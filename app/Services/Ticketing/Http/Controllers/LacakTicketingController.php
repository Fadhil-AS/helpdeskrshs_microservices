<?php

namespace App\Services\Ticketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\Ticketing\Traits\NotifikasiWhatsappPelapor;
use Illuminate\Support\Facades\DB;

class LacakTicketingController extends Controller
{
    use NotifikasiWhatsappPelapor;

    public function getLacakTicketing()
    {
        return view('Services.Ticketing.lacakTicket.mainLacakTicketing');
    }

    public function tanggapiPenyelesaian(Request $request, $id_complaint)
    {
        $validator = Validator::make($request->all(), [
            'tanggapan' => 'required|string|in:selesai,belum_selesai',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Input tidak valid.', 'errors' => $validator->errors()], 422);
        }

        try {
            DB::transaction(function () use ($request, $id_complaint, &$pesanSukses, &$redirectUrl, &$laporan) {
                $laporan = Laporan::find($id_complaint);
                if (!$laporan) {
                    return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
                }

                $statusMenunggu = ['Menunggu Konfirmasi', 'Menunggu Konfirmasi Pelapor'];
                if (!in_array($laporan->STATUS, $statusMenunggu)) {
                    throw new \Exception('Tiket tidak dalam status menunggu konfirmasi. Status saat ini: ' . $laporan->STATUS, 400);
                }

                $tanggapan = $request->tanggapan;
                $pesanSukses = '';
                $redirectUrl = null;

                if ($tanggapan === 'selesai') {
                    $laporan->STATUS = 'Close';
                    $laporan->RATING_LAPORAN = 'Masalah terselesaikan';
                    $laporan->TGL_SELESAI = Carbon::now()->toDateString();
                    $pesanSukses = 'Terima kasih atas konfirmasi Anda. Tiket telah ditutup. Silakan berikan feedback tambahan jika berkenan.';
                    $laporan->TGL_INSROW = Carbon::now()->toDateString();
                    $laporan->save();

                    // Cek apakah tiket ini adalah tiket lanjutan (banding)
                    if (!empty($laporan->ID_COMPLAINT_REFERENSI)) {
                        // Cari tiket lama yang berstatus 'Banding'
                        $laporanReferensi = Laporan::find($laporan->ID_COMPLAINT_REFERENSI);

                        // Jika tiket lama ditemukan, tutup juga
                        if ($laporanReferensi) {
                            $laporanReferensi->STATUS = 'Close';
                            $laporanReferensi->TGL_SELESAI = Carbon::now();
                            $laporanReferensi->TGL_INSROW = Carbon::now()->toDateString();
                            $laporanReferensi->save();

                            Log::info('Tiket referensi ' . $laporanReferensi->ID_COMPLAINT . ' juga telah ditutup karena tiket lanjutan ' . $laporan->ID_COMPLAINT . ' selesai.');
                        }
                    }
                } elseif ($tanggapan === 'belum_selesai') {
                    $laporan->STATUS = 'Banding';
                    $laporan->RATING_LAPORAN = null;
                    $pesanSukses = 'Terima kasih atas informasinya. Laporan Anda telah diajukan untuk peninjauan kembali (banding).';
                    $redirectUrl = route('ticketing.buat-laporan', ['ref' => $laporan->ID_COMPLAINT]);
                    $laporan->TGL_INSROW = Carbon::now()->toDateString();
                    $laporan->save();
                }

                if ($tanggapan === 'selesai') {
                    $this->kirimNotifikasiStatusKePelapor($laporan);
                }
            });

            $laporanTerbaru = Laporan::find($id_complaint);

            return response()->json(['success' => true, 'message' => $pesanSukses, 'new_status' => $laporanTerbaru->STATUS, 'redirect_url' => $redirectUrl]);

        } catch (\Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 500) ? $e->getCode() : 500;
            Log::error("Error di tanggapiPenyelesaian untuk ID {$id_complaint}: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => $statusCode === 400 ? $e->getMessage() : 'Terjadi kesalahan server internal.'], $statusCode);
        }
    }

    public function searchTicket(Request $request)
    {
        $searchInput = $request->input('searchInput');

        if (empty($searchInput)) {
            return response()->json(['success' => false, 'message' => 'Input pencarian tidak boleh kosong.'], 400);
        }

        try {
            $laporan = Laporan::with(['unitKerja', 'penyelesaianPengaduan'])
                ->where('ID_COMPLAINT', $searchInput)
                ->first();

            if ($laporan) {
                $riwayatPenanganan = [];

                // History: Tiket Diterima (oleh Humas)
                if (!in_array($laporan->STATUS, ['Baru', 'Open']) || $laporan->ID_BAGIAN) {
                    $waktuDiterima = $laporan->TGL_COMPLAINT ?? $laporan->TGL_DISPOSISI ?? $laporan->updated_at;
                    $riwayatPenanganan[] = [
                        'tanggal_aksi' => Carbon::parse($waktuDiterima)->format('d M Y'),
                        'aktor' => 'Humas',
                        'judul_aksi' => 'Tiket Diterima',
                        'deskripsi_aksi' => 'Tiket telah diterima dan sedang diproses.',
                    ];
               }

                // History: Diteruskan ke Unit Kerja
                if ($laporan->ID_BAGIAN && isset($laporan->unitKerja)) {
                    $waktuDisposisi = $laporan->TGL_DISPOSISI ?? $laporan->TGL_EVALUASI ?? $laporan->updated_at;
                    $riwayatPenanganan[] = [
                        'tanggal_aksi' => Carbon::parse($waktuDisposisi)->format('d M Y'),
                        'aktor' => 'Humas',
                        'judul_aksi' => 'Diteruskan ke Unit Kerja',
                        'deskripsi_aksi' => 'Laporan diteruskan ke <b>' . $laporan->unitKerja->NAMA_BAGIAN . '</b> untuk penanganan lebih lanjut.',
                    ];
                }

                // History: Klarifikasi dari Unit Kerja
                if (!empty($laporan->EVALUASI_COMPLAINT) && !empty($laporan->TGL_EVALUASI)) {
                    $riwayatPenanganan[] = [
                        'tanggal_aksi' => Carbon::parse($laporan->TGL_EVALUASI)->format('d M Y'),
                        'aktor' => $laporan->unitKerja->NAMA_BAGIAN ?? 'Unit Kerja',
                        'judul_aksi' => 'Klarifikasi sudah diproses',
                        'deskripsi_aksi' => 'Klarifikasi sudah diproses dengan hasil <b>'. $laporan->EVALUASI_COMPLAINT . '</b> dan akan ditindak lanjuti oleh Humas.',
                    ];
                }

                 // History: Tindak Lanjut oleh Humas (berdasarkan penyelesaian)
                if (!empty($laporan->DISPOSISI) || !empty($laporan->TINDAK_LANJUT_HUMAS)) {
                    $deskripsiTindakLanjut = '';
                    if (!empty($laporan->DISPOSISI)) {
                        $deskripsiTindakLanjut = 'Laporan sudah ditindak lanjuti oleh Humas dengan hasil <b>' . $laporan->DISPOSISI . '</b> dan <b>'. $laporan->TINDAK_LANJUT_HUMAS . '</b>.';
                    } elseif (!empty($laporan->TINDAK_LANJUT_HUMAS)) {
                        $deskripsiTindakLanjut = 'Laporan sudah ditindak lanjuti oleh Humas dengan keterangan: <b>' . $laporan->TINDAK_LANJUT_HUMAS . '</b>';
                    }

                    $riwayatPenanganan[] = [
                        'tanggal_aksi' => Carbon::parse($laporan->TGL_SELESAI ?? $laporan->updated_at)->format('d M Y'),
                        'aktor' => 'Humas',
                        'judul_aksi' => 'Sudah ditindak lanjuti oleh Humas',
                        'deskripsi_aksi' => $deskripsiTindakLanjut,
                    ];
                }

                // History: Menunggu Konfirmasi
                if (in_array($laporan->STATUS, ['Menunggu Konfirmasi', 'Menunggu Konfirmasi Pelapor'])) {
                    $waktuMenunggu = $laporan->TGL_EVALUASI ?? $laporan->updated_at;
                    $riwayatPenanganan[] = [
                        'tanggal_aksi' => Carbon::parse($waktuMenunggu)->format('d M Y'),
                        'aktor' => 'Humas',
                        'judul_aksi' => 'Menunggu Konfirmasi Pelapor',
                        'deskripsi_aksi' => 'Klarifikasi telah diberikan, sistem menunggu tanggapan dari Anda.',
                    ];
               }

                $waktuTanggapan = $laporan->TGL_INSROW ?? $laporan->updated_at;
                if ($laporan->STATUS === 'Banding') {
                     $riwayatPenanganan[] = [
                        'tanggal_aksi' => Carbon::parse($waktuTanggapan)->format('d M Y'),
                        'aktor' => 'Pelapor',
                        'judul_aksi' => 'Pengajuan Banding',
                        'deskripsi_aksi' => 'Pelapor menyatakan masalah belum selesai dan meminta peninjauan kembali.',
                    ];
                } else if (in_array($laporan->STATUS, ['Close', 'Selesai'])) {
                    $deskripsiTutup = $laporan->RATING_LAPORAN === 'Masalah terselesaikan'
                                       ? 'Pelapor mengkonfirmasi masalah telah terselesaikan.'
                                       : 'Tiket telah ditutup oleh sistem.';
                     $riwayatPenanganan[] = [
                        'tanggal_aksi' => Carbon::parse($waktuTanggapan)->format('d M Y'),
                        'aktor' => 'Pelapor',
                        'judul_aksi' => 'Tiket Ditutup',
                        'deskripsi_aksi' => 'Pelapor mengkonfirmasi masalah telah terselesaikan.',
                    ];
                }

                if (property_exists($laporan, 'KETERANGAN') && !empty($laporan->KETERANGAN)) {
                    $entries = explode(';;', trim($laporan->KETERANGAN));
                    foreach ($entries as $entry) {
                        if (empty(trim($entry))) continue;
                        $parts = explode('|', $entry, 4);
                        if (count($parts) >= 3) {
                            $riwayatPenanganan[] = [
                                'tanggal_aksi' => Carbon::parse(trim($parts[0]))->format('d M Y'),
                                'aktor' => trim($parts[1]),
                                'judul_aksi' => trim($parts[2]),
                                'deskripsi_aksi' => isset($parts[3]) ? trim($parts[3]) : '',
                            ];
                        }
                    }
                }

                $uniqueRiwayat = [];
                $keys = [];
                foreach ($riwayatPenanganan as $item) {
                    $key = $item['aktor'] . '|' . $item['judul_aksi'];
                    if (!in_array($key, $keys)) {
                        $uniqueRiwayat[] = $item;
                        $keys[] = $key;
                    }
                }
                $riwayatPenanganan = $uniqueRiwayat;

                $statusMapping = [
                    'Open' => 'Terbuka',
                    'Baru' => 'Baru',
                    'On Progress' => 'Dalam Proses',
                    'Dalam Proses' => 'Dalam Proses',
                    'Menunggu Konfirmasi' => 'Menunggu Konfirmasi Pelapor',
                    'Menunggu Konfirmasi Pelapor' => 'Menunggu Konfirmasi Pelapor',
                    'Close' => 'Selesai',
                    'Selesai' => 'Selesai',
                    'Banding' => 'Banding',
                ];
                $displayStatus = $statusMapping[$laporan->STATUS] ?? $laporan->STATUS;

                $deskripsiStatusTerkini = 'Informasi status tiket Anda.';
                switch ($laporan->STATUS) {
                    case 'Open':
                    case 'Baru':
                        $deskripsiStatusTerkini = 'Laporan Anda telah kami terima dan akan segera diteruskan ke bagian terkait.';
                        break;
                    case 'On Progress':
                    case 'Dalam Proses':
                        $deskripsiStatusTerkini = 'Laporan Anda sedang dalam proses penanganan oleh ' . ($laporan->unitKerja->NAMA_BAGIAN ?? 'tim terkait') . '.';
                        break;
                    case 'Menunggu Konfirmasi':
                    case 'Menunggu Konfirmasi Pelapor':
                        $deskripsiStatusTerkini = 'Klarifikasi atau solusi telah diberikan. Mohon periksa riwayat penanganan dan berikan konfirmasi Anda.';
                        break;
                    case 'Banding':
                        $deskripsiStatusTerkini = 'Anda menyatakan laporan belum selesai. Laporan Anda sedang dalam proses peninjauan kembali (banding).';
                        break;
                    case 'Close':
                    case 'Selesai':
                        $deskripsiStatusTerkini = 'Laporan Anda telah diselesaikan.';
                        break;
                    default:
                        $deskripsiStatusTerkini = 'Status laporan Anda saat ini: ' . $displayStatus . '.';
                }

                $isMenungguKonfirmasi = in_array($laporan->STATUS, ['Menunggu Konfirmasi', 'Menunggu Konfirmasi Pelapor']);
                $waktuKonfirmasiTersisa = null;
                $persenWaktuKonfirmasi = '0%';
                $tglSelesaiInternalISO = null;

                if ($isMenungguKonfirmasi && $laporan->TGL_EVALUASI) {
                    $tglSelesaiInternal = Carbon::parse($laporan->TGL_EVALUASI);
                    $tglSelesaiInternalISO = $tglSelesaiInternal->toIso8601String();
                    $batasWaktuKonfirmasi = $tglSelesaiInternal->copy()->addHours(24);
                    $sekarang = Carbon::now();
                    if ($sekarang->lt($batasWaktuKonfirmasi)) {
                        $sisaDetik = $sekarang->diffInSeconds($batasWaktuKonfirmasi, false);
                        if ($sisaDetik > 0) {
                            $jam = floor($sisaDetik / 3600);
                            $menit = floor(($sisaDetik % 3600) / 60);
                            $detik = $sisaDetik % 60;
                            $waktuKonfirmasiTersisa = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
                            $totalDurasiDetik = 24 * 3600;
                            $persenWaktuKonfirmasi = (($sisaDetik / $totalDurasiDetik) * 100) . '%';
                        } else {
                            $waktuKonfirmasiTersisa = "Waktu habis";
                        }
                    } else {
                        $waktuKonfirmasiTersisa = "Waktu habis";
                    }
                }

                $tanggalDiperbaruiFormatted = $laporan->updated_at ? Carbon::parse($laporan->updated_at)->format('d/m/Y') : Carbon::parse($laporan->TGL_COMPLAINT)->format('d/m/Y');
                if ($laporan->TGL_EVALUASI && Carbon::parse($laporan->TGL_EVALUASI)->gt(Carbon::parse($laporan->updated_at ?: $laporan->TGL_COMPLAINT))) {
                    $tanggalDiperbaruiFormatted = Carbon::parse($laporan->TGL_EVALUASI)->format('d/m/Y');
                }

                $data = [
                    'success' => true,
                    'tiket' => [
                        'id_complaint' => $laporan->ID_COMPLAINT,
                        'status' => $displayStatus,
                        'tanggal_dibuat' => Carbon::parse($laporan->TGL_COMPLAINT)->format('d/m/Y'),
                        'tanggal_diperbarui' => $tanggalDiperbaruiFormatted,
                        'ditangani_oleh' => $laporan->unitKerja ? $laporan->unitKerja->NAMA_BAGIAN : 'Belum Ditentukan',
                        'deskripsi_status_terkini' => $deskripsiStatusTerkini,
                        'tgl_selesai_internal' => $tglSelesaiInternalISO,
                        'is_menunggu_konfirmasi' => $isMenungguKonfirmasi,
                        'waktu_konfirmasi_tersisa' => $waktuKonfirmasiTersisa,
                        'persen_waktu_konfirmasi' => $persenWaktuKonfirmasi,
                        'tanggal_complaint_timelineFormat' => Carbon::parse($laporan->TGL_COMPLAINT)->format('d M Y'),
                    ],
                    'riwayat_penanganan' => $riwayatPenanganan,
                ];
                return response()->json($data);
            } else {
                return response()->json(['success' => false, 'message' => 'Data tiket tidak ditemukan.'], 404);
            }

        } catch (\Exception $e) {
            Log::error("Error di searchTicket: " . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server internal saat mencari tiket.'], 500);
        }
    }

    public function simpanFeedback(Request $request){
        $validator = Validator::make($request->all(), [
            'id_complaint'  => 'required|string|exists:data_complaint,ID_COMPLAINT',
            'rating'        => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string|max:4000',
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi GAGAL untuk simpanFeedback:', [
                'id' => $request->id_complaint,
                'errors' => $validator->errors()->toArray(),
                'request_data_received' => $request->all()
            ]);
            return response()->json(['success' => false, 'message' => 'Input untuk feedback tidak valid.', 'errors' => $validator->errors()], 422);
        }

        try {
            $laporan = Laporan::find($request->id_complaint);
            if (!$laporan) { return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404); }

            $laporan->RATING_LAPORAN = (string) $request->rating;
            $laporan->FEEDBACK_PELAPOR = $request->input('feedback_text', null);

            $laporan->TGL_INSROW = Carbon::now()->toDateString();
            $laporan->save();

            return response()->json(['success' => true, 'message' => 'Terima kasih atas feedback dan penilaian Anda!']);
        } catch (\Exception $e) {
                Log::error("Error di simpanFeedback untuk ID {$request->id_complaint}: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server internal saat menyimpan feedback.'], 500);
            }
        }
}

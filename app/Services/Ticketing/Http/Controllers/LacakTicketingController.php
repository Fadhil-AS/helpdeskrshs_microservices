<?php

namespace App\Services\Ticketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LacakTicketingController extends Controller
{
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
            $laporan = Laporan::find($id_complaint);

            if (!$laporan) {
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
            }

            if ($laporan->STATUS !== 'Menunggu Konfirmasi Pelapor') {
                return response()->json(['success' => false, 'message' => 'Tiket tidak dalam status menunggu konfirmasi. Status saat ini: ' . $laporan->STATUS], 400);
            }

            $tanggapan = $request->tanggapan;
            // $aktor = "Pelapor"; // Tidak perlu jika KETERANGAN tidak dipakai
            // $tanggalAksiUntukKeterangan = Carbon::now()->toDateTimeString(); // Tidak perlu jika KETERANGAN tidak dipakai
            $tanggalUntukKolomDatabase = Carbon::now()->toDateString(); // Sesuai tipe DATE untuk TGL_INSROW

            // $keteranganSebelumnya = $laporan->KETERANGAN ? trim($laporan->KETERANGAN) . ';;' : ''; // Tidak perlu jika KETERANGAN tidak dipakai
            // $maxLengthKeterangan = 50; // Tidak perlu jika KETERANGAN tidak dipakai

            $ratingLaporanString = '';

            if ($tanggapan === 'selesai') {
                $laporan->STATUS = 'Close';
                $ratingLaporanString = 'Masalah terselesaikan';
                // $judulAksi = "Mslh Dikonfirmasi Selesai"; // Tidak perlu jika KETERANGAN tidak dipakai
                // $deskripsiAksi = "Pelapor mengkonfirmasi bahwa masalah telah terselesaikan. Rating: Masalah terselesaikan."; // Tidak perlu
            } elseif ($tanggapan === 'belum_selesai') {
                $laporan->STATUS = 'Open';
                $ratingLaporanString = 'Masalah belum terselesaikan';
                // $judulAksi = "Masalah Dinyatakan Belum Selesai"; // Tidak perlu
                // $deskripsiAksi = "Pelapor menyatakan bahwa masalah belum terselesaikan dan meminta tindak lanjut kembali. Rating: Masalah belum terselesaikan."; // Tidak perlu
            }

            $laporan->RATING_LAPORAN = $ratingLaporanString;
            $laporan->TGL_INSROW = $tanggalUntukKolomDatabase;

            // $keteranganBaru = "{$tanggalAksiUntukKeterangan}|{$aktor}|{$judulAksi}|{$deskripsiAksi}"; // Tidak perlu
            // $fullKeterangan = $keteranganSebelumnya . $keteranganBaru; // Tidak perlu
            // Logika pemotongan KETERANGAN dihapus
            // $laporan->KETERANGAN = ...; // BARIS INI DAN LOGIKA DI ATASNYA DIHAPUS

            // Eloquent akan otomatis mengisi 'updated_at' karena $timestamps = true di Model
            $laporan->save();

            $pesanSukses = ($tanggapan === 'selesai') ? 'Terima kasih atas konfirmasi Anda. Tiket telah ditutup.' : 'Terima kasih atas informasinya. Laporan Anda akan kami tindak lanjuti kembali.';
            return response()->json(['success' => true, 'message' => $pesanSukses, 'new_status' => $laporan->STATUS]);

        } catch (\Exception $e) {
            Log::error("Error di tanggapiPenyelesaian untuk ID {$id_complaint}: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server internal saat memproses tanggapan Anda.'], 500);
        }
    }

    public function searchTicket(Request $request)
    {
        $searchInput = $request->input('searchInput');

        if (empty($searchInput)) {
            return response()->json(['success' => false, 'message' => 'Input pencarian tidak boleh kosong.'], 400);
        }

        try {
            $laporan = Laporan::with('unitKerja')
                ->where('ID_COMPLAINT', $searchInput)
                ->first();

            if ($laporan) {
                $riwayatPenanganan = [];
                // Asumsi: Kolom KETERANGAN masih ada di DB sesuai migrasi,
                // tapi mungkin tidak diisi data baru jika Anda sudah menghapus logikanya.
                // Jika ada data lama di KETERANGAN, ini akan mencoba mem-parsingnya.
                if (property_exists($laporan, 'KETERANGAN') && !empty($laporan->KETERANGAN)) {
                    $entries = explode(';;', trim($laporan->KETERANGAN));
                    foreach ($entries as $entry) {
                        if (empty(trim($entry))) continue;
                        $parts = explode('|', $entry, 4);
                        if (count($parts) >= 3) { // Membutuhkan setidaknya tanggal, aktor, judul
                            try {
                                $tanggalAksiRaw = trim($parts[0]);
                                $tanggalAksiFormatted = Carbon::parse($tanggalAksiRaw)->format('d M Y \p\u\k\u\l H:i');
                                $riwayatPenanganan[] = [
                                    'tanggal_aksi' => $tanggalAksiFormatted,
                                    'aktor' => trim($parts[1]),
                                    'judul_aksi' => trim($parts[2]),
                                    'deskripsi_aksi' => isset($parts[3]) ? trim($parts[3]) : '',
                                ];
                            } catch (\Exception $e) {
                                Log::error("Gagal parsing entri riwayat dari KETERANGAN: '{$entry}'. Error: " . $e->getMessage());
                                // Tambahkan entri fallback jika parsing gagal agar tidak crash
                                $riwayatPenanganan[] = [
                                    'tanggal_aksi' => 'N/A',
                                    'aktor' => isset($parts[1]) ? trim($parts[1]) : 'Sistem',
                                    'judul_aksi' => 'Error Parsing Data Riwayat',
                                    'deskripsi_aksi' => 'Data riwayat untuk entri ini tidak dapat ditampilkan.',
                                ];
                            }
                        } else {
                             Log::warning("Entri riwayat (KETERANGAN) tidak memiliki format yang benar: '{$entry}' pada tiket {$laporan->ID_COMPLAINT}");
                        }
                    }
                }
                // else {
                // Jika KETERANGAN tidak ada atau kosong, $riwayatPenanganan akan jadi array kosong.
                // JavaScript akan menangani kasus ini dengan menampilkan pesan default "Tiket Dibuat".
                // }


                $deskripsiStatusTerkini = 'Informasi status tiket Anda.';
                if ($laporan->STATUS === 'Open' || $laporan->STATUS === 'Baru') {
                    $deskripsiStatusTerkini = 'Laporan Anda telah kami terima dan akan segera diproses.';
                } elseif ($laporan->STATUS === 'On Progress' || $laporan->STATUS === 'Dalam Proses') {
                    $deskripsiStatusTerkini = 'Laporan Anda sedang dalam proses penanganan oleh tim terkait.';
                } elseif ($laporan->STATUS === 'Menunggu Konfirmasi Pelapor') {
                    $deskripsiStatusTerkini = 'Solusi telah diberikan. Mohon konfirmasi apakah masalah Anda telah terselesaikan.';
                } elseif ($laporan->STATUS === 'Close' || $laporan->STATUS === 'Selesai') {
                    $deskripsiStatusTerkini = 'Laporan Anda telah diselesaikan.';
                     if ($laporan->RATING_LAPORAN === 'Masalah terselesaikan') {
                        $deskripsiStatusTerkini .= ' Pelapor mengkonfirmasi masalah telah selesai.';
                    } elseif ($laporan->RATING_LAPORAN === 'Masalah belum terselesaikan' && $laporan->STATUS !== 'Open') {
                        $deskripsiStatusTerkini .= ' Pelapor sebelumnya menyatakan masalah belum selesai.';
                    }
                }

                $isMenungguKonfirmasi = false;
                $waktuKonfirmasiTersisa = null;
                $persenWaktuKonfirmasi = '0%';
                $tglSelesaiInternalISO = null;

                if ($laporan->STATUS === 'Menunggu Konfirmasi Pelapor' && $laporan->TGL_SELESAI) {
                    $isMenungguKonfirmasi = true;
                    $tglSelesaiInternal = Carbon::parse($laporan->TGL_SELESAI);
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
                            $persenWaktuKonfirmasi = ($sisaDetik / $totalDurasiDetik) * 100;
                            $persenWaktuKonfirmasi = min(max($persenWaktuKonfirmasi, 0), 100) . '%';
                        } else {
                            $waktuKonfirmasiTersisa = "Waktu habis";
                            $persenWaktuKonfirmasi = '0%';
                        }
                    } else {
                        $waktuKonfirmasiTersisa = "Waktu habis";
                        $persenWaktuKonfirmasi = '0%';
                    }
                }

                $tanggalDiperbaruiFormatted = $laporan->updated_at ? Carbon::parse($laporan->updated_at)->format('d/m/Y H:i') : ($laporan->TGL_INSROW ? Carbon::parse($laporan->TGL_INSROW)->format('d/m/Y') : Carbon::parse($laporan->TGL_COMPLAINT)->format('d/m/Y H:i'));
                // Jika TGL_EVALUASI lebih baru, gunakan itu sebagai tanggal diperbarui
                if ($laporan->TGL_EVALUASI && Carbon::parse($laporan->TGL_EVALUASI)->gt(Carbon::parse($laporan->updated_at ?: $laporan->TGL_INSROW ?: $laporan->TGL_COMPLAINT ))) {
                    $tanggalDiperbaruiFormatted = Carbon::parse($laporan->TGL_EVALUASI)->format('d/m/Y H:i');
                }

                $data = [
                    'success' => true,
                    'tiket' => [
                        'id_complaint' => $laporan->ID_COMPLAINT,
                        'status' => $laporan->STATUS,
                        'rating_laporan' => $laporan->RATING_LAPORAN,
                        'tanggal_dibuat' => Carbon::parse($laporan->TGL_COMPLAINT)->format('d/m/Y H:i'),
                        'tanggal_complaint_timelineFormat' => Carbon::parse($laporan->TGL_COMPLAINT)->format('d M Y \p\u\k\u\l H:i'), // Digunakan JS untuk default riwayat
                        'tanggal_diperbarui' => $tanggalDiperbaruiFormatted,
                        'ditangani_oleh' => $laporan->unitKerja ? $laporan->unitKerja->NAMA_BAGIAN : ($laporan->ID_BAGIAN ?? 'Belum Ditentukan'),
                        'deskripsi_status_terkini' => $deskripsiStatusTerkini,
                        'tgl_selesai_internal' => $tglSelesaiInternalISO,
                        'is_menunggu_konfirmasi' => $isMenungguKonfirmasi,
                        'waktu_konfirmasi_tersisa' => $waktuKonfirmasiTersisa,
                        'persen_waktu_konfirmasi' => $persenWaktuKonfirmasi,
                        'evaluasi_complaint' => $laporan->EVALUASI_COMPLAINT,
                        'tgl_evaluasi' => $laporan->TGL_EVALUASI ? Carbon::parse($laporan->TGL_EVALUASI)->format('d/m/Y H:i') : null,
                    ],
                    'riwayat_penanganan' => $riwayatPenanganan, // PERUBAHAN: tidak lagi di-reverse
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
            'id_complaint' => 'required|string|exists:data_complaint,ID_COMPLAINT',
            'rating_laporan' => 'required|string|in:Masalah terselesaikan,Masalah belum terselesaikan',
            'feedback_text_optional' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Input untuk feedback tidak valid.', 'errors' => $validator->errors()], 422);
        }

        try {
            $laporan = Laporan::find($request->id_complaint);
            if (!$laporan) {
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
            }

            $laporan->RATING_LAPORAN = $request->rating_laporan;

            if ($request->filled('feedback_text_optional')) {
                $laporan->FEEDBACK_PELAPOR = $request->feedback_text_optional;
            }

            // Kolom KETERANGAN tidak lagi diisi di sini
            // $aktor = "Pelapor";
            // $tanggalAksiUntukKeterangan = Carbon::now()->toDateTimeString();
            // $tanggalUntukKolomDatabase = Carbon::now()->toDateString();
            // $keteranganSebelumnya = $laporan->KETERANGAN ? trim($laporan->KETERANGAN) . ';;' : '';
            // $judulAksi = "Rating Laporan Diperbarui";
            // ... (logika KETERANGAN dihapus) ...

            $laporan->TGL_INSROW = Carbon::now()->toDateString(); // Sesuai tipe DATE untuk TGL_INSROW
            // Eloquent akan otomatis mengisi 'updated_at'
            $laporan->save();

            return response()->json(['success' => true, 'message' => 'Rating laporan berhasil disimpan!']);

        } catch (\Exception $e) {
            Log::error("Error di simpanFeedback untuk ID {$request->id_complaint}: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server internal saat menyimpan feedback.'], 500);
        }
    }
}

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
            $aktor = "Pelapor";
            $tanggalAksi = Carbon::now()->toDateTimeString();
            $keteranganSebelumnya = $laporan->KETERANGAN ? trim($laporan->KETERANGAN) . ';;' : '';

            // Definisikan batas maksimal panjang untuk kolom KETERANGAN
            $maxLengthKeterangan = 50; // Sesuai dengan migrasi $table->string('KETERANGAN', 50)

            if ($tanggapan === 'selesai') {
                $laporan->STATUS = 'Close';
                $laporan->RATING_LAPORAN = 'Masalah terselesaikan';
                $laporan->TGL_INSROW = Carbon::now()->toDateTimeString(); // Menggunakan toDateTimeString() agar format konsisten

                $judulAksi = "Masalah Dikonfirmasi Selesai";
                $deskripsiAksi = "Pelapor mengkonfirmasi bahwa masalah telah terselesaikan. Rating: Masalah terselesaikan.";

                $keteranganBaru = "{$tanggalAksi}|{$aktor}|{$judulAksi}|{$deskripsiAksi}";
                $fullKeterangan = $keteranganSebelumnya . $keteranganBaru;

                // Potong string jika lebih panjang dari batas maksimal kolom KETERANGAN
                // Pertimbangkan panjang $keteranganSebelumnya juga.
                // Jika $keteranganSebelumnya sudah panjang, $keteranganBaru mungkin perlu dipotong lebih banyak.
                // Untuk implementasi sederhana, kita potong hasil gabungannya:
                if (strlen($fullKeterangan) > $maxLengthKeterangan) {
                    // Logika pemotongan bisa lebih canggih, misalnya memotong dari $keteranganBaru saja
                    // atau memberi notifikasi bahwa data terlalu panjang.
                    // Di sini kita potong dari akhir $fullKeterangan agar pas.
                    // Ini mungkin memotong bagian penting dari $keteranganBaru.
                    // Alternatif: potong $keteranganBaru sebelum digabung.
                    $sisaPanjang = $maxLengthKeterangan - strlen($keteranganSebelumnya);
                    if ($sisaPanjang > 0) { // Jika masih ada ruang setelah keterangan sebelumnya
                        $keteranganBaruPotong = substr($keteranganBaru, 0, $sisaPanjang);
                        $laporan->KETERANGAN = $keteranganSebelumnya . $keteranganBaruPotong;
                    } else {
                        // Jika keterangan sebelumnya sudah memenuhi atau melebihi,
                        // mungkin tidak menambahkan keterangan baru atau hanya penanda.
                        // Untuk contoh ini, kita akan memotong $fullKeterangan dari awal,
                        // namun ini akan menghapus history lama.
                        // Lebih baik, jika keterangan sebelumnya sudah penuh, hanya tambahkan penanda singkat.
                        // Atau, yang paling aman adalah memotong $keteranganBaru agar pas.
                        $laporan->KETERANGAN = substr($fullKeterangan, 0, $maxLengthKeterangan);
                        Log::warning("Keterangan untuk tiket {$id_complaint} dipotong karena melebihi {$maxLengthKeterangan} karakter.");
                    }
                } else {
                    $laporan->KETERANGAN = $fullKeterangan;
                }

                $laporan->save();

                return response()->json(['success' => true, 'message' => 'Terima kasih atas konfirmasi Anda. Tiket telah ditutup.', 'new_status' => $laporan->STATUS]);

            } elseif ($tanggapan === 'belum_selesai') {
                $laporan->STATUS = 'Open';
                $laporan->RATING_LAPORAN = 'Masalah belum terselesaikan';
                $laporan->TGL_INSROW = Carbon::now()->toDateTimeString(); // Menggunakan toDateTimeString()

                $judulAksi = "Masalah Dinyatakan Belum Selesai";
                $deskripsiAksi = "Pelapor menyatakan bahwa masalah belum terselesaikan dan meminta tindak lanjut kembali. Rating: Masalah belum terselesaikan.";

                $keteranganBaru = "{$tanggalAksi}|{$aktor}|{$judulAksi}|{$deskripsiAksi}";
                $fullKeterangan = $keteranganSebelumnya . $keteranganBaru;

                // Potong string jika lebih panjang dari batas maksimal kolom KETERANGAN
                if (strlen($fullKeterangan) > $maxLengthKeterangan) {
                    $sisaPanjang = $maxLengthKeterangan - strlen($keteranganSebelumnya);
                     if ($sisaPanjang > 0) {
                        $keteranganBaruPotong = substr($keteranganBaru, 0, $sisaPanjang);
                        $laporan->KETERANGAN = $keteranganSebelumnya . $keteranganBaruPotong;
                    } else {
                        $laporan->KETERANGAN = substr($fullKeterangan, 0, $maxLengthKeterangan);
                        Log::warning("Keterangan untuk tiket {$id_complaint} dipotong karena melebihi {$maxLengthKeterangan} karakter.");
                    }
                } else {
                    $laporan->KETERANGAN = $fullKeterangan;
                }

                $laporan->save();

                return response()->json(['success' => true, 'message' => 'Terima kasih atas informasinya. Laporan Anda akan kami tindak lanjuti kembali.', 'new_status' => $laporan->STATUS]);
            }
        } catch (\Exception $e) {
            Log::error("Error di tanggapiPenyelesaian untuk ID {$id_complaint}: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server saat memproses tanggapan Anda.'], 500);
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
                if (!empty($laporan->KETERANGAN)) {
                    $entries = explode(';;', trim($laporan->KETERANGAN));
                    foreach ($entries as $entry) {
                        if (empty(trim($entry))) continue;

                        $parts = explode('|', $entry, 4);
                        if (count($parts) >= 3) {
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
                                Log::error("Gagal parsing entri riwayat: '{$entry}'. Error: " . $e->getMessage());
                                $riwayatPenanganan[] = [
                                    'tanggal_aksi' => 'N/A',
                                    'aktor' => isset($parts[1]) ? trim($parts[1]) : 'Sistem',
                                    'judul_aksi' => 'Error Parsing Data Riwayat',
                                    'deskripsi_aksi' => 'Data riwayat untuk entri ini tidak dapat ditampilkan.',
                                ];
                            }
                        } else {
                             Log::warning("Entri riwayat tidak memiliki format yang benar (kurang dari 4 bagian setelah explode): '{$entry}' pada tiket {$laporan->ID_COMPLAINT}");
                        }
                    }
                }

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
                    } elseif ($laporan->RATING_LAPORAN === 'Masalah belum terselesaikan') {
                        $deskripsiStatusTerkini .= ' Pelapor menyatakan masalah belum selesai';
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

                $data = [
                    'success' => true,
                    'tiket' => [
                        'id_complaint' => $laporan->ID_COMPLAINT,
                        'status' => $laporan->STATUS,
                        'rating_laporan' => $laporan->RATING_LAPORAN,
                        'tanggal_dibuat' => Carbon::parse($laporan->TGL_COMPLAINT)->format('d/m/Y'),
                        'tanggal_complaint_timelineFormat' => Carbon::parse($laporan->TGL_COMPLAINT)->format('d M Y'),
                        'tanggal_diperbarui' => $laporan->TGL_EVALUASI ? Carbon::parse($laporan->TGL_EVALUASI)->format('d/m/Y') : '-',
                        'ditangani_oleh' => $laporan->unitKerja ? $laporan->unitKerja->NAMA_BAGIAN : ($laporan->ID_BAGIAN ?? 'Belum Ditentukan'),
                        'deskripsi_status_terkini' => $deskripsiStatusTerkini,
                        'tgl_selesai_internal' => $tglSelesaiInternalISO,
                        'is_menunggu_konfirmasi' => $isMenungguKonfirmasi,
                        'waktu_konfirmasi_tersisa' => $waktuKonfirmasiTersisa,
                        'persen_waktu_konfirmasi' => $persenWaktuKonfirmasi,
                        'evaluasi_complaint' => $laporan->EVALUASI_COMPLAINT,
                        'tgl_evaluasi' => $laporan->TGL_EVALUASI ? Carbon::parse($laporan->TGL_EVALUASI)->format('d/m/Y H:i') : null,
                    ],
                    'riwayat_penanganan' => $riwayatPenanganan,
                ];
                return response()->json($data);
            } else {
                return response()->json(['success' => false, 'message' => 'Data tiket tidak ditemukan.'], 404);
            }

        } catch (\Exception $e) {
            Log::error("Error di searchTicket: " . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan di server saat memproses permintaan Anda.'], 500);
        }
    }

    public function simpanFeedback(Request $request){
        $validator = Validator::make($request->all(), [
            'id_complaint' => 'required|string|exists:data_complaint,ID_COMPLAINT',
            'rating' => 'required|integer|min:0|max:5', // Asumsi rating bintang 1-5, 0 jika tidak ada
            'feedback' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Input tidak valid.', 'errors' => $validator->errors()], 422);
        }

        try {
            $laporan = Laporan::find($request->id_complaint);
            if (!$laporan) {
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
            }
            $laporan->FEEDBACK_PELAPOR = $request->feedback; // Sesuaikan nama kolom di DB Anda
            if ($request->filled('rating') && $request->rating > 0) {
                 // $laporan->RATING_PELAPOR_BINTANG = $request->rating; // Contoh jika ada kolom rating bintang
            }
            $aktor = "Pelapor";
            $tanggalAksi = Carbon::now()->toDateTimeString();
            $keteranganSebelumnya = $laporan->KETERANGAN ? trim($laporan->KETERANGAN) . ';;' : '';
            $judulAksi = "Feedback Diberikan oleh Pelapor";
            $deskripsiAksi = "Pelapor memberikan feedback: " . ($request->feedback ?: '(tidak ada komentar)') . ". Rating Bintang: " . ($request->rating ?: 'N/A');
            $laporan->KETERANGAN = $keteranganSebelumnya . "{$tanggalAksi}|{$aktor}|{$judulAksi}|{$deskripsiAksi}";

            $laporan->TGL_INSROW = Carbon::now();
            $laporan->save();

            return response()->json(['success' => true, 'message' => 'Terima kasih atas feedback Anda!']);

        } catch (\Exception $e) {
            Log::error("Error di simpanFeedback untuk ID {$request->id_complaint}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server saat menyimpan feedback.'], 500);
        }
    }
}

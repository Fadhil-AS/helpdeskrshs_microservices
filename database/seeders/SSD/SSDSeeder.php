<?php

namespace Database\Seeders\SSD;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SSDSeeder extends Seeder {
    public function run()
    {
        // Set koneksi database ssd pada tabel ssd
        DB::connection('ssd')->table('ssd')->truncate();

        $now = Carbon::now();
        $ssd = [
            // Kategori 1: Pelacakan dan Status Laporan
            [
                'ID_SSD' => 1,
                'ID_KATEGORI_SSD' => 1,
                'PERTANYAAN_SSD' => 'Bagaimana cara melacak status laporan saya?',
                'JAWABAN_SSD' => "Lacak status laporan dengan memasukkan nomor tiket Anda di tab 'Lacak Tiket'.",
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 2,
                'ID_KATEGORI_SSD' => 1,
                'PERTANYAAN_SSD' => 'Apa arti dari setiap status laporan?',
                'JAWABAN_SSD' => "Arti status: 'Open' (diterima), 'On Progress' (ditangani), 'Menunggu Konfirmasi' (tindakan dari pelapor), & 'Close' (kasus selesai), & 'Banding' (Ditindak secara lanjut). Info detail ada pada tiket.",
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 3,
                'ID_KATEGORI_SSD' => 1,
                'PERTANYAAN_SSD' => 'Apakah saya akan mendapat notifikasi saat status laporan berubah?',
                'JAWABAN_SSD' => 'Ya, notifikasi akan dikirim via Whatsapp setiap ada pembaruan status. Pastikan kontak Anda valid.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Kategori 2: Proses dan Waktu Penanganan
            [
                'ID_SSD' => 4,
                'ID_KATEGORI_SSD' => 2,
                'PERTANYAAN_SSD' => 'Berapa lama waktu yang dibutuhkan untuk menindaklanjuti laporan?',
                'JAWABAN_SSD' => 'Proses tindak lanjut biasanya dilakukan dalam 1-3 hari kerja tergantung tingkat prioritas dan kompleksitas laporan. Untuk kasus yang memerlukan investigasi lebih lanjut, mungkin memerlukan waktu lebih lama.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 5,
                'ID_KATEGORI_SSD' => 2,
                'PERTANYAAN_SSD' => 'Berapa lama waktu yang diberikan untuk mengkonfirmasi penyelesaian tiket?',
                'JAWABAN_SSD' => 'Tindak lanjut laporan biasanya 1-3 hari kerja, tergantung prioritas & kompleksitas. Kasus investigasi mungkin lebih lama.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 6,
                'ID_KATEGORI_SSD' => 2,
                'PERTANYAAN_SSD' => 'Apakah ada prioritas khusus untuk jenis laporan tertentu?',
                'JAWABAN_SSD' => 'Ya, laporan darurat seperti keselamatan pasien diprioritaskan. Namun, semua laporan ditangani secepatnya.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Kategori 3: Perubahan dan Tindak Lanjut Laporan
            [
                'ID_SSD' => 7,
                'ID_KATEGORI_SSD' => 3,
                'PERTANYAAN_SSD' => 'Apakah saya bisa mengubah laporan yang sudah dikirim?',
                'JAWABAN_SSD' => 'Hubungi admin atau buka kembali tiket untuk mengubah laporan jika belum diproses lebih lanjut.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 8,
                'ID_KATEGORI_SSD' => 3,
                'PERTANYAAN_SSD' => 'Bagaimana jika saya belum puas dengan penyelesaian masalah?',
                'JAWABAN_SSD' => "Jika tidak puas, Anda bisa membuka kembali tiket yang 'Selesai' dalam waktu yang ditentukan untuk memberi umpan balik.",
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 9,
                'ID_KATEGORI_SSD' => 3,
                'PERTANYAAN_SSD' => 'Bisakah saya menambahkan informasi tambahan setelah laporan dikirim?',
                'JAWABAN_SSD' => 'Ya, Anda bisa menambah info atau lampiran pada tiket yang sudah ada melalui halaman lacak tiket.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Kategori 4: Keamanan dan Privasi
            [
                'ID_SSD' => 10,
                'ID_KATEGORI_SSD' => 4,
                'PERTANYAAN_SSD' => 'Apakah data saya akan aman?',
                'JAWABAN_SSD' => 'Ya, data Anda aman sesuai kebijakan privasi kami dan hanya digunakan untuk menangani laporan.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 11,
                'ID_KATEGORI_SSD' => 4,
                'PERTANYAAN_SSD' => 'Siapa saja yang bisa melihat laporan saya?',
                'JAWABAN_SSD' => 'Laporan Anda hanya bisa diakses oleh Anda sebagai pelapor dan petugas berwenang yang menanganinya.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ID_SSD' => 12,
                'ID_KATEGORI_SSD' => 4,
                'PERTANYAAN_SSD' => 'Apakah laporan saya bisa dibuat anonim?',
                'JAWABAN_SSD' => 'Laporan anonim mungkin tersedia tergantung jenis laporan. Periksa opsi yang ada saat mengisi formulir.',
                'STATUS' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::connection('ssd')->table('ssd')->insert($ssd);
    }
}

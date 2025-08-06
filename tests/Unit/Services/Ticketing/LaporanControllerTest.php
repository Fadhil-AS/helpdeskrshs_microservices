<?php

namespace Tests\Unit\Services\Ticketing;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\Ticketing\Models\JenisMedia;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use App\Services\Ticketing\Models\Laporan;
use PHPUnit\Framework\Attributes\Test;

class LaporanControllerTest extends TestCase
{
    use WithoutMiddleware;

    private $klasifikasiBiasa;
    private $klasifikasiGratifikasi;
    private $jenisMediaWebsite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->klasifikasiBiasa = KlasifikasiPengaduan::factory()->create(['KLASIFIKASI_PENGADUAN' => 'Layanan']);
        $this->klasifikasiGratifikasi = KlasifikasiPengaduan::factory()->create(['KLASIFIKASI_PENGADUAN' => 'Gratifikasi']);
        $this->jenisMediaWebsite = JenisMedia::factory()->create(['JENIS_MEDIA' => 'Website Helpdesk']);

        Http::fake([ 'https://api.fonnte.com/*' => Http::response(['status' => true], 200) ]);
    }

    // #[Test]
    // public function get_buat_laporan_page_can_be_rendered(): void
    // {
    //     $this->get(route('ticketing.buat-laporan'))->assertStatus(200);
    // }

    #[Test]
    public function tempFileUploaded(): void
    {
        Storage::fake('local');
        $uploadId = Str::uuid()->toString();
        $file = UploadedFile::fake()->image('test_image.jpg');

        $this->postJson(route('ticketing.upload-file'), [
            'file' => $file,
            'upload_id' => $uploadId,
        ])->assertStatus(200)->assertJson(['success' => true]);

        Storage::disk('local')->assertExists('temp/' . $uploadId . '/' . $file->hashName());
    }

    #[Test]
    public function storeLaporanWithUploadedFiles(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $uploadId = Str::uuid()->toString();
        $fakeFile = UploadedFile::fake()->create('bukti_laporan.pdf', 100);
        $tempPath = 'temp/' . $uploadId . '/' . $fakeFile->hashName();
        Storage::disk('local')->put($tempPath, $fakeFile->getContent());

        $formData = [
            'jenis_pelapor' => 'Pasien',
            'NAME' => 'Budi Santoso',
            'NO_TLPN' => '081234567890',
            'ID_KLASIFIKASI' => $this->klasifikasiBiasa->ID_KLASIFIKASI,
            'ISI_COMPLAINT' => 'Ini adalah isi laporan test.',
            'upload_id' => $uploadId,
            'uploaded_files' => [$tempPath],
            'NO_MEDREC' => null,
            'ID_COMPLAINT_REFERENSI' => null,
        ];

        $response = $this->post(route('ticketing.store-laporan'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('data_complaint', ['NAME' => 'Budi Santoso']);
    }

    #[Test]
    public function storeGratifikasiLaporan(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $uploadId = Str::uuid()->toString();
        $fakeFile = UploadedFile::fake()->create('bukti_gratifikasi.pdf', 100);
        $tempPath = 'temp/' . $uploadId . '/' . $fakeFile->hashName();
        Storage::disk('local')->put($tempPath, $fakeFile->getContent());

        $formData = [
            'jenis_pelapor' => 'Non-Pasien',
            'NAME' => '',
            'NO_TLPN' => '',
            'ID_KLASIFIKASI' => $this->klasifikasiGratifikasi->ID_KLASIFIKASI,
            'ISI_COMPLAINT' => 'Laporan gratifikasi dari testing.',
            'upload_id' => $uploadId,
            'uploaded_files' => [$tempPath],
            'NO_MEDREC' => null,
            'ID_COMPLAINT_REFERENSI' => null,
        ];

        $response = $this->post(route('ticketing.store-laporan'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('data_complaint', ['NAME' => 'Anonimus']);
    }
}

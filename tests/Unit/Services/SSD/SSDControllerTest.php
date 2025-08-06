<?php

namespace Tests\Unit\Services\SSD;

use Tests\TestCase;

use App\Services\SSD\Models\KategoriSSD;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class SSDControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh', [
            '--database' => 'ssd',
            '--path' => 'database/migrations/ssd',
            '--realpath' => true,
        ]);
    }

    #[Test]
    public function getSSDWithCategories() : void
    {
        (new KategoriSSD())->setConnection('ssd');
        KategoriSSD::factory()->count(3)->create();

        $response = $this->get('/ssd');

        $response->assertStatus(200);
        $response->assertViewIs('Services.SSD.mainSSD');
        $response->assertViewHas('semuaKategori');

        $kategoriDariView = $response->viewData('semuaKategori');
        $this->assertCount(3, $kategoriDariView);
    }
}

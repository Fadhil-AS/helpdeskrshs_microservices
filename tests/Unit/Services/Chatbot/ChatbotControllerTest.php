<?php

namespace Tests\Unit\Services\Chatbot;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use App\Services\Chatbot\Models\Chatbot;
use PHPUnit\Framework\Attributes\Test;

class ChatbotControllerTest extends TestCase
{
    use WithoutMiddleware;

    protected $connectionsToTransact = [];

    #[Test]
    public function chat_view_can_be_rendered(): void
    {
        $this->get('/chat')->assertStatus(200)->assertViewIs('Services.Chatbot.mainChatbot');
    }

    #[Test]
    public function sendAndReplyChatbot(): void
    {
        Http::fake(['http://localhost:5678/webhook/chatbot-laravel' => Http::response(['reply' => 'Ini adalah balasan dari rshs.'], 200)]);
        $this->postJson('/chatbot', ['message' => 'Test'])->assertStatus(200)->assertJson(['reply' => 'Ini adalah balasan dari rshs.']);
    }

    public function can_upload_a_valid_excel_file_successfully(): void
    {
        $this->withoutFollowingRedirects();

        Http::fake([
            'http://localhost:5678/webhook/upload-data' => Http::response(['status' => 'success'], 200),
        ]);

        $file = UploadedFile::fake()->create('laporan_bulanan.xlsx', 1024);

        $response = $this->post('/upload', [
            'file' => $file,
        ]);

        $response->assertRedirect('/upload');
        $response->assertSessionHas('status', 'File berhasil diunggah!');

        Http::assertSent(fn ($request) => $request->hasFile('file'));
    }


    #[Test]
    public function upload_data_fails_validation_for_wrong_file_type(): void
    {
        $file = UploadedFile::fake()->create('dokumen_salah.txt', 1024);
        $this->post('/upload', ['file' => $file])->assertRedirect()->assertSessionHasErrors('file');
    }

    #[Test]
    public function can_delete_a_file_record(): void
    {
        $fileToDelete = Chatbot::factory()->create();
        $this->assertDatabaseHas('data_chatbot', ['id' => $fileToDelete->id], 'chatbot');
        $this->delete(route('delete.file', $fileToDelete->id))->assertRedirect('/upload');
        $this->assertDatabaseMissing('data_chatbot', ['id' => $fileToDelete->id], 'chatbot');
    }

    #[Test]
    public function tes_untuk_melihat_data_di_database(): void
    {
        Chatbot::factory()->count(5)->create();
        $this->assertTrue(true);
    }
}

<?php

namespace App\Services\Chatbot\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Chatbot\Models\Chatbot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller {
    public function handleChat(Request $request)
    {
        $userMessage = $request->input('message');

        $response = Http::post('http://localhost:5678/webhook/chatbot-laravel', [
            'message' => $userMessage,
        ]);

        return response()->json([
            'reply' => $response->json()['reply'] ?? 'Tidak ada respons dari chatbot.',
        ]);
    }

    public function uploadData(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        $file = $request->file('file');

        $response = Http::attach(
            'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
        )->post('http://localhost:5678/webhook/upload-data');

        return redirect('/upload')->with('status', 'File berhasil diunggah!');
    }
}

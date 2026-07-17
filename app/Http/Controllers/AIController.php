<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    /**
     * Generate professional engineer description using Groq API
     */
    public function generateProfessionalDescription(Request $request)
    {
        // Validasi input
        $request->validate([
            'description' => 'required|string'
        ]);

        $text = $request->description;

       $prompt = "Ubah kalimat berikut menjadi deskripsi pekerjaan yang profesional, formal, dan jelas dalam bahasa Indonesia.

ATURAN:
- Jangan gunakan kata 'saya'
- Jangan gunakan frasa 'sebagai engineer'
- Jangan gunakan sudut pandang orang pertama
- Langsung fokus pada aktivitas pekerjaan
- Gunakan gaya bahasa profesional perusahaan
- Hanya 1 kalimat
- Jangan membuat daftar atau bullet point
- Maksimal 1-2 kalimat

Kalimat:
{$text}";

        // Ambil API Key dari config/services.php
        $apiKey = trim(config('services.groq.key'));

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API Key Groq tidak ditemukan.'
            ], 500);
        }

        try {

            // Endpoint resmi Groq
            $url = 'https://api.groq.com/openai/v1/chat/completions';

            // Request ke Groq
            $response = Http::withHeaders([
    'Authorization' => "Bearer {$apiKey}",
    'Content-Type' => 'application/json',
])->post($url, [
    'model' => 'llama-3.3-70b-versatile',
    'messages' => [
        [
            'role' => 'user',
            'content' => $prompt
        ]
    ],
    'temperature' => 0.7,
    'max_tokens' => 200
]);

            // Jika gagal
            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal dari Groq API',
                    'error' => $response->body()
                ], 500);
            }

            // Ambil hasil response
            $result = $response->json();

            $generatedText = $result['choices'][0]['message']['content'] ?? null;

            if (!$generatedText) {
                return response()->json([
                    'success' => false,
                    'message' => 'Response AI kosong.'
                ], 500);
            }

            // Success
            return response()->json([
                'success' => true,
                'data' => trim($generatedText)
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
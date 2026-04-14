<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    /**
     * Memperbaiki deskripsi task menjadi lebih profesional menggunakan Google Gemini API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateProfessionalDescription(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'description' => 'required|string'
        ]);

        $text = $request->description;
        
        // 2. Persiapkan prompt untuk AI dengan aturan ketat
        $prompt = "Ubah kalimat berikut menjadi 1 deskripsi pekerjaan engineer yang profesional, formal, dan jelas dalam bahasa Indonesia.

ATURAN:
- Hanya berikan 1 kalimat saja
- Jangan buat daftar atau opsi
- Jangan gunakan bullet point
- Maksimal 1-2 kalimat
- Fokus pada aktivitas pekerjaan

Kalimat:
" . $text;

        // 3. Ambil API Key dari config
        $apiKey = trim(config('services.gemini.key'));
        
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'API Key Gemini (GEMINI_API_KEY) tidak ditemukan.'
            ], 500);
        }

        try {
            // 4. Request ke API Gemini menggunakan model 2.5-flash sesuai data user
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            // 5. Tangani respons dari API
            if ($response->successful()) {
                $data = $response->json();
                
                // Parsing response dari Gemini
                $generatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($generatedText) {
                    return response()->json([
                        'success' => true,
                        'data' => trim($generatedText) 
                    ]);
                } else {
                     return response()->json([
                        'success' => false,
                        'message' => 'Format respons dari Gemini tidak sesuai atau kosong.'
                    ], 500);
                }
            }

            // Jika API merespons dengan HTTP error (Gunakan 500 agar tidak rancu dengan route 404)
            return response()->json([
                'success' => false,
                'message' => 'Gagal dari Gemini API: ' . $response->body()
            ], 500);

        } catch (\Exception $e) {
            // 6. Penanganan error catch-all
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}

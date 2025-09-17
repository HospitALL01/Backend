<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'array'
        ]);

        $apiKey  = env('GEMINI_API_KEY');
        $model   = env('GEMINI_MODEL', 'gemini-1.5-flash');
        $apiBase = rtrim(env('GEMINI_API_BASE', 'https://generativelanguage.googleapis.com/v1beta'), '/');

        if (!$apiKey) {
            return response()->json(['error' => 'GEMINI_API_KEY not set'], 500);
        }

        // Build contents
        $contents = [];
        foreach ($request->input('history', []) as $turn) {
            $text = $turn['text'] ?? '';
            if ($text !== '') {
                $role = ($turn['role'] ?? 'user') === 'model' ? 'model' : 'user';
                $contents[] = [
                    'role'  => $role,
                    'parts' => [['text' => $text]],
                ];
            }
        }
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $request->input('message')]],
        ];

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature'      => 0.7,
                'topP'             => 0.95,
                'topK'             => 40,
                'maxOutputTokens'  => 1024,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HATE_SPEECH',      'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
            ],
        ];

        $endpoint = "{$apiBase}/models/{$model}:generateContent?key={$apiKey}";

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => 'Curl error', 'details' => $err], 500);
        }
        curl_close($ch);

        $json = json_decode($response, true);

        if ($status >= 400) {
            return response()->json([
                'error'  => 'Gemini API error',
                'status' => $status,
                'details'=> $json
            ], $status);
        }

        $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!$text) {
            return response()->json(['error' => 'No text in Gemini response', 'raw' => $json], 500);
        }

        return response()->json(['reply' => $text]);
    }
}

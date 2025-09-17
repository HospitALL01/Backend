<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected $apiKey;
    protected $endpoint;
    protected $model;

    public function __construct()
    {
        // Load Gemini API Key and endpoint from config.
        $this->apiKey = config('gemini.api_key');
        $this->endpoint = config('gemini.endpoint');
        $this->model = config('gemini.model');
    }

    /**
     * Generate a response from the Gemini API.
     * 
     * @param array $history Conversation history
     * @return array Response from Gemini API
     */
   public function generateResponse(array $history)
{
    try {
        // Gemini API কল
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])
        ->post("{$this->endpoint}/chat", [
            'model' => $this->model,
            'messages' => $history,
            'temperature' => 0.7,
            'max_tokens' => 150,
        ]);

        // চেক করো API রেসপন্স সফল কিনা
        if ($response->failed()) {
            Log::error("Gemini API error: ", ['response' => $response->body()]);
            return ['error' => 'Gemini API call failed'];
        }

        return $response->json();
    } catch (\Exception $e) {
        Log::error("Gemini API exception: ", ['error' => $e->getMessage()]);
        return ['error' => 'Internal Server Error'];
    }
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextGenerator extends Model
{
    use HasFactory;

    const TEXT_GENERATION_ENDPOINT = '/v1/chat/completions';

    protected $table = 'generations';
    protected $fillable = ['fixture_id', 'generation', 'status', 'created_at', 'updated_at'];

    /**
     * @param array $payload
     * @return false|mixed
     */
    public function generateText(array $payload): mixed
    {
        $chatGPTResponse = false;

        try {
            $response = Http::timeout(120)->withToken(env('CHATGPT_API_KEY'))
                ->post(env('CHATGPT_API_HOST') . self::TEXT_GENERATION_ENDPOINT, $payload);

            Log::debug("CHATGPT Text Generation: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['choices'][0]['message']['content'])) {
                    $chatGPTResponse = $data['choices'][0]['message']['content'];
                }
            } else {
                Log::error("[" . $payload['fixture_id'] . "] CHATGPT Text Generation: " . $response->status() . " >>> Message: " . $response->body());
            }
        } catch (\Throwable $th) {
            Log::error("[" . $payload['fixture_id'] . "] " . $th->getMessage() . " >>> Error: " . isset($response['error']['message']) ? $response['error']['message'] : $response->status());
        }

        return $chatGPTResponse;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool
    {
        // Before creating a new generation, try to find an existing one
        $generations = TextGenerator::all();
        $generation = $generations->firstWhere('fixture_id', $data['fixture_id']);

        if (empty($fixture)) {
            $generation = new TextGenerator();
        }

        foreach ($data as $column => $value) {
            $generation->$column = $value;
        }

        return $generation->save();
    }
}

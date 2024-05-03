<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextGeneratorModel extends Model
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
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
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
        $generations = TextGeneratorModel::all();
        $generation = $generations->firstWhere('fixture_id', $data['fixture_id']);

        if (empty($fixture)) {
            $generation = new TextGeneratorModel();
        }

        foreach ($data as $column => $value) {
            $generation->$column = $value;
        }

        return $generation->save();
    }
}

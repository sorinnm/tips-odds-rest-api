<?php

namespace App\Http\Controllers;

use App\Models\Fixtures;
use App\Models\Standings;
use App\Models\TextGenerator;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TextGenerationController extends Controller
{
    public function __construct(
        protected TextGenerator $generatorModel,
        protected Fixtures      $fixtures,
        protected Standings     $standings
    ){}

    public function index(Request $request): JsonResponse
    {


        foreach ($fixtures as $fixture) {


            $chatGPTResponse = $this->generatorModel->generateText($payload, $fixture->fixture_id);

            if (!empty($chatGPTResponse)) {
                $saved = $this->generatorModel->store([
                    'fixture_id' => $fixture->fixture_id,
                    'generation' => json_encode($chatGPTResponse)
                ]);

                if ($saved) {
                    $data['generations'][] = $fixture->fixture_id;
                    $data['generations']['success']++;

                    // Set fixture to complete if generation has been saved
                    $this->fixtures->store([
                        'fixture_id' => $fixture->fixture_id,
                        'status' => 'complete'
                    ]);
                }
            } else {
                Log::error("CHAT GPT generation failed...");
                $data['generations'][$fixture->fixture_id][] = "Invalid response from ChatGPT. Check logs for additional details";
                $data['generations']['error']++;
            }
        }


        return response()->json($data);
    }
}

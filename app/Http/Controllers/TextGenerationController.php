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
        // Retrieve the AI model to be used with ChatGPT and the user-defined prompt messages
        $messages = $request->get('messages');
        $payload = [
            'model' => $request->get('model')
        ];

        $data = ['generations' => ['success' => 0, 'error' => 0]];

        // Add to messages the JSON contents for ChatGPT processing
        $standings = $this->standings->retrieveStandings(
            $request->get('league'),
            $request->get('season'),
            $request->get('round')
        );

        $fixtures = $this->fixtures->getAll(
            $request->get('league'),
            $request->get('season'),
            $request->get('round')
        );

        foreach ($fixtures as $fixture) {
            $messages[] = ['role' => 'user', 'content' => Json::encode($fixture->fixtures)];
            $messages[] = ['role' => 'user', 'content' => Json::encode($standings->standings)];
            $messages[] = ['role' => 'user', 'content' => Json::encode($fixture->home_team_squad)];
            $messages[] = ['role' => 'user', 'content' => Json::encode($fixture->away_team_squad)];
            $messages[] = ['role' => 'user', 'content' => Json::encode($fixture->injuries)];
            $messages[] = ['role' => 'user', 'content' => Json::encode($fixture->predictions)];
            $messages[] = ['role' => 'user', 'content' => Json::encode($fixture->head_to_head)];
            $messages[] = ['role' => 'user', 'content' => Json::encode($fixture->bets)];

            $payload['messages'] = $messages;

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

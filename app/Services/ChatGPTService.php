<?php

namespace App\Services;

use App\Models\Fixtures;
use App\Models\Standings;
use App\Models\TextGenerator;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class ChatGPTService
{

    /**
     * @param TextGenerator $generatorModel
     * @param Fixtures $fixtures
     * @param Standings $standings
     */
    public function __construct(
        protected TextGenerator $generatorModel,
        protected Fixtures      $fixtures,
        protected Standings     $standings
    ){}

    const TEXT_GENERATION_ENDPOINT = '/v1/chat/completions';

    /**
     * @param Fixtures $fixture
     * @return false|mixed|null
     * @throws \Exception
     */
    public function generateText(Fixtures $fixture): mixed
    {
        // Retrieve the AI model to be used with ChatGPT and the user-defined prompt messages
        $messages = [
            ["role" => "system", "content" => "You are a sport analyst."],
            ["role" => "user", "content" => "I will provide 8 JSON format objects"],
            ["role" => "user", "content" => "taking into consideration the information provided, I want you to create an article regarding tips, odds and prediction for the sport event. Structure of the article will be:"],
            ["role" => "user", "content" => "First paragraph: make a 300 words match preview.
            Make the title as first team vs second team match preview. Use long-tail keywords when possible, like first team vs second team match, first team vs second team preview, first team vs second team prediction, first team second team tips, first team second team bets.
            Don't use the exact words \"first team\" or \"second team\", replace them with the actual name of the teams. Don't use double quotes for the keywords, just integrate them naturally into text.
            Mention the current standings and recent form for each team.
            Second paragraph: Using the provided information, create a paragraph of approximately 50 words with the top prediction for this match.
            We will use this paragraph for google search engine optimization, for getting the featured snippet. Below, create an article of approximately 150 words highlighting the top 3 predictions for the match.
            Take into consideration using keywords for better SEO. Each paragraph of the article should have first the prediction then why this is the probable result.
            Use as often as possible long tail keywords like first team vs second team prediction, first team second team bets, etc in both snippet and article.
            Write it like a bettor would, without mentioning terms like seo and keywords.
            Name the snippet using the name of the first team vs the name of the second team Betting Predictions.
            Name the article using the name of the first team vs the name of the second team Top Predictions for betting.
            The third paragraph : make a 100 words essay regarding last 5 head to head between the two teams.
            Specify the dates and the result of each match, as \"Team 1 vs Team 2  (or team 2 vs team 1) - Score - Date\".
            Use long-tail keywords when possible, like first team vs second team match, first team vs second team preview, first team vs second team prediction, first team second team tips, first team second team bets.
            Don't use the exact words \"first team\" or \"second team\", replace them with the actual name of the teams. Don't use double quotes for the keywords, just integrate them naturally into text.
            The forth paragraph : make a 100 words essay regarding the squads, presenting the absents ( injuries and suspended ).
            Use long-tail keywords when possible, like first team vs second team match, first team vs second team preview, first team vs second team prediction, first team second team tips, first team second team bets.
            Don't use the exact words \"first team\" or \"second team\", replace them with the actual name of the teams. Don't use double quotes for the keywords, just integrate them naturally into text.
            Print the result as JSON following this precise nested structure: first_paragraph as key with title, content as children, second_paragraph as key with title, content and top_3_predictions as children where top_3_predictions has 'prediction' and 'reason' as two children, third_paragraph as key with title, content and head_to_head as children where head_to_head has 'match' as child (example: 'Boca Juniors vs Atletico Tucuman - 1-0 - Jan 30, 2023') and the forth_paragraph as key with title, content as children. This object structure is very strict in terms of keys defined."]
        ];

        // Add to messages the JSON contents for ChatGPT processing
        $standings = $this->standings->retrieveStandings(
            $fixture->league_id,
            $fixture->season_id,
            $fixture->round
        );

        if (!empty($fixture->fixtures)) {
            $messages[] = ['role' => 'user', 'content' => $fixture->fixtures];
        }

        if (!empty($standings->standings)) {
            $messages[] = ['role' => 'user', 'content' => $standings->standings];
        }

        if (!empty($fixture->home_team_squad)) {
            $messages[] = ['role' => 'user', 'content' => $fixture->home_team_squad];
        }

        if (!empty($fixture->away_team_squad)) {
            $messages[] = ['role' => 'user', 'content' => $fixture->away_team_squad];
        }

        if (!empty($fixture->injuries)) {
            $messages[] = ['role' => 'user', 'content' => $fixture->injuries];
        }

        if (!empty($fixture->predictions)) {
            $messages[] = ['role' => 'user', 'content' => $fixture->predictions];
        }

        if (!empty($fixture->head_to_head)) {
            $messages[] = ['role' => 'user', 'content' => $fixture->head_to_head];
        }

        if (!empty($fixture->bets)) {
            $messages[] = ['role' => 'user', 'content' => $fixture->bets];
        }

        $payload = [
            'model' => env('CHATGPT_API_MODEL'),
            'messages' => $messages
        ];

        Log::channel('chatgpt')->debug("CHATGPT Payload: " . Json::encode($payload));

        return $this->callAPI(
            Request::METHOD_POST,
            env('CHATGPT_API_HOST') . self::TEXT_GENERATION_ENDPOINT,
            $payload
        );
    }


    /**
     * @param $httpMethod
     * @param string $endpoint
     * @param array $payload
     * @return false|mixed|void
     * @throws \Exception
     */
    public function callAPI($httpMethod, string $endpoint, array $payload)
    {
        $response = Http::timeout(120)->withToken(env('CHATGPT_API_KEY'))
            ->withHeader('Accept', 'application/json')
            ->$httpMethod($endpoint, $payload);

        Log::channel('chatgpt')->debug($endpoint. " >>> Status code: " . $response->status() . " >>> Response: " . Json::encode($response->json()));


        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data) && isset($data['choices'][0]['message']['content'])) {
                $result = $data['choices'][0]['message']['content'];
            } else {
                throw new \Exception("Invalid CHATGPT response: " . $response->status() . " >>> Message: " . $response->body());
            }
        } else {
           throw new \Exception("CHATGPT Text Generation: " . $response->status() . " >>> Message: " . $response->body());
        }

        return $result;

    }
}

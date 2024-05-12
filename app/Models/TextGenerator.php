<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextGenerator extends Model
{
    use HasFactory;

    protected $table = 'generations';
    protected $fillable = ['fixture_id', 'generation', 'status', 'created_at', 'updated_at'];

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

    /**
     * @return string[]
     */
    public function getGeneration()
    {
        $generation = TextGenerator::all()->firstWhere('status', 'pending');
        $text = trim($generation->generation, '\"');
        $text = $generation->generation;
//        dd($text);
//        $text = "**Real Betis vs Sevilla Match Preview**  \nThe stage is set for an intense La Liga showdown as Real Betis prepares to lock horns with Sevilla at the iconic Estadio Benito Villamar\u00edn. This encounter is more than just a regular match; it's a deep-rooted Andalusian derby known for its electrifying atmosphere and historic rivalry, capturing the essence of Spanish football at its peak.\n\nReal Betis, currently positioned near the mid-table, has displayed a series of inconsistent performances this season. Their journey in La Liga reflects a battle for stability, marked by unexpected highs and disappointing lows. As they gear up for this home game, the lack of consistency in their match outcomes—a blend of wins, draws, and losses—highlights the unpredictability they bring to the pitch. Offensively, Betis has found the back of the net with a moderate frequency but has struggled significantly on the defensive end, often leaving them vulnerable, especially in the dying minutes of the game.\n\nOn the flip side, Sevilla travels to this match with a slightly better momentum, showing hints of revival under their current tactical setup. Despite a less than stellar start to the season, their recent matches suggest a potential turnaround, with defensive tactics significantly bolstered and offensive strategies gradually improving. Sevilla's approach to away games has been cautiously optimistic, focusing on maintaining structure and exploiting counter-attacking opportunities.\n\nThis encounter is poised to be a tactical chess match between two sides desperate to climb higher up the league standings. With both teams experiencing variable forms and the added pressure of a derby, goals are expected, but the resilience in defense will be the determining factor. Betting enthusiasts and supporters alike are bracing for a match filled with passion, intensity, and hopefully, exhilarating football. [tipsOdds]\n\n**Real Betis vs Sevilla Top Prediction**  \nThe fierce rivalry continues with Betis hosting Sevilla in a match that promises drama and action. Here's a quick prediction to gear up fans and bettors for this much-anticipated clash.\n\nBetis tends to come alive in front of their home crowd, adding a layer of intensity to their game, whereas Sevilla's tactical discipline on the road can lead to a tight contest. Expect a draw or a narrow win for Sevilla as they look to capitalize on Betis's defensive vulnerabilities. [tipsOdds]\nReal Betis vs Sevilla predicted outcomes:\n- Sevilla wins (Odd 3.85) \u2013 Sevilla\u2019s improving form could see them edge out a narrow victory.\n- Both teams to score (Odd 1.83) \u2013 Given the offensive capabilities on both sides, it\u2019s likely that both teams will find the back of the net.\n- Final score: Betis 1-2 Sevilla (Odd available on request) \u2013 A close match with Sevilla just edging it by the full-time whistle. [tipsOdds]\n\n**Real Betis vs Sevilla Recent Results**  \nThe history between Real Betis and Sevilla is filled with intense matchups characterized by fervent competition and memorable moments. Here is a glimpse into their recent encounters:\nBetis vs Sevilla (or Sevilla vs Betis) - Score - Date:\n- Sevilla 1 vs 1 Betis - 11 Nov 2023 \n- Sevilla 1 vs 0 Betis - 3 Aug 2023 (Club Friendly)\n- Sevilla 0 vs 0 Betis - 21 May 2023 \n- Betis 1 vs 1 Sevilla - 6 Nov 2022\n- Sevilla 2 vs 1 Betis - 27 Feb 2022 [tipsOdds]\n\n**Real Betis vs Sevilla Probable Starting Lineups**  \nGiven the injuries and the current form of players, here are the predicted starters for each team:\nFor Real Betis, despite the injuries to key players like C. Avila and M. Bartra, the lineup is expected to be resilient. Rui Silva might start as the goalkeeper, providing reliability at the back. In defense, despite the absence of H. Bellerin, players like Juan Miranda and Victor Ruiz might be tasked with the responsibility of curtailing Sevilla's attacks. Guido Rodriguez and Sergio Canales could control the midfield, trying to orchestrate offensive plays.\n\nSevilla has its share of absences including M. Dmitrovic and N. Gudelj and could rely on Bono to start in goal. The defense might see the experienced Jesus Navas and Diego Carlos anchoring the backline. Up front, Lucas Ocampos could be crucial in breaking through the Betis defense, with support from midfielders like Ivan Rakitic and Joan Jordan.\n\nThe predicted lineup choices reflect each team's strategic considerations and injury impacts, setting the stage for a riveting clash in La Liga. [tipsOdds]";

//        $paragraphs = explode('[tipsOdds]', trim($generation->generation, '\"'));
//
//        $output = [];
//        $data = [];
//        foreach ($paragraphs as $key => $paragraph) {
//            if (!empty($paragraph)) {
//                $output[$key] = explode('\n', $paragraph);
//
//                for ($i = 0; $i <= count($output[$key]); $i++) {
//                    if (!empty($output[$key][$i])) {
//                        if (str_starts_with($output[$key][$i], '**')
//                            && (str_contains($output[$key][$i], 'Match Preview')
//                                || str_contains($output[$key][$i], 'Top Prediction')
//                                || str_contains($output[$key][$i], 'Recent Results')
//                                || str_contains($output[$key][$i], 'Probable Starting Lineups'))) {
//
//                            $data[$key]['main_title'][] = str_replace("**", '', $output[$key][$i]);
//                        } else {
//                            if (!isset($data[$key]['main_title'])) {
//                                $data[$key-1]['content'][] = $output[$key][$i];
//                            } else {
//                                $data[$key]['content'][] = $output[$key][$i];
//                            }
//                        }
//                    }
//                }
//            }
//        }

        // Regular expression to capture titles and content between ** and [tipsOdds]
        $pattern = '/\*\*(.*?)\*\*\s+(.*?)(?=\[tipsOdds\]|$)/s';

        // Execute the regular expression to extract titles and content
        preg_match_all($pattern, $text, $matches);

        // Initialize an associative array to hold the parsed content
        $viewData = [
            'match_preview_title' => '',
            'match_preview_content' => '',
            'top_predictions_title' => '',
            'top_predictions_content' => '',
            'recent_results_title' => '',
            'recent_result_content' => '',
            'starting_lineup_title' => '',
            'starting_lineup_content' => '',
        ];

        // Assign extracted titles and content to the respective keys in $viewData
        if (isset($matches[1]) && isset($matches[2])) {
            foreach ($matches[1] as $index => $title) {
                $content = trim($matches[2][$index]);
                switch ($title) {
                    case str_contains($title, 'Match Preview'):
                        $viewData['match_preview_title'] = $title;
                        $viewData['match_preview_content'] = $content;
                        break;
                    case str_contains($title, 'Top Prediction'):
                        $viewData['top_predictions_title'] = $title;
                        $viewData['top_predictions_content'] = $content;
                        break;
                    case str_contains($title, 'Recent Results'):
                        $viewData['recent_results_title'] = $title;
                        $viewData['recent_result_content'] = $content;
                        break;
                    case str_contains($title, 'Probable Starting Lineups'):
                        $viewData['starting_lineup_title'] = $title;
                        $viewData['starting_lineup_content'] = $content;
                        break;
                }
            }
        }

        return $viewData;
    }
}

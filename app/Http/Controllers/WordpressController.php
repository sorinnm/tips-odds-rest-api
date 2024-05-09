<?php

namespace App\Http\Controllers;

use App\Models\TextGenerator;
use Illuminate\Http\Request;

class WordpressController extends Controller
{
    public function __construct(
        protected TextGenerator $textGenerator
    ) {}

    public function post(Request $request)
    {
        $generations = $this->textGenerator->getGeneration();

//        $paragraph = [];
//        $k = 0;
//        foreach ($generations as $generation) {
//            $paragraph[$k]['main_title'] = $generation['main_title'];
//            $paragraph[$k]['content'] = $generation['content'];
//            $k++;
//        }

//        $viewData['main_title'] = trim(str_replace(' Match Preview', '', $paragraph[0]['main_title'][0]));
//        $viewData['match_preview_title'] = trim($paragraph[0]['main_title'][0]);
//        $viewData['top_predictions_title'] = trim($paragraph[1]['main_title'][0]);
//        $viewData['recent_results_title'] = trim($paragraph[2]['main_title'][0]);
//        $viewData['starting_lineup_title'] = trim($paragraph[3]['main_title'][0]);
//        $viewData['match_preview_content'] = implode($paragraph[0]['content']);
//        $viewData['top_predictions_content'] = implode($paragraph[1]['content']);
//        $viewData['recent_result_content'] = implode($paragraph[2]['content']);
//        $viewData['starting_lineup_content'] = implode($paragraph[3]['content']);
//        list($viewData['top_predictions_content'], $viewData['top_predictions']) = explode(':', $viewData['top_predictions_content'], 2);
//        $viewData['top_predictions'] = explode(';', $viewData['top_predictions']);

//        foreach ($viewData['top_predictions'] as $top_prediction) {
//            $viewData['predictions'][] = explode('\\', $top_prediction);
//        }

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

        $html = view('wordpress_post', $generations)->render();
        return response()->json($html);
    }
}

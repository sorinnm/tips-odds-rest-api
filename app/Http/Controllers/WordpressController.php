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

        // TODO for postman

        $html = view('wordpress_post', $generations)->render();
        return response()->json($html);
    }
}

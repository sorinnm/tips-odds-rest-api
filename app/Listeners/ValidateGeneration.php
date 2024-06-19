<?php

namespace App\Listeners;

use App\Events\FixtureStatusUpdate;
use App\Events\GenerationCheck;
use App\Models\TextGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;

class ValidateGeneration
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GenerationCheck $event): void
    {
        $fixture = $event->fixture;
        $generation = TextGenerator::all()->where('fixture_id', $fixture->fixture_id)->first();

        $content = trim($generation->generation, "```json\n");

        // Define the validation rules
        $rules = [
            'first_paragraph.title' => 'required|string',
            'first_paragraph.content' => 'required|string',
            'second_paragraph.title' => 'required|string',
            'second_paragraph.content' => 'required|string',
            'second_paragraph.top_3_predictions' => 'required|array|size:3',
            'second_paragraph.top_3_predictions.*.prediction' => 'required|string',
            'second_paragraph.top_3_predictions.*.reason' => 'required|string',
            'third_paragraph.title' => 'required|string',
            'third_paragraph.content' => 'required|string',
            'third_paragraph.head_to_head' => 'required|array|between:1,5',
            'third_paragraph.head_to_head.*.match' => 'required|string',
            'forth_paragraph.title' => 'required|string',
            'forth_paragraph.content' => 'required|string',
        ];

        // Validate the request data against the rules
        $validator = Validator::make(json_decode($content, true), $rules);

        if ($validator->fails()) {
            // Validation failed
            $errors = $validator->errors();
            $event->fixture->step_error_message = json_encode($errors, JSON_PRETTY_PRINT);
            $event->fixture->save();
            FixtureStatusUpdate::dispatch($event->fixture, 'GenerationContentCheck', 7);
        } else {
            // Validation passed
            FixtureStatusUpdate::dispatch($event->fixture, 'GenerationContentCheck', 8);
        }
    }
}

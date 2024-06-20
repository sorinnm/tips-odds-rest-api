<?php

namespace App\Listeners;

use App\Events\FixtureGeneration;
use App\Events\FixtureStatusUpdate;
use App\Services\ChatGPTService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateContent
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public ChatGPTService $chatGPTService
    ){}

    /**
     * Handle the event.
     */
    public function handle(FixtureGeneration $event): void
    {
        $fixture = $event->fixture;
        try {
            $this->chatGPTService->generateFixtureData($fixture);
        } catch (\Exception $e) {
            FixtureStatusUpdate::dispatch($fixture, 'ChatGPTGeneration', 5);
            $fixture->step_error_message = $e->getMessage();
            $fixture->save();
        }
    }
}

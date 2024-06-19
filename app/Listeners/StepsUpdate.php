<?php

namespace App\Listeners;

use App\Events\FixtureStatusUpdate;
use App\Models\Fixtures;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class StepsUpdate
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
    public function handle(FixtureStatusUpdate $event): void
    {
        $fixture = $event->fixture;
        $type = $event->type;
        $nextStep = $event->step;

        $previousStep = $fixture->step;
        $steps = Fixtures::PROCESS_STEPS;

        $fixture->step = $nextStep;
        $fixture->save();

        if ($previousStep != $nextStep) {
            Log::channel('api-football')->info("$type: #{$fixture->fixture_id} updated from step '$steps[$previousStep] => $steps[$nextStep]'");
        }
    }
}

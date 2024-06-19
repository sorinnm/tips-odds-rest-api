<?php

namespace App\Listeners;

use App\Events\DataIntegrityCheck;
use App\Models\Fixtures;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateDataIntegrityCheck
{
    /**
     * Create the event listener.
     */
    public function __construct(){}

    /**
     * Handle the event.
     */
    public function handle(DataIntegrityCheck $event): void
    {
        $fixture = $event->fixture;
        $apiFootballErrors = $event->apiErrors;

        if (empty($apiFootballErrors)) {
            $step = 4;
        } else {
            $step = (count($apiFootballErrors) > 2) ? 2 : 3;
        }

        $fixture->step = $step;
        $fixture->save();
    }
}

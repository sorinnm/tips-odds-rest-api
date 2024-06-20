<?php

namespace App\Listeners;

use App\Events\FixturePublish;
use App\Events\FixtureStatusUpdate;
use App\Services\WordpressService;
use Exception;

class PublishFixture
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public WordpressService $wordpressService
    ){}

    /**
     * Handle the event.
     */
    public function handle(FixturePublish $event): void
    {
        $fixture = $event->fixture;

        try {
            $this->wordpressService->processGeneration($fixture);
        } catch (Exception $e) {
            FixtureStatusUpdate::dispatch($fixture, 'FixturePublished', 11);
            $fixture->step_error_message = $e->getMessage();
            $fixture->save();
        }
    }
}

<?php

namespace App\Listeners;

use App\Events\FixturePublish;
use App\Events\FixtureStatusUpdate;
use App\Services\WordpressService;
use Exception;
use Illuminate\Support\Facades\Session;

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
            $result = $this->wordpressService->processGeneration($fixture);
            Session::flash('message', "Successfully published fixture ID #{$result['report']['ID']} - {$result['report']['Match']}!");
        } catch (Exception $e) {
            FixtureStatusUpdate::dispatch($fixture, 'FixturePublished', 11);
            $fixture->step_error_message = $e->getMessage();
        }
    }
}

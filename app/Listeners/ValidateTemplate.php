<?php

namespace App\Listeners;

use App\Events\FixtureStatusUpdate;
use App\Events\TemplateValidation;
use App\Models\TextGenerator;
use App\Services\WordpressService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Queue\InteractsWithQueue;

class ValidateTemplate
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public WordpressService $wordpressService,
        public TextGenerator $textGenerator
    ) {}

    /**
     * Handle the event.
     */
    public function handle(TemplateValidation $event): void
    {
        $fixture = $event->fixture;
        $errors = [];

        try {
            $generation = $this->textGenerator->getGeneration($fixture->fixture_id);

            if (empty($generation)) {
                throw new \Exception("No generation found");
            }

            $generation = Json::decode($generation, true);
            $fixtureData = json_decode($fixture->fixtures, true);

            if (!empty($fixtureData)) {
                $matchDate = date('F j Y', $fixtureData[0]['fixture']['timestamp']);
                $generation['matchDate'] = $matchDate;
            }

            $html = $this->wordpressService->generateHtml($generation, WordpressService::EXPORT_TYPE_PAGE);
            FixtureStatusUpdate::dispatch($event->fixture, 'TemplateValidationCheck', 10);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (!empty($errors)) {
            $fixture->step_error_message = $errors;
            $fixture->save();
            FixtureStatusUpdate::dispatch($event->fixture, 'TemplateValidationCheck', 9);
        }
    }
}

<?php

namespace App\Livewire;

use App\Events\FixtureGeneration;
use App\Events\FixturePublish;
use App\Events\FixtureStatusUpdate;
use App\Events\GenerationCheck;
use App\Events\TemplateValidation;
use App\Listeners\PublishFixture;
use App\Models\Fixtures;
use App\Models\TextGenerator;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\Attributes\On;

class Status extends Component
{
    public Fixtures $fixture;

    public function generate()
    {
        FixtureGeneration::dispatch($this->fixture);
    }

    public function regenerate()
    {
        if ($this->fixture->status == Fixtures::STATUS_COMPLETE) {
            $this->fixture->status = Fixtures::STATUS_PENDING;
            $this->fixture->save();
        }

        FixtureGeneration::dispatch($this->fixture);
    }

    public function dataIntegrityCheckAck()
    {
        if ($this->fixture->generation && $this->fixture->generation->status == TextGenerator::STATUS_COMPLETE) {
            FixtureStatusUpdate::dispatch($this->fixture, 'DataIntegrityCheck', 6);
        } else {
            FixtureStatusUpdate::dispatchIf($this->fixture->step == 3, $this->fixture, 'DataIntegrityCheck', 4);
        }

    }

    public function generationContentCheck()
    {
        GenerationCheck::dispatch($this->fixture);
    }

    public function generationContentRetry()
    {
        GenerationCheck::dispatch($this->fixture);
    }

    public function templateValidationCheck()
    {
        TemplateValidation::dispatch($this->fixture);
    }

    public function templateValidationRetry()
    {
        TemplateValidation::dispatch($this->fixture);
    }

    public function fixturePublish()
    {
        FixturePublish::dispatch($this->fixture);
    }

    #[On('refresh-statuses')]
    public function render()
    {
        return view('livewire.status');
    }

    public function mount(Fixtures $fixture)
    {
        $this->fixture = $fixture;
    }
}

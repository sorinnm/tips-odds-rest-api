<?php

namespace App\Livewire;

use App\Events\FixtureStatusUpdate;
use App\Events\GenerationCheck;
use App\Events\TemplateValidation;
use App\Models\Fixtures;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Livewire\Attributes\On;

class Status extends Component
{
    public Fixtures $fixture;

    public function generate()
    {
        Artisan::call('top:chatgpt:generate ' . $this->fixture->fixture_id);
    }

    public function regenerate()
    {
        if ($this->fixture->status == Fixtures::STATUS_COMPLETE) {
            $this->fixture->status = Fixtures::STATUS_PENDING;
            $this->fixture->save();
        }
        Artisan::call('top:chatgpt:generate ' . $this->fixture->fixture_id);
    }

    public function dataIntegrityCheckAck()
    {
        FixtureStatusUpdate::dispatchIf($this->fixture->step == 3, $this->fixture, 4);
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
        sleep(5);
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

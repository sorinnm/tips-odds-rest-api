<?php

namespace App\Livewire;

use App\Events\FixtureStatusUpdate;
use App\Events\GenerationCheck;
use App\Models\Fixtures;
use Livewire\Component;
use Livewire\Attributes\On;

class Status extends Component
{
    public Fixtures $fixture;

    public function generate()
    {
        sleep(5);
        $this->fixture->step = 5;
        $this->fixture->save();
    }

    public function dataIntegrityCheckAck()
    {
        FixtureStatusUpdate::dispatchIf($this->fixture->step == 3, $this->fixture, 4);
    }

    public function generationContentCheck()
    {
        GenerationCheck::dispatch($this->fixture);
        FixtureStatusUpdate::dispatchIf($this->fixture->step == 6, $this->fixture, 7);
    }

    public function generationContentRetry()
    {
        FixtureStatusUpdate::dispatchIf($this->fixture->step == 7, $this->fixture, 8);
    }

    public function retry()
    {
        sleep(5);
        $this->fixture->step = 6;
        $this->fixture->save();

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

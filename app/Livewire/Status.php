<?php

namespace App\Livewire;

use App\Models\Fixtures;
use Livewire\Component;

class Status extends Component
{
    public Fixtures $fixture;

    public function generate()
    {
        sleep(5);
        $this->fixture->step = 5;
        $this->fixture->save();
    }

    public function retry()
    {
        sleep(5);
        $this->fixture->step = 6;
        $this->fixture->save();

    }

    public function render()
    {
        return view('livewire.status');
    }

    public function mount(Fixtures $fixture)
    {
        $this->fixture = $fixture;
    }
}

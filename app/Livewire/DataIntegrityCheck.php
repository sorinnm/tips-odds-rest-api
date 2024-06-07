<?php

namespace App\Livewire;

use App\Models\Fixtures;
use App\Services\ApiFootballService;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class DataIntegrityCheck extends Component
{
    public Fixtures $fixture;

    protected ApiFootballService $apiFootballService;

    public string $reimportType;

    public function reimportAll()
    {
        Artisan::call('top:reimport-fixture ' . $this->fixture->fixture_id);
    }

    public function render()
    {
        return view('livewire.data-integrity-check');
    }

    public function mount(
        Fixtures $fixture,
        ApiFootballService $apiFootballService
    ) {
        $this->fixture = $fixture;
        $this->apiFootballService = $apiFootballService;
    }
}

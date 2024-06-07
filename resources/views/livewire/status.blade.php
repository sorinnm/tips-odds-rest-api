<div class="card">
    <div class="card-header font-weight-bold"><strong>Status</strong></div>
    <div class="card-body bg-success d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">1</span> Fixture imported from API-Football</h5>
    </div>
    <div class="card-body bg-{{ $fixture->step == 2 ? 'danger' : ($fixture->step == 3 ? 'warning' : ($fixture->step >= 4 ? 'success' : 'secondary')) }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">2</span> Data Integrity Check</h5>
    </div>
    <div class="card-body bg-{{ $fixture->step == 5 ? 'danger' : ($fixture->step == 6 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">3</span> ChatGPT generation</h5>
        @if($fixture->step == 4)
            <form wire:submit="generate">
                <input id="generationFixtureId" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">Start</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @elseif($fixture->step == 5)
            <form wire:submit="retry">
                <input id="generationFixtureId" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-secondary">Retry</button>
                <div wire:loading>
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <div class="card-body bg-{{ $fixture->step == 7 ? 'danger' : ($fixture->step == 8 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">4</span> Generation content check</h5>
        @if($fixture->step == 7)
            <a href="#" class="btn btn-secondary">Retry</a>
        @endif
    </div>
    <div class="card-body bg-{{ $fixture->step == 9 ? 'danger' : ($fixture->step == 10 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">5</span> Template validation</h5>
        @if($fixture->step == 9)
            <a href="#" class="btn btn-secondary">Retry</a>
        @endif
    </div>
    <div class="card-body bg-{{ $fixture->step == 11 ? 'danger' : ($fixture->step == 12 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">6</span> Fixture published</h5>
        @if($fixture->step == 11)
            <a href="#" class="btn btn-secondary">Retry</a>
        @endif
    </div>
</div>

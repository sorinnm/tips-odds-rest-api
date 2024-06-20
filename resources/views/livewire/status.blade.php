<div class="card">
    <div class="card-header font-weight-bold"><strong>Status</strong></div>
    <div class="card-body bg-success d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">1</span> Fixture imported from API-Football</h5>
    </div>
    <div class="card-body bg-{{ $fixture->step == 2 ? 'danger' : ($fixture->step == 3 ? 'warning' : ($fixture->step >= 4 ? 'success' : 'secondary')) }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">2</span> Data Integrity Check</h5>
        @if($fixture->step == 3)
            <form wire:submit="dataIntegrityCheckAck">
                <input id="dicFixtureId" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">OK</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <div data-bs-target=".jsonContentModal" data-type="chat_gpt_generation" data-step="{{ $fixture->step }}" class="card-body chatGptGeneration bg-{{ $fixture->step == 5 ? 'danger' : ($fixture->step >= 6 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">3</span> <span class="status-title">ChatGPT generation</span></h5>
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
        @elseif($fixture->step == 6 || $fixture->step == 7)
                <form wire:submit="regenerate">
                    <input id="generationFixtureId" type="hidden" wire:model="fixture" value="">
                    <button wire:loading.remove type="submit" class="btn btn-info">Regenerate</button>
                    <div wire:loading>
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                </form>
        @elseif($fixture->step == 5)
            <form wire:submit="generationContentRetry">
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
    <div data-bs-target=".jsonContentModal" data-type="generation_content_check" data-step="{{ $fixture->step }}" class="card-body generationContentCheck bg-{{ $fixture->step == 7 ? 'danger' : ($fixture->step >= 8 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">4</span> <span class="status-title">Generation content check</span></h5>
        @if($fixture->step == 6)
            <form wire:submit="generationContentCheck">
                <input id="generationContentCheck" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">Check</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @elseif($fixture->step == 7)
            <form wire:submit="generationContentRetry">
                <input id="generationContentRetry" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">Retry</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <div data-bs-target=".jsonContentModal" data-type="template_validation_check" data-step="{{ $fixture->step }}" class="card-body templateValidationCheck bg-{{ $fixture->step == 9 ? 'danger' : ($fixture->step >= 10 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">5</span> <span class="status-title">Template validation</span></h5>
        @if($fixture->step == 8)
            <form wire:submit="templateValidationCheck">
                <input id="templateValidationCheck" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">Check</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @elseif($fixture->step == 9)
            <form wire:submit="templateValidationRetry">
                <input id="templateValidationRetry" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">Retry</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <div class="card-body bg-{{ $fixture->step == 11 ? 'danger' : ($fixture->step == 12 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
        <h5 class="card-title"><span class="badge rounded-pill bg-dark">6</span> Fixture published</h5>
        @if($fixture->step == 10)
            <form wire:submit="fixturePublish">
                <input id="fixturePublish" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">Publish</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @elseif($fixture->step == 11)
            <form wire:submit="fixturePublish">
                <input id="fixturePublishRetry" type="hidden" wire:model="fixture" value="">
                <button wire:loading.remove type="submit" class="btn btn-info">Retry</button>
                <div wire:loading>
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </form>
        @elseif($fixture->step == 12)
            <a href="{{ $fixture->generation->url }}" target="_blank"><button type="button" class="btn btn-info">View</button></a>
        @endif
    </div>
</div>

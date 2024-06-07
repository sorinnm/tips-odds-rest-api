<form wire:submit="reimportAll">
    <ul class="list-group dataIntegrityCheck">
    <li class="list-group-item list-group-item font-weight-bold d-flex justify-content-between align-items-center">
        <strong>Data Integrity Check</strong>
        <button wire:loading.remove type="submit" class="btn btn-sm btn-secondary">Re-import</button>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
    <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="fixtures" class="icon-link icon-link-hover list-group-item list-group-item-{{ !empty($fixture->fixtures) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
        <span class="di_title">Fixtures Data</span>
        <div wire:loading.remove>
            @if(!empty($fixture->fixtures))
                <span class="badge rounded-pill bg-success">OK</span>
            @else
                <span class="badge rounded-pill bg-danger">MISSING</span>
            @endif
        </div>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
    <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="home_team_squad" class="list-group-item list-group-item-{{ !empty($fixture->home_team_squad) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
        <span class="di_title">Home Team Squad</span>
        <div wire:loading.remove>
            @if(!empty($fixture->home_team_squad))
                <span class="badge rounded-pill bg-success">OK</span>
            @else
                <span class="badge rounded-pill bg-danger">MISSING</span>
            @endif
        </div>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
    <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="away_team_squad" class="list-group-item list-group-item-{{ !empty($fixture->away_team_squad) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
        <span class="di_title">Away Team Squad</span>
        <div wire:loading.remove>
            @if(!empty($fixture->away_team_squad))
                <span class="badge rounded-pill bg-success">OK</span>
            @else
                <span class="badge rounded-pill bg-danger">MISSING</span>
            @endif
        </div>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
    <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="injuries" class="list-group-item list-group-item-{{ !empty($fixture->injuries) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
        <span class="di_title">Injuries</span>
        <div wire:loading.remove>
            @if(!empty($fixture->injuries))
                <span class="badge rounded-pill bg-success">OK</span>
            @else
                <span class="badge rounded-pill bg-danger">MISSING</span>
            @endif
        </div>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
    <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="predictions" class="list-group-item list-group-item-{{ !empty($fixture->predictions) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
        <span class="di_title">Predictions</span>
        <div wire:loading.remove>
            @if(!empty($fixture->predictions))
                <span class="badge rounded-pill bg-success">OK</span>
            @else
                <span class="badge rounded-pill bg-danger">MISSING</span>
            @endif
        </div>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
    <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="head_to_head" class="list-group-item list-group-item-{{ !empty($fixture->head_to_head) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
        <span class="di_title">Head To Head</span>
        <div wire:loading.remove>
            @if(!empty($fixture->head_to_head))
                <span class="badge rounded-pill bg-success">OK</span>
            @else
                <span class="badge rounded-pill bg-danger">MISSING</span>
            @endif
        </div>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
    <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="bets" class="list-group-item list-group-item-{{ !empty($fixture->bets) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center dataIntegrity">
        <span class="di_title">Bets</span>
        <div wire:loading.remove>
            @if(!empty($fixture->bets))
                <span class="badge rounded-pill bg-success">OK</span>
            @else
                <span class="badge rounded-pill bg-danger">MISSING</span>
            @endif
        </div>
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </li>
</ul>
</form>

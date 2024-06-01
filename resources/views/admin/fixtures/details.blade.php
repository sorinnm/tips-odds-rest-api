@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">
                            @if(empty($fixture->home_logo))
                                <img src="{{ asset('images/team_logo_placeholder.webp') }}" class="img-fluid float-start" width="40px" alt="Home Team Logo">
                            @else
                                <img src="{{ $fixture->home_logo }}" class="img-fluid float-start" width="40px" alt="Home Team Logo">
                            @endif
                            {{ $pageTitle }}
                            @if(empty($fixture->home_logo))
                                <img src="{{ asset('images/team_logo_placeholder.webp') }}" class="img-fluid float-end" width="40px" alt="Home Team Logo">
                            @else
                                <img src="{{ $fixture->away_logo }}" class="img-fluid float-end" width="40px" alt="Away Team Logo">
                            @endif
                        </h2>
                        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @foreach($breadcrumbs as $breadcrumb)
                                    <li class="breadcrumb-item active">{{ $breadcrumb }}</li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <ul class="list-group dataIntegrityCheck">
                            <li class="list-group-item list-group-item font-weight-bold d-flex justify-content-between align-items-center">
                                <strong>Data Integrity Check</strong>
                                <button type="button" title="Details" class="btn btn-sm btn-primary">Re-import</button>
                            </li>
                            <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="fixtures" class="icon-link icon-link-hover list-group-item list-group-item-{{ !empty($fixture->fixtures) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
                                <span class="di_title">Fixtures Data</span>
                                @if(!empty($fixture->fixtures))
                                    <span class="badge rounded-pill bg-success">OK</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">MISSING</span>
                                @endif
                            </li>
                            <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="home_team_squad" class="list-group-item list-group-item-{{ !empty($fixture->home_team_squad) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
                                <span class="di_title">Home Team Squad</span>
                                @if(!empty($fixture->home_team_squad))
                                    <span class="badge rounded-pill bg-success">OK</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">MISSING</span>
                                @endif
                            </li>
                            <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="away_team_squad" class="list-group-item list-group-item-{{ !empty($fixture->away_team_squad) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
                                <span class="di_title">Away Team Squad</span>
                                @if(!empty($fixture->away_team_squad))
                                    <span class="badge rounded-pill bg-success">OK</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">MISSING</span>
                                @endif
                            </li>
                            <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="injuries" class="list-group-item list-group-item-{{ !empty($fixture->injuries) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
                                <span class="di_title">Injuries</span>
                                @if(!empty($fixture->injuries))
                                    <span class="badge rounded-pill bg-success">OK</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">MISSING</span>
                                @endif
                            </li>
                            <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="predictions" class="list-group-item list-group-item-{{ !empty($fixture->predictions) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
                                <span class="di_title">Predictions</span>
                                @if(!empty($fixture->predictions))
                                    <span class="badge rounded-pill bg-success">OK</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">MISSING</span>
                                @endif
                            </li>
                            <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="head_to_head" class="list-group-item list-group-item-{{ !empty($fixture->head_to_head) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center">
                                <span class="di_title">Head To Head</span>
                                @if(!empty($fixture->head_to_head))
                                    <span class="badge rounded-pill bg-success">OK</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">MISSING</span>
                                @endif
                            </li>
                            <li data-bs-toggle="modal" data-bs-target=".jsonContentModal" data-type="bets" class="list-group-item list-group-item-{{ !empty($fixture->bets) ? 'success' : 'danger' }} d-flex justify-content-between align-items-center dataIntegrity">
                                <span class="di_title">Bets</span>
                                @if(!empty($fixture->bets))
                                    <span class="badge rounded-pill bg-success">OK</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">MISSING</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header font-weight-bold"><strong>Status</strong></div>
                            <div class="card-body bg-success d-flex justify-content-between align-items-center">
                                <h5 class="card-title"><span class="badge rounded-pill bg-dark">1</span> Fixture imported from API-Football</h5>
                            </div>
                            <div class="card-body bg-{{ $fixture->step == 2 ? 'danger' : ($fixture->step == 3 ? 'warning' : ($fixture->step == 4 ? 'success' : 'secondary')) }} d-flex justify-content-between align-items-center">
                                <h5 class="card-title"><span class="badge rounded-pill bg-dark">2</span> Data Integrity Check</h5>
                            </div>
                            <div class="card-body bg-{{ $fixture->step == 5 ? 'danger' : ($fixture->step == 6 ? 'success' : 'secondary') }} d-flex justify-content-between align-items-center">
                                <h5 class="card-title"><span class="badge rounded-pill bg-dark">3</span> ChatGPT generation</h5>
                                @if($fixture->step == 4)
                                    <a href="#" class="btn btn-info">Start</a>
                                @elseif($fixture->step == 5)
                                    <a href="#" class="btn btn-secondary">Retry</a>
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
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal -->
<div class="modal fade jsonContentModal" tabindex="-1" aria-labelledby="jsonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control modalContent" rows="20" placeholder=""></textarea>
            </div>
            <div class="modal-footer">
                <buttßon type="button" class="btn btn-info" data-bs-dismiss="modal">Re-import</buttßon>
                <button type="button" class="btn btn-secondary float-end" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary float-end">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click','.dataIntegrityCheck li',function(){
        var type = $(this).attr('data-type');
        var title = $(this).children('.di_title').text();
        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ajax.admin.fixtures.dataIntegrityCheck') }}',
            data: {'id': {{ $fixture->id }}, 'type': type},
            success : function(data){
                $('.modal-body textarea').text(JSON.stringify(data, null, "\t"));
            }
        });
        $('.modal-title').text(title);
    });
</script>

@include('admin/layout/body-scripts')
</body>
</html>

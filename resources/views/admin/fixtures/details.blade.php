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
                        <ul class="list-group">
                            <li class="list-group-item list-group-item font-weight-bold d-flex justify-content-between align-items-center"><strong>Data Integrity</strong> <button type="button" title="Details" class="btn btn-sm btn-primary">Re-import</button></li>
                            <li data-bs-toggle="modal" data-bs-target="#jsonContentModal" class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Fixtures Data <span class="badge rounded-pill bg-success">OK</span></li>
                            <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Home Team Squad <span class="badge rounded-pill bg-success">OK</span></li>
                            <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Away Team Squad <span class="badge rounded-pill bg-success">OK</span></li>
                            <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Injuries <span class="badge rounded-pill bg-danger">MISSING</span></li>
                            <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Predictions <span class="badge rounded-pill bg-success">OK</span></li>
                            <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Head To Head <span class="badge rounded-pill bg-success">OK</span></li>
                            <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">Bets <span class="badge rounded-pill bg-warning">CHECK</span></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header font-weight-bold"><strong>Status</strong></div>
                            <div class="card-body bg-success d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Fixture imported from API-Football</h5>
                            </div>
                            <div class="card-body bg-success d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Data Integrity Check</h5>
                            </div>
                            <div class="card-body bg-warning d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Data Integrity Check</h5>
                            </div>
                            <div class="card-body bg-danger d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Data Integrity Check</h5>
                            </div>
                            <div class="card-body bg-success d-flex justify-content-between align-items-center">
                                <h5 class="card-title">ChatGPT generation</h5>
                            </div>
                            <div class="card-body bg-danger d-flex justify-content-between align-items-center">
                                <h5 class="card-title">ChatGPT generation</h5>
                                <a href="#" class="btn btn-secondary">Retry</a>
                            </div>
                            <div class="card-body bg-success d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Generation content check</h5>
                            </div>
                            <div class="card-body bg-danger d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Generation content check</h5>
                                <a href="#" class="btn btn-secondary">Retry</a>
                            </div>
                            <div class="card-body bg-success d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Template validation</h5>
                            </div>
                            <div class="card-body bg-danger d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Template validation</h5>
                                <a href="#" class="btn btn-secondary">Retry</a>
                            </div>
                            <div class="card-body bg-success d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Fixture published</h5>
                            </div>
                            <div class="card-body bg-danger d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Fixture published</h5>
                                <a href="#" class="btn btn-secondary">Retry</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="jsonContentModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a comment here" style="height: 100px">
                    {{ json_encode(json_decode($fixture->home_team_squad), JSON_PRETTY_PRINT) }}
                </textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click','.deleteButton',function(){
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        $('input[name="modal_id"]').val(id);
        $('.modalName').text(name);
        $('#confirmDeleteModal').modal('show');
    });
</script>

@include('admin/layout/body-scripts')
</body>
</html>

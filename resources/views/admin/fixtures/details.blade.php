@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
                    <div class="col-3">
                        <ul class="list-group list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">API Football ID</div>
                                    {{ $fixture->fixture_id }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Imported At</div>
                                    {{ date('F j Y, H:i:s', strtotime($fixture->created_at)) }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Last Update</div>
                                    {{ date('F j Y, H:i:s', strtotime($fixture->updated_at)) }}
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-3">
                        <livewire:data-integrity-check :fixture="$fixture" />
                    </div>
                    <div class="col-6">
                        <livewire:status :fixture="$fixture" />
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
                <textarea name="modalContent" class="form-control modalContent" rows="20" placeholder=""></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary float-end" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary float-end">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click','.dataIntegrityCheck li:not(:first)',function(){
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

    $(document).on('click','.chatGptGeneration',function(){
        var step = $(this).attr('data-step');

        if (step >= 6) {
            $(this).attr('data-bs-toggle', 'modal');
            var type = $(this).attr('data-type');
            var title = $(this).find('.status-title').text();
            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ajax.admin.fixtures.validationCheck') }}',
                data: {'fixture_id': {{ $fixture->fixture_id }}, 'type': type},
                success : function(data){
                    $('.modal-body textarea').text(JSON.stringify(data, null, "\t"));
                }
            });
            $('.modal-title').text(title);
        } else {
            $(this).removeAttr('data-bs-toggle');
        }
    });

    $(document).on('click','.generationContentCheck',function(){
        var step = $(this).attr('data-step');

        if (step == 7) {
            $(this).attr('data-bs-toggle', 'modal');
            var type = $(this).attr('data-type');
            var title = $(this).find('.status-title').text();
            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ajax.admin.fixtures.validationCheck') }}',
                data: {'fixture_id': {{ $fixture->fixture_id }}, 'type': type},
                success : function(data){
                    $('.modal-body textarea').text(JSON.stringify(data, null, "\t"));
                }
            });
            $('.modal-title').text(title);
        } else {
            $(this).removeAttr('data-bs-toggle');
        }
    });

    $(document).on('click','.templateValidationCheck',function(){
        var step = $(this).attr('data-step');

        if (step == 9) {
            $(this).attr('data-bs-toggle', 'modal');
            var type = $(this).attr('data-type');
            var title = $(this).find('.status-title').text();
            $.ajax({
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ajax.admin.fixtures.validationCheck') }}',
                data: {'fixture_id': {{ $fixture->fixture_id }}, 'type': type},
                success : function(data){
                    $('.modal-body textarea').text(JSON.stringify(data, null, "\t"));
                }
            });
            $('.modal-title').text(title);
        } else {
            $(this).removeAttr('data-bs-toggle');
        }
    });
</script>

@include('admin/layout/body-scripts')
</body>
</html>

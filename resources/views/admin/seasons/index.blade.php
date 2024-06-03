@include('admin.layout.head')
<body>
@include('admin.layout.dark-mode')
@include('admin.layout.header')

<div class="container-fluid">
    <div class="row">
        @include('admin.layout.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="row">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="h2">Active seasons</h2>
                </div>
                @if (Session::has('message'))
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                @elseif(Session::has('error_message'))
                    <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
                @endif
                <div class="table-responsive small">
                    <table class="table table-striped table-hover table-sm">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">League</th>
                            <th scope="col">Country</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($activeSeasons as $season)
                            <tr>
                                <td>{{ $season->id }}</td>
                                <td>{{ $season->name }}</td>
                                <td class="w-25">{{ $season->league->name }}</td>
                                <td>{{ $season->league->country->name }}</td>
                                <td><span class="badge rounded-pill bg-success">ENABLED</span></td>
                                <td>{{ $season->created_at }}</td>
                                <td>{{ $season->updated_at }}</td>
                                <td class="text-center">
                                    <button type="button" data-status="disable" data-id="{{ $season->id }}" data-name="{{ $season->name }} {{ $season->league->name }}" title="Disable {{ $season->name }} {{ $season->league->name }}" data-bs-toggle="modal" data-bs-target="#confirmStatusModal" class="disableButton btn btn-danger" aria-label="Close"><i class="bi bi-arrow-down"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="h2">All seasons</h2>
                    <a href="{{ route('seasons.add') }}"><button type="button" class="btn btn-primary text-end"><i class="bi bi-plus-circle"></i> Add</button></a>
                </div>
                <div class="table-responsive small">
                    <table class="table table-striped table-hover table-sm">
                        <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">League</th>
                            <th scope="col">Country</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($disabledSeasons as $season)
                            <tr>
                                <td>{{ $season->id }}</td>
                                <td>{{ $season->name }}</td>
                                <td class="w-25">{{ $season->league->name }}</td>
                                <td>{{ $season->league->country->name }}</td>
                                <td><span class="badge rounded-pill bg-danger">DISABLED</span></td>
                                <td>{{ $season->created_at }}</td>
                                <td>{{ $season->updated_at }}</td>
                                <td class="text-center">
                                    <button type="button" data-status="enable" data-id="{{ $season->id }}" data-name="{{ $season->name }} {{ $season->league->name }}" title="Disable {{ $season->name }} {{ $season->league->name }}" data-bs-toggle="modal" data-bs-target="#confirmStatusModal" class="disableButton btn btn-success" aria-label="Enable"><i class="bi bi-arrow-up"></i></button>
                                    <button type="button" data-status="delete" data-id="{{ $season->id }}" data-name="{{ $season->name }} {{ $season->league->name }}" title="Delete {{ $season->name }} {{ $season->league->name }}" data-bs-toggle="modal" data-bs-target="#confirmStatusModal" class="disableButton btn btn-danger" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1" aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmStatusModalLabel">Confirm action</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <span class="statusType"></span> <span class="modalName"></span> ?
                    <input type="hidden" name="modal_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click','.disableButton',function(){
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var statusType = $(this).attr('data-status');
        var submitButton = $('button[type="submit"]');
        $('input[name="modal_id"]').val(id);
        $('.statusType').text(statusType);
        if (statusType == 'enable') {
            submitButton.removeClass('btn-danger').addClass('btn-success');
        } else {
            submitButton.removeClass('btn-success').addClass('btn-danger');
        }

        if (statusType == 'delete') {
            $('form').attr('action', '{{ route('seasons.delete') }}');
        } else {
            $('form').attr('action', '{{ route('seasons.status') }}');
        }
        submitButton.text(statusType.charAt(0).toUpperCase() + statusType.slice(1));
        $('.modalName').text(name);
    });
</script>

@include('admin.layout.body-scripts')
</body>
</html>

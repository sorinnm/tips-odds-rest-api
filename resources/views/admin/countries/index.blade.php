@include('admin.layout.head')
<body>
@include('admin.layout.dark-mode')
@include('admin.layout.header')

<div class="container-fluid">
    <div class="row">
        @include('admin.layout.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="h2">Countries</h2>
                <a href="{{ route('countries.add') }}"><button type="button" class="btn btn-primary text-end"><i class="bi bi-plus-circle"></i> Add</button></a>
            </div>
            @if (Session::has('message'))
                <div class="alert alert-info">{{ Session::get('message') }}</div>
            @elseif(Session::has('error_message'))
                <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
            @endif
            <div class="table-responsive small">
                <table class="table table-striped table-sm">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category ID</th>
                        <th scope="col">Category Path</th>
                        <th scope="col">Page ID</th>
                        <th scope="col">Sport</th>
                        <th scope="col">Author ID</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($countries as $country)
                        <tr>
                            <td>{{ $country->id }}</td>
                            <td>{{ $country->name }}</td>
                            <td>{{ $country->category_id }}</td>
                            <td>{{ $country->category_path }}</td>
                            <td>{{ $country->page_id }}</td>
                            <td>{{ $country->sport->name }}</td>
                            <td>{{ $country->author_id }}</td>
                            <td>{{ $country->created_at }}</td>
                            <td>{{ $country->updated_at }}</td>
                            <td class="text-center">
                                <a href="{{ route('countries.edit', ['id' => $country->id]) }}">
                                    <button type="button" title="Edit" class="btn btn-info"><i
                                            class="bi bi-pencil-square"></i></button>
                                </a>
                                <button type="button" data-id="{{ $country->id }}" data-name="{{ $country->name }}" title="Delete {{ $country->name }}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" class="deleteButton btn btn-danger" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('countries.delete') }}">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="confirmDeleteModalLabel">Confirm action</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to remove <span class="modalName"></span> ?
                                <input type="hidden" name="modal_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Confirm</button>
                            </div>
                        </form>
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
        </main>
    </div>
</div>

@include('admin.layout.body-scripts')
</body>
</html>

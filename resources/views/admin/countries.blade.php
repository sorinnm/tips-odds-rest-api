@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="h2">Countries</h2>
                <button type="button" class="btn btn-primary text-end"><i class="bi bi-plus-circle"></i> Add</button>
            </div>
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
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

@include('admin/layout/body-scripts')
</body>
</html>

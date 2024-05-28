@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2 class="h2">Leagues</h2>
                <button type="button" class="btn btn-primary text-end"><i class="bi bi-plus-circle"></i> Add</button>
            </div>
            <div class="table-responsive small">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Country</th>
                        <th scope="col">API-Football ID</th>
                        <th scope="col">WordPress Category ID</th>
                        <th scope="col">WordPress Category Path</th>
                        <th scope="col">WordPress Page ID</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leagues as $league)
                        <tr>
                            <td>{{ $league->id }}</td>
                            <td>{{ $league->name }}</td>
                            <td>{{ $league->country->name }}</td>
                            <td>{{ $league->api_football_id }}</td>
                            <td>{{ $league->category_id }}</td>
                            <td>{{ $league->category_path }}</td>
                            <td>{{ $league->page_id }}</td>
                            <td>{{ $league->created_at }}</td>
                            <td>{{ $league->updated_at }}</td>
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

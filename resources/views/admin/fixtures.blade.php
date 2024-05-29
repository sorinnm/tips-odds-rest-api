@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <script>
            window.chartData = {{ json_encode([2421, 5894, 12343, 4229, 3588, 900, 4100]) }};
        </script>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">Fixtures</h2>
                    </div>
                    <div class="col-4">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-success">Success <span class="badge rounded-pill bg-success">24</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-warning">Pending <span class="badge rounded-pill bg-warning">12</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-danger">Error <span class="badge rounded-pill bg-danger">8</span></li>
                        </ul>
                    </div>
                    <div class="col-8">
                        <canvas class="my-4 w-100" id="fixtures" width="900" height="190"></canvas>
                    </div>

                </div>

                <div class="row">
                    <div class="table-responsive small">
                        <table class="table table-striped table-sm align-middle">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Teams</th>
                                <th scope="col">Country</th>
                                <th scope="col">League</th>
                                <th scope="col">Season</th>
                                <th scope="col">Round</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1038292</td>
                                <td>Aston Villa - Liverpool</td>
                                <td>England</td>
                                <td>Premier League</td>
                                <td>2023</td>
                                <td>Regular Season - 35</td>
                                <td>
                                    <button type="button" title="Success" class="btn btn-success"><i class="bi bi-check"></i></button>
                                    <button type="button" title="Warning" class="btn btn-warning"><i class="bi bi-arrow-repeat"></i></button>
                                    <button type="button" title="Error" class="btn btn-danger"><span aria-hidden="true">&times;</span></button>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </main>
    </div>
</div>

@include('admin/layout/body-scripts')
<script src="{{ asset('js/bootstrap/dashboard.js') }}"></script>
</body>
</html>

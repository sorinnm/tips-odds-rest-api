@include('admin.layout.head')
<body>
@include('admin.layout.dark-mode')
@include('admin.layout.header')

<div class="container-fluid">
    <div class="row">
        @include('admin.layout.sidebar')

        <script>
            window.chartData = {{ json_encode([2421, 5894, 12343, 4229, 3588, 900, 4100]) }};
        </script>
        <main class="col-md-9 ms-sm-auto col-lg-10 overflow-scroll">
            <div class="container-fluid">
                <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light gy-2">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Dropdown
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="#">Action</a></li>
                                        <li><a class="dropdown-item" href="#">Another action</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                            <form class="d-flex">
                                <input name="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    </div>
                </nav>
                <div class="row">
                    <div class="col-4">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-success">
                                Success <span class="badge rounded-pill bg-success">24</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-warning">
                                Pending <span class="badge rounded-pill bg-warning">12</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-danger">
                                Error <span class="badge rounded-pill bg-danger">8</span></li>
                        </ul>
                    </div>
                    <div class="col-8">

                    </div>
                </div>

                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">Fixtures</h2>
                    </div>
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
                            @foreach($fixtures as $fixture)
                                @php
                                    $fixtureData = json_decode($fixture->fixtures, true);
                                @endphp
                                <tr>
                                    <td>{{ $fixture->fixture_id }}</td>
                                    <td>{{ $fixtureData[0]['teams']['home']['name'] }} vs {{ $fixtureData[0]['teams']['away']['name'] }}</td>
                                    <td>{{ $fixture->league->country->name }}</td>
                                    <td>{{ $fixture->league->name }}</td>
                                    <td>{{ $fixture->league->season->name }}</td>
                                    <td>{{ $fixture->round }}</td>
                                    <td>
                                        @if($fixture->status == 'complete')
                                            <span class="badge text-bg-success">Completed</span>
                                        @elseif($fixture->status == 'pending')
                                            <span class="badge text-bg-warning">Pending</span>
                                        @elseif($fixture->status == 'error')
                                            <span class="badge text-bg-warning">Error</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('fixtures.details', ['id' => $fixture->id]) }}">
                                            <button type="button" title="Details" class="btn btn-info"><i class="bi bi-list"></i></button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </main>
    </div>
</div>

@include('admin.layout.body-scripts')
</body>

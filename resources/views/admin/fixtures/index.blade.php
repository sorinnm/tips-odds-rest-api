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
        <main class="col-md-9 ms-sm-auto col-lg-10 overflow-y-scroll">
            <div class="container-fluid">
{{--                <nav class="navbar sticky-top navbar-expand-lg navbar-auto bg-light gy-2">--}}
{{--                    <div class="container-fluid">--}}
{{--                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--                            <span class="navbar-toggler-icon"></span>--}}
{{--                        </button>--}}
{{--                        <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
{{--                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link active" aria-current="page" href="#">Home</a>--}}
{{--                                </li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link" href="#">Link</a>--}}
{{--                                </li>--}}
{{--                                <li class="nav-item dropdown">--}}
{{--                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                                        Dropdown--}}
{{--                                    </a>--}}
{{--                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
{{--                                        <li><a class="dropdown-item" href="#">Action</a></li>--}}
{{--                                        <li><a class="dropdown-item" href="#">Another action</a></li>--}}
{{--                                        <li><hr class="dropdown-divider"></li>--}}
{{--                                        <li><a class="dropdown-item" href="#">Something else here</a></li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                            <form class="d-flex">--}}
{{--                                <input name="search" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">--}}
{{--                                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </nav>--}}
                <div class="row my-2">
                    <div class="col-4">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item font-weight-bold d-flex justify-content-between align-items-center">
                                <strong>API-Football</strong>
                            </li>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 1]) }}">
                                <li class=" list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                    Imported
                                    <span class="badge rounded-pill bg-success">{{ $stepsCount[1] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 4]) }}">
                                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                    Data Integrity OK
                                    <span class="badge rounded-pill bg-success">{{ $stepsCount[4] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 3]) }}">
                                <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">
                                    Data Integrity Warning
                                    <span class="badge rounded-pill bg-warning">{{ $stepsCount[3] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 2]) }}">
                                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">
                                    Data Integrity Error
                                    <span class="badge rounded-pill bg-danger">{{ $stepsCount[2] }}</span>
                                </li>
                            </a>
                        </ul>
                    </div>
                    <div class="col-4">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item font-weight-bold d-flex justify-content-between align-items-center">
                                <strong>ChatGPT</strong>
                            </li>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 6]) }}">
                                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                    AI Generation OK
                                    <span class="badge rounded-pill bg-success">{{ $stepsCount[6] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 5]) }}">
                                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">
                                    AI Generation Fail
                                    <span class="badge rounded-pill bg-danger">{{ $stepsCount[5] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 8]) }}">
                                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                    Generation Content OK
                                    <span class="badge rounded-pill bg-success">{{ $stepsCount[8] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 7]) }}">
                                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">
                                    Generation Content Fail
                                    <span class="badge rounded-pill bg-danger">{{ $stepsCount[7] }}</span>
                                </li>
                            </a>
                        </ul>
                    </div>
                    <div class="col-4">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item font-weight-bold d-flex justify-content-between align-items-center">
                                <strong>Wordpress</strong>
                            </li>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 10]) }}">
                                <li class=" list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                    Template Validation OK
                                    <span class="badge rounded-pill bg-success">{{ $stepsCount[10] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 9]) }}">
                                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">
                                    Template Validation Fail
                                    <span class="badge rounded-pill bg-danger">{{ $stepsCount[9] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 12]) }}">
                                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                    Published
                                    <span class="badge rounded-pill bg-success">{{ $stepsCount[12] }}</span>
                                </li>
                            </a>
                            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('fixtures.index', ['step' => 11]) }}">
                                <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">
                                    Not Published
                                    <span class="badge rounded-pill bg-danger">{{ $stepsCount[11] }}</span>
                                </li>
                            </a>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="d-flex flex-wrap justify-content-between flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">Fixtures</h2>
                        <a href="{{ route('fixtures.import.index') }}">
                            <button type="button" class="btn btn-primary text-end"><i class="bi bi-arrow-down"></i> Manual import</button>
                        </a>
                    </div>
                    @if (Session::has('messages'))
                        @php
                            $messages = Session::get('messages');
                        @endphp
                        @foreach($messages as $message)
                            <div class="alert alert-info">{{ $message }}</div>
                        @endforeach
                    @elseif(Session::has('error_message'))
                        <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
                    @endif
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
                                        <span class="badge text-bg-{{ in_array($fixture->step, [2,5,7,9,11]) ? 'danger' : ($fixture->step == 3 ? 'warning' : 'success') }}">{{ \App\Models\Fixtures::PROCESS_STEPS[$fixture->step] }}</span>
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
                        @if(!empty(Request::get('step')))
                            {{ $fixtures->appends(['step' => Request::get('step')])->links() }}
                        @else
                            {{ $fixtures->links() }}
                        @endif
                    </div>
                </div>
            </div>


        </main>
    </div>
</div>

@include('admin.layout.body-scripts')
</body>

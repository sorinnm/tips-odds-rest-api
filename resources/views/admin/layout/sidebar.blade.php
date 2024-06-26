<div class="col-2 vh-100 d-flex flex-column stick-top flex-shrink-0 p-3 text-bg-dark">
        <a href="/admin/dashboard" class="d-flex align-items-center align-middle mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <img src="{{ asset('images/logo.webp') }}" alt="logo" width="40">
            <span class="fs-4 brand-title">TipsOddsPredictions</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link text-white">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/fixtures') }}" class="nav-link text-white">
                    Fixtures
                </a>
            </li>
            <li>
                <a href="{{ route('sports.index') }}" class="nav-link text-white">
                    Sports
                </a>
            </li>
            <li>
                <a href="{{ route('countries.index') }}" class="nav-link text-white">
                    <span> - Countries</span>
                </a>
            </li>
            <li>
                <a href="{{ route('leagues.index') }}" class="nav-link text-white">
                    <span> -- Leagues</span>
                </a>
            </li>
            <li>
                <a href="{{ route('seasons.index') }}" class="nav-link text-white">
                    <span> --- Seasons</span>
                </a>
            </li>
            <li>
                <a href="{{ route('api.test') }}" class="nav-link text-white">
                    <i class="bi bi-cloud-arrow-up"></i>
                    API Test
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/logs') }}" class="nav-link text-white">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    Logs
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index') }}" class="nav-link text-white">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <div class="d-flex align-items-center justify-content-between">
                <a href="#" class=" text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>{{ $user->name }}</strong>
                </a>
                <span class="fs-6 text-secondary">v0.1.0</span>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" style="">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>

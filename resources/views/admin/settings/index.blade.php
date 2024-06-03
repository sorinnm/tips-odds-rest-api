@include('admin.layout.head')
<body>
@include('admin.layout.dark-mode')
@include('admin.layout.header')

<div class="container-fluid">
    <div class="row">
        @include('admin.layout.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="row">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="h2">Settings</h2>
                </div>
                @if (Session::has('message'))
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                @elseif(Session::has('error_message'))
                    <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
                @endif
            </div>
        </main>
    </div>
</div>

@include('admin.layout.body-scripts')
</body>
</html>

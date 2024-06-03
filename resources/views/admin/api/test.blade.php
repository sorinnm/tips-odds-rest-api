@include('admin.layout.head')
<body>
@include('admin.layout.dark-mode')
@include('admin.layout.header')

<div class="container-fluid">
    <div class="row">
        @include('admin.layout.sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 overflow-scroll">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">API Test</h2>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('admin.layout.body-scripts')
</body>

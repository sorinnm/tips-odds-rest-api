@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">Add new season</h2>
                    </div>

                    <form name="add-country" method="POST" action="{{ route('seasons.add') }}">
                        @csrf
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input name="name" value="{{ old('name')}}" type="text" required class="form-control @required('name') @error('name') is-invalid @enderror" id="floatingInput" placeholder="">
                                <label for="floatingInput">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <label class="col-lg-2 control-label">League</label>
                                <select name="leagueId"
                                        class="form-control selectpicker @error('leagueId') is-invalid @enderror"
                                        data-container="body"
                                        data-title="Please select a league"
                                        data-title-tip="Please select a league"
                                        data-live-search="true"
                                        data-style=""
                                        data-style-base="form-control"
                                        data-size="10"
                                        aria-describedby="validationServerLeagueIdFeedback">
                                    <option value="0">Please select a league...</option>
                                    @foreach($leagues as $league)
                                        <option value="{{ $league->id }}" {{ old('leagueId') == $league->id ? "selected" : '' }}>{{ $league->name }}</option>
                                    @endforeach
                                </select>
                                <div id="validationServerLeagueIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>
</div>

@include('admin/layout/body-scripts')
</body>
</html>

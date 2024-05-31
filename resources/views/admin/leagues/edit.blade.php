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
                        <h2 class="h2">{{ $pageTitle }}</h2>
                    </div>

                    <form name="edit-country" method="POST" action="{{ route('leagues.update', ['id' => $league->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <input name="name" value="{{ $league->name }}" type="text" required class="form-control @required('name') @error('name') is-invalid @enderror" id="floatingInput" placeholder="">
                                <label for="floatingInput">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <label class="col-lg-2 control-label">Country</label>
                                <select name="countryId"
                                        class="form-control selectpicker @error('countryId') is-invalid @enderror"
                                        data-container="body"
                                        data-title="Country"
                                        data-title-tip="Please select a country"
                                        data-live-search="true"
                                        data-style=""
                                        data-style-base="form-control"
                                        data-size="10"
                                        aria-describedby="validationServerCountryIdFeedback">
                                    <option value="0">Please select a country...</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $league->country->id == $country->id ? "selected" : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <div id="validationServerCountryIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select name="sportId" type="text" class="form-control @error('sportId') is-invalid @enderror" id="floatingSportId" aria-describedby="validationServerSportIdFeedback" placeholder="">
                                    <option value="0">Please select a sport...</option>
                                    @foreach($sports as $sport)
                                        <option value="{{ $sport->id }}" {{ $league->country->sport->id == $sport->id ? "selected" : '' }}>{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingSportId">Sport</label>
                                <div id="validationServerSportIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="apiFootballId" value="{{ $league->api_football_id }}" type="text" class="form-control @error('apiFootballId') is-invalid @enderror" id="floatingApiFootballId" aria-describedby="validationServerCategoryIdFeedback" placeholder="">
                                <label for="floatingApiFootballId">API-Football ID</label>
                                <div id="validationServerApiFootballIdFeedback" class="invalid-feedback">
                                    Please choose a numeric value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="categoryId" value="{{ $league->category_id }}" type="text" class="form-control @error('categoryId') is-invalid @enderror" id="floatingCategoryId" aria-describedby="validationServerCategoryIdFeedback" placeholder="">
                                <label for="floatingCategoryId">Category ID (WordPress)</label>
                                <div id="validationServerCategoryIdFeedback" class="invalid-feedback">
                                    Please choose a numeric value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="categoryPath" value="{{ $league->category_path }}" type="text" class="form-control @error('categoryPath') is-invalid @enderror" id="floatingCategoryPath" placeholder="">
                                <label for="floatingCategoryPath">Category Path (WordPress)</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="pageId" value="{{ $league->page_id }}" type="text" class="form-control @error('pageId') is-invalid @enderror" id="floatingPageId" aria-describedby="validationServerPageIdFeedback" placeholder="">
                                <label for="floatingPageId">Page ID (WordPress)</label>
                                <div id="validationServerPageIdFeedback" class="invalid-feedback">
                                    Please choose a numeric value.
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

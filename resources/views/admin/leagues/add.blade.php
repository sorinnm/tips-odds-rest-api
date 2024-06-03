@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">Add new league</h2>
                    </div>

                    <form name="add-country" method="POST" action="{{ route('leagues.add') }}">
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
                                        <option value="{{ $country->id }}" {{ old('countryId') == $country->id ? "selected" : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <div id="validationServerCountryIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="apiFootballId" value="{{ old('apiFootballId')}}" type="text" class="form-control @error('apiFootballId') is-invalid @enderror" id="floatingApiFootballId" aria-describedby="validationServerCategoryIdFeedback" placeholder="">
                                <label for="floatingApiFootballId">API-Football ID</label>
                                <div id="validationServerApiFootballIdFeedback" class="invalid-feedback">
                                    Please choose a numeric value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="categoryId" value="{{ old('categoryId')}}" type="text" class="form-control @error('categoryId') is-invalid @enderror" id="floatingCategoryId" aria-describedby="validationServerCategoryIdFeedback" placeholder="">
                                <label for="floatingCategoryId">Category ID (WordPress)</label>
                                <div id="validationServerCategoryIdFeedback" class="invalid-feedback">
                                    Please choose a numeric value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="categoryPath" value="{{ old('categoryPath')}}" type="text" class="form-control @error('categoryPath') is-invalid @enderror" id="floatingCategoryPath" placeholder="">
                                <label for="floatingCategoryPath">Category Path (WordPress)</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="pageId" value="{{ old('pageId')}}" type="text" class="form-control @error('pageId') is-invalid @enderror" id="floatingPageId" aria-describedby="validationServerPageIdFeedback" placeholder="">
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

<script>
    $( document ).ready(function() {
        $('select[name="sportId"]').selectpicker();
    });
</script>
@include('admin/layout/body-scripts')
</body>
</html>

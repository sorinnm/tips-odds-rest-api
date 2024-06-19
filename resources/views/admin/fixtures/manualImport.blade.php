@include('admin/layout/head')
<body>
@include('admin/layout/dark-mode')
@include('admin/layout/header')

<div class="container-fluid">
    <div class="row">
        @include('admin/layout/sidebar')

        <div class="overlay"></div>
        <div class="spanner">
            <div class="loader"></div>
            <p></p>
        </div>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 overflow-scroll">
            <div class="container-fluid">
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2 class="h2">{{ $pageTitle }}</h2>
                    </div>

                    <form name="add-country" method="POST" action="{{ route('fixtures.import') }}">
                        @csrf
                        <div class="col-6">
                            <div class="form-floating mb-3">
                                <select name="sportId" type="text" class="form-control @error('sportId') is-invalid @enderror" id="floatingSportId" aria-describedby="validationServerSportIdFeedback" placeholder="">
                                    <option value="0">Please select a sport...</option>
                                    @foreach($sports as $sport)
                                        <option selected value="{{ $sport->id }}" {{ old('sportId') == $sport->id ? "selected" : '' }}>{{ $sport->name }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingSportId">Sport</label>
                                <div id="validationServerSportIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <select name="countryId"
                                        class="form-control selectpicker @error('countryId') is-invalid @enderror"
                                        data-container="body"
                                        data-title="Please select a country ..."
                                        data-title-tip="Please select a country"
                                        data-live-search="true"
                                        data-style=""
                                        data-style-base="form-control"
                                        data-size="10"
                                        aria-describedby="validationServerCountryIdFeedback"
                                        id="countryId"
                                        placeholder="">
                                    <option value="0">Please select a country...</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('countryId') == $country->id ? "selected" : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <label for="countryId">Country</label>
                                <div id="validationServerCountryIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>
                            <div class="leagues form-floating mb-3 visually-hidden">
                                <input list="leagues" class="form-control @error('leagueId') is-invalid @enderror" id="leagueId" name="leagueId" aria-describedby="validationServerLeagueIdFeedback" placeholder=""/>
                                <datalist id="leagues">
                                </datalist>
                                <label for="leagueId">League</label>
                                <div id="validationServerLeagueIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>
                            <div class="seasons form-floating mb-3 visually-hidden">
                                <input list="seasons" class="form-control @error('seasonId') is-invalid @enderror" id="seasonId" name="seasonId" aria-describedby="validationServerSeasonIdFeedback" placeholder=""/>
                                <datalist id="seasons">
                                </datalist>
                                <label for="seasonId">Season</label>
                                <div id="validationServerSeasonIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>
                            <div class="rounds form-floating mb-3 visually-hidden">
                                <input list="rounds" class="form-control @error('roundId') is-invalid @enderror" id="roundId" name="roundId" aria-describedby="validationServerRoundIdFeedback" placeholder=""/>
                                <datalist id="rounds">
                                </datalist>
                                <label for="roundId">Round</label>
                                <div id="validationServerRoundIdFeedback" class="invalid-feedback">
                                    Please select a value.
                                </div>
                            </div>

                            <div class="importButton d-grid gap-2 d-md-flex justify-content-md-end visually-hidden">
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    $( "#countryId" ).on( "change", function() {
        var countryId = $(this).val();

        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ajax.admin.fixtures.refreshSelectOptions') }}',
            data: {'id': countryId, 'type': 'leagues'},
            beforeSend: function() {
                $("div.spanner").addClass("show");
                $("div.overlay").addClass("show");
            },
            complete: function(){
                $("div.spanner").removeClass("show");
                $("div.overlay").removeClass("show");
            },
            success : function(data){
                if ($.isEmptyObject(data)) {
                    console.log('no leagues');
                } else {
                    $.each(data, function (index, league) {
                        $('#leagues').append($('<option>', {
                            value: league.name
                        }));
                    });

                    $('.leagues').removeClass('visually-hidden');
                }
            }
        });
    });

    $( "#leagueId" ).on( "change", function() {
        var leagueName = $(this).val();

        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ajax.admin.fixtures.refreshSelectOptions') }}',
            data: {'id': leagueName, 'type': 'seasons'},
            beforeSend: function() {
                $("div.spanner").addClass("show");
                $("div.overlay").addClass("show");
            },
            complete: function(){
                $("div.spanner").removeClass("show");
                $("div.overlay").removeClass("show");
            },
            success : function(data){
                if ($.isEmptyObject(data)) {
                    $('.seasons').text('No seasons found. Add a new one here: ');
                    var addSeasonButton = $('<a>', {id:'addNewSeason', href: '{{ route('seasons.add') }}', 'class': 'btn btn-secondary', role: 'button', text: 'Add new season'}).appendTo('.seasons');
                } else {
                    $.each(data, function (index, league) {
                        $('#seasons').append($('<option>', {
                            value: league.name
                        }));
                    });
                }

                $('.seasons').removeClass('visually-hidden');
            }
        });
    });

    $( "#seasonId" ).on( "change", function() {
        var seasonId = $(this).val();

        $.ajax({
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ajax.admin.fixtures.getCurrentRound') }}',
            data: {'season': seasonId, 'league': $( "#leagueId" ).val()},
            beforeSend: function() {
                $("div.spanner").addClass("show");
                $("div.overlay").addClass("show");
            },
            complete: function(){
                $("div.spanner").removeClass("show");
                $("div.overlay").removeClass("show");
            },
            success : function(data){
                if ($.isEmptyObject(data)) {
                    $('.rounds').text('No rounds found.');
                } else {
                    $.each(data, function (index, value) {
                        $('#rounds').append($('<option>', {
                            value: value
                        }));
                    });
                }

                $('.rounds').removeClass('visually-hidden');
            }
        });
    });

    $( "#roundId" ).on( "change", function() {
        $('.importButton').removeClass('visually-hidden');
    });

</script>

@include('admin/layout/body-scripts')
</body>
</html>

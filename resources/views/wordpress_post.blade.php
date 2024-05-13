<!-- wp:heading {"textAlign":"center","level":1,"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}}},"backgroundColor":"background","textColor":"foreground"} -->
<h1 class="wp-block-heading has-text-align-center has-foreground-color has-background-background-color has-text-color has-background has-link-color" id="h-aston-villa-vs-liverpool-may-14-2024-betting-tips-amp-predictions"><strong>{{ str_replace(' Match Preview', '', $first_paragraph['title'])}} {{$matchDate}} Betting Tips &amp; Predictions</strong></h1>
<!-- /wp:heading -->

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}}} -->
<hr class="wp-block-separator has-alpha-channel-opacity" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-aston-villa-vs-liverpool-may-14-2024-betting-tips-amp-predictions-0"><strong><strong>{{ str_replace(' Match Preview', '', $first_paragraph['title'])}} {{$matchDate}} Betting Tips &amp; Predictions</strong></strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong><strong>{{ str_replace(' Match Preview', '', $first_paragraph['title'])}} {{$matchDate}} Betting Tips &amp; Predictions</strong></strong><br>{{ $first_paragraph['content'] }}</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-aston-villa-vs-liverpool-betting-predictions"><strong>{{ $second_paragraph['title'] }}</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{ $second_paragraph['content'] }}</p>
<!-- /wp:paragraph -->

@foreach ($second_paragraph['top_3_predictions'] as $prediction)
    <!-- wp:paragraph -->
    <p><strong>{{ $prediction['prediction'] }}</strong>: {{ $prediction['explanation'] }}</p>
    <!-- /wp:paragraph -->
@endforeach

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-head-to-head-encounter-insights-aston-villa-vs-liverpool"><strong>{{ $third_paragraph['title'] }}</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{ $third_paragraph['content'] }}<br></p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
    @foreach ($third_paragraph['head_to_head'] as $h2h)
        <!-- wp:list-item -->
        <li>{{ $h2h['match'] }}</li>
        <!-- /wp:list-item -->
    @endforeach
</ul>
<!-- /wp:list -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-aston-villa-vs-liverpool-squad-overview-and-absence-impact">{{ $forth_paragraph['title'] }}</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{ $forth_paragraph['content'] }}</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>These are the {{ str_replace(' Match Preview', '', $first_paragraph['title'])}} {{$matchDate}} Betting Tips &amp; Predictions.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

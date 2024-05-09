<!-- wp:heading {"textAlign":"center","level":1,"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}}},"backgroundColor":"background","textColor":"foreground"} -->
<h1 class="wp-block-heading has-text-align-center has-foreground-color has-background-background-color has-text-color has-background has-link-color"><strong>{{ str_replace(' Match Preview', '', $match_preview_title) }} Tips &amp; Predictions</strong></h1>
<!-- /wp:heading -->
<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}}} -->
<hr class="wp-block-separator has-alpha-channel-opacity" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"/>
<!-- /wp:separator -->
<!-- wp:heading -->
<h2 class="wp-block-heading"><strong>{{ str_replace(' Match Preview', '', $match_preview_title) }} Tips &amp; Predictions</strong></h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong>{{ $match_preview_title }}</strong><br>{{ $match_preview_content }}</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>{{ $top_predictions_title }}</strong><br>{{ $top_predictions_content }}</p>
<!-- /wp:paragraph -->
{{--@foreach($predictions as $prediction)--}}
{{--    <!-- wp:paragraph -->--}}
{{--    <p><strong>{{ $prediction[0] }}</strong>{{ $prediction[1] }}</p>--}}
{{--    <!-- /wp:paragraph -->--}}
{{--@endforeach--}}
<!-- wp:paragraph -->
<p><strong>{{ $recent_results_title }}</strong><br>{{ $recent_result_content }}</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>{{ $starting_lineup_title }}</strong><br>{{ $starting_lineup_content }}</p>
<!-- /wp:paragraph -->

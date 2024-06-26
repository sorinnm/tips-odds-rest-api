<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}}} -->
<hr class="wp-block-separator has-alpha-channel-opacity" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center" id="h-aston-villa-vs-liverpool-may-13-2024-betting-tips-amp-predictions"><strong><strong>{{ str_replace(' Match Preview', '', $first_paragraph['title']) }} {{$matchDate}}<br>Betting Tips &amp; Predictions</strong></strong></h1>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-betting-predictions"><strong>Betting Predictions</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{ $second_paragraph['content'] }}</p>
<!-- /wp:paragraph -->

@php
    function parseJson($arr, $key1 = 'title', $key2 = 'reason') {
        $results = [];

        function recursiveParse($item, &$results, $key1, $key2) {
            foreach ($item as $key => $value) {
                if (is_array($value)) {
                    recursiveParse($value, $results, $key1, $key2);
                } else {
                    if (!isset($results[$key1])) {
                        $results[$key1] = $value;
                    } elseif (!isset($results[$key2])) {
                        $results[$key2] = $value;
                    }

                    if (isset($results[$key1]) && isset($results[$key2])) {
                        $results[] = [$key1 => $results[$key1], $key2 => $results[$key2]];
                        unset($results[$key1], $results[$key2]);
                    }
                }
            }
        }

        recursiveParse($arr, $results, $key1, $key2);

        return $results;
    }

    $topPredictions = parseJson($second_paragraph['top_3_predictions']);
@endphp


@foreach ($topPredictions as $prediction)
    <!-- wp:paragraph -->
    <p><strong>{{ $prediction['title'] }}</strong>: {{ $prediction['reason'] }}</p>
    <!-- /wp:paragraph -->
@endforeach

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-match-preview"><strong>Match preview</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{ $first_paragraph['content'] }}</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-head-to-head-analysis"><strong>Head-to-Head Analysis</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{ $third_paragraph['content'] }}<br></p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
    @foreach ($third_paragraph['head_to_head'] as $h2h)
        @php
            $keys = array_keys($h2h);
        @endphp
            <!-- wp:list-item -->
        <li>{{ $h2h[$keys[0]] }}</li>
        <!-- /wp:list-item -->
    @endforeach
</ul>
<!-- /wp:list -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-squad-analysis">Squad Analysis</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{{ $forth_paragraph['content'] }}</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>These are the {{ str_replace(' Match Preview', '', $first_paragraph['title']) }} {{$matchDate}} Betting Tips &amp; Predictions.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

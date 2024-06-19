<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\Seasons;
use App\Models\Sports;
use App\Models\TextGenerator;
use App\Services\AdminService;
use App\Services\ApiFootballService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FixturesController extends Controller
{
    public function __construct(
        protected AdminService $adminService,
        protected ApiFootballService $apiFootballService
    ){}

    public function index(Request $request)
    {
        $user = $request->user();
        $fixtures = Fixtures::orderBy('created_at', 'desc')->paginate(20);

        if ($request->query('step')) {
            $fixtures = $fixtures->whereIn('step', explode(',', $request->query('step')));
        }

        if ($request->query('league')) {
            $fixtures = Fixtures::whereHas('league', function (Builder $query) use ($request) {
                $query->where('name', 'like', $request->query('league'));
            });
        }

        $stepsCount = [
             1 => Fixtures::all()->where('step','=',1)->count(),
             2 => Fixtures::all()->where('step','=',2)->count(),
             3 => Fixtures::all()->where('step','=',3)->count(),
             4 => Fixtures::all()->where('step','=',4)->count(),
             5 => Fixtures::all()->where('step','=',5)->count(),
             6 => Fixtures::all()->where('step','=',6)->count(),
             7 => Fixtures::all()->where('step','=',7)->count(),
             8 => Fixtures::all()->where('step','=',8)->count(),
             9 => Fixtures::all()->where('step','=',9)->count(),
            10 => Fixtures::all()->where('step','=',10)->count(),
            11 => Fixtures::all()->where('step','=',11)->count(),
            12 => Fixtures::all()->where('step','=',12)->count(),
        ];

        return view('admin.fixtures.index', [
            'user' => $user,
            'pageTitle' => 'Fixtures',
            'fixtures' => $fixtures,
            'stepsCount' => $stepsCount
        ]);
    }

    public function add(Request $request)
    {
        $user = $request->user();
        $countries = Countries::all();
        return view('admin.leagues.add', [
            'user' => $user,
            'pageTitle' => 'Add new League',
            'countries' => $countries
        ]);
    }

    public function save(Request $request)
    {
        $user = $request->user();

        $rules = array(
            'name'          => 'required',
            'apiFootballId' => 'numeric',
            'pageId'        => 'numeric|nullable',
            'categoryId'    => 'numeric',
            'countryId'     => 'numeric|not_in:0'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->with([
                    'user' => $user,
                    'pageTitle' => 'Add Sport'
                ])
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $league = new Leagues();
            $league->name            = $request->get('name');
            $league->api_football_id = $request->get('apiFootballId');
            $league->country_id      = $request->get('countryId');
            $league->category_id     = $request->get('categoryId');
            $league->category_path   = $request->get('categoryPath');
            $league->page_id         = $request->get('pageId');
            $league->save();

            // redirect
            Session::flash('message', 'Successfully created league!');

            return redirect('admin/leagues');
        }
    }

    public function details(Request $request, int $id)
    {
        $user = $request->user();
        $fixture = Fixtures::find($id);
        $fixtureData = json_decode($fixture->fixtures, true);
        $homeTeam = $fixtureData[0]['teams']['home']['name'];
        $awayTeam = $fixtureData[0]['teams']['away']['name'];

        $breadcrumbs = [
            $fixture->league->country->sport->name,
            $fixture->league->country->name,
            $fixture->league->name,
            $fixture->league->season->name,
            $fixture->round
        ];

        $league = Leagues::find($id);
        $countries = Countries::all();
        $sports = Sports::all();

        return view('admin.fixtures.details', [
            'user' => $user,
            'pageTitle' => "$homeTeam vs $awayTeam",
            'fixture' => $fixture,
            'breadcrumbs' => $breadcrumbs,
            'league' => $league,
            'countries' => $countries,
            'sports' => $sports
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = $request->user();
        $league = Leagues::find($id);

        $rules = array(
            'name'         => 'required',
            'categoryId'   => 'numeric',
            'pageId'       => 'numeric'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->with([
                    'user' => $user,
                    'pageTitle' => "Update league $league->name"
                ])
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $league->name            = $request->get('name');
            $league->country_id      = $request->get('countryId');
            $league->category_id     = $request->get('categoryId');
            $league->category_path   = $request->get('categoryPath');
            $league->page_id         = $request->get('pageId');
            $league->save();

            // redirect
            Session::flash('message', "Successfully updated #$league->id $league->name");

            return redirect('admin/leagues');
        }
    }

    public function delete(Request $request)
    {
        $league = Leagues::find($request->get('modal_id'));

        if (!empty($league)) {
            $league->delete();
            Session::flash('message', "Successfully deleted the #$league->id $league->name");
        } else {
            Session::flash('message', "Unable to delete");
        }

        return redirect('admin/leagues');
    }

    public function manualImportIndex(Request $request)
    {
        $user = $request->user();
        $leagues = Leagues::all();
        $sports = Sports::all();
        $countries = Countries::all();

        return view('admin.fixtures.manualImport', [
            'user' => $user,
            'pageTitle' => "Manual Fixtures Import",
            'leagues' => $leagues,
            'sports' => $sports,
            'countries' => $countries
        ]);
    }

    public function manualImport(Request $request)
    {
        $messages = [];
        $leagueName = $request->get('leagueId');

        $leagueId = Leagues::all()->where('name', '=', $leagueName)->first()->api_football_id;
        $seasonId = $request->get('seasonId');
        $roundId = $request->get('roundId');

        try {
            $result = $this->apiFootballService->importFixtures($leagueId, $seasonId, $roundId);

            if (!empty($result)) {
                foreach ($result['report'] as $fixture) {
                    $messages[] = "Successfully imported {$fixture['Match']} - ID: {$fixture['ID']}";
                }

                Session::flash('messages', $messages);
            }
        } catch (\Exception $e) {
            Session::flash('message', $e->getMessage());
        }

        return redirect('admin/fixtures');
    }

    public function ajaxDataIntegrity(Request $request): \Illuminate\Http\JsonResponse
    {
        $fixture = Fixtures::find($request->get('id'));
        $type = $request->get('type');

        return response()->json(json_decode($fixture->$type, true), 200);
    }

    public function ajaxChatGPT(Request $request): \Illuminate\Http\JsonResponse
    {
        switch ($type = $request->get('type')) {
            case 'chat_gpt_generation':
                $generation = TextGenerator::all()
                    ->where('fixture_id', $request->get('fixture_id'))
                    ->first();


                $jsonArray = trim($generation->generation, "```json\n");
                break;
            case 'generation_content_check':
                break;
            default:
                $jsonArray = [];
                break;
        }

        return response()->json(json_decode($jsonArray, true), 200);
    }

    public function ajaxRefreshOptions(Request $request): \Illuminate\Http\JsonResponse
    {
        switch ($type = $request->get('type')) {
            case 'leagues':
                $jsonArray = Leagues::all()->where('country_id', $request->get('id'));
                break;
            case 'seasons':
                $leagueName = $request->get('id');
                $league = Leagues::all()->where('name', $leagueName)->first();
                $jsonArray = Seasons::all()->where('league_id', $league->id);
                break;
            default:
                $jsonArray = [];
        }

        return response()->json($jsonArray, 200);
    }

    public function ajaxGetCurrentRound(Request $request): \Illuminate\Http\JsonResponse
    {
        $season = $request->get('season');
        $leagueName = $request->get('league');
        $league = Leagues::all()->where('name', $leagueName)->first();

        $currentRound = $this->apiFootballService->getCurrentRound($league->api_football_id, $season);

        return response()->json($currentRound, 200);
    }
}

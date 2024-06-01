<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\Seasons;
use App\Models\Sports;
use App\Services\AdminService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FixturesController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){}

    public function index(Request $request)
    {
        $user = $request->user();
        $fixtures = Fixtures::all();

        return view('admin.fixtures.index', [
            'user' => $user,
            'pageTitle' => 'Fixtures',
            'fixtures' => $fixtures
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

        $modalData = [
            json_encode(json_decode($fixture->fixtures, true), JSON_PRETTY_PRINT),
            json_encode(json_decode($fixture->home_team_squad, true), JSON_PRETTY_PRINT),
            json_encode(json_decode($fixture->away_team_squad, true), JSON_PRETTY_PRINT),
            json_encode(json_decode($fixture->injuries, true), JSON_PRETTY_PRINT),
            json_encode(json_decode($fixture->predictions, true), JSON_PRETTY_PRINT),
            json_encode(json_decode($fixture->head_to_head, true), JSON_PRETTY_PRINT),
            json_encode(json_decode($fixture->bets, true), JSON_PRETTY_PRINT),
        ];

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
            'sports' => $sports,
            'modalData' => $modalData
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
                    'pageTitle' => "Update league {$league->name}"
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
            Session::flash('message', "Successfully updated #{$league->id} {$league->name}");

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

    public function ajaxDataIntegrity(Request $request): \Illuminate\Http\JsonResponse
    {
        $fixture = Fixtures::find($request->get('id'));
        $type = $request->get('type');

        return response()->json(json_decode($fixture->$type, true), 200);
    }
}

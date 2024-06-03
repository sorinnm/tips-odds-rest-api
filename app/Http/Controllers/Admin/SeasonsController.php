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
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SeasonsController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){}

    public function index(Request $request)
    {
        $user = $request->user();
        $activeSeasons = Seasons::all()->where('is_active', 1);
        $disabledSeasons = Seasons::all()->where('is_active', 0);

        return view('admin.seasons.index', [
            'user' => $user,
            'pageTitle' => 'Seasons',
            'activeSeasons' => $activeSeasons,
            'disabledSeasons' => $disabledSeasons
        ]);
    }

    public function add(Request $request)
    {
        $user = $request->user();
        $leagues = Leagues::all();

        return view('admin.seasons.add', [
            'user' => $user,
            'pageTitle' => 'Add new seasons',
            'leagues' => $leagues
        ]);
    }

    public function save(Request $request)
    {
        $user = $request->user();

        $rules = array(
            'name'         => 'required',
            'leagueId'      => 'numeric|not_in:0',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->with([
                    'user' => $user,
                    'pageTitle' => 'Add new seasons'
                ])
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $season = new Seasons();
            $season->name            = $request->get('name');
            $season->league_id       = $request->get('leagueId');
            $season->save();

            // redirect
            Session::flash('message', 'Successfully created new season!');
            return redirect('admin/seasons')->with([
                    'user' => $user,
                    'pageTitle' => 'Add new season'
            ]);
        }
    }

    public function delete(Request $request)
    {
        $season = Seasons::find($request->get('modal_id'));

        if (!empty($season)) {
            $season->delete();
            Session::flash('message', "Successfully deleted the $season->name | {$season->league->name} | {$season->league->country->name}");
        } else {
            Session::flash('error_message', "Unable to delete");
        }

        return redirect('admin/seasons');
    }

    public function status(Request $request): Application|Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $season = Seasons::find($request->get('modal_id'));

        if (!empty($season)) {
            $season->is_active = !$season->is_active;
            $season->save();
            $status = $season->is_active ? 'enabled' : 'disabled';
            Session::flash('message', "Successfully $status the season $season->name | {$season->league->name} | {$season->league->country->name}");
        }

        return redirect('admin/seasons');
    }
}

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

class LeaguesController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){}

    public function index(Request $request)
    {
        $user = $request->user();
        $leagues = Leagues::all();

        return view('admin.leagues.index', [
            'user' => $user,
            'pageTitle' => 'Leagues',
            'leagues' => $leagues
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

    public function edit(Request $request, int $id)
    {
        $user = $request->user();
        $league = Leagues::find($id);
        $countries = Countries::all();
        $sports = Sports::all();

        return view('admin.leagues.edit', [
            'user' => $user,
            'pageTitle' => "Edit {$league->name}",
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
                    'pageTitle' => 'Update league '
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
}

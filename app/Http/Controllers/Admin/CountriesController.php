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

class CountriesController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){}

    public function index(Request $request)
    {
        $user = $request->user();
        $countries = Countries::all();

        return view('admin.countries.index', [
            'user' => $user,
            'pageTitle' => 'Countries',
            'countries' => $countries
        ]);
    }

    public function add(Request $request)
    {
        $user = $request->user();
        $sports = Sports::all();
        return view('admin.countries.add', [
            'user' => $user,
            'pageTitle' => 'Add Country',
            'sports' => $sports
        ]);
    }

    public function save(Request $request)
    {
        $user = $request->user();

        $rules = array(
            'name'         => 'required',
            'categoryId'   => 'numeric|nullable',
            'categoryPath' => 'string|nullable',
            'authorId'     => 'numeric|nullable',
            'sportId'      => 'numeric|not_in:0',
            'pageId'       => 'numeric|nullable'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->with([
                    'user' => $user,
                    'pageTitle' => 'Add Country'
                ])
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $country = new Countries;
            $country->name            = $request->get('name');
            $country->category_id     = $request->get('categoryId');
            $country->category_path   = $request->get('categoryPath');
            $country->sport_id         = $request->get('sportId');
            $country->page_id         = $request->get('pageId');
            $country->author_id       = $request->get('authorId');
            $country->save();

            // redirect
            Session::flash('message', 'Successfully created new country!');
            return redirect('admin/countries')->with([
                    'user' => $user,
                    'pageTitle' => 'Add Country'
            ]);
        }
    }

    public function edit(Request $request, int $id)
    {
        $user = $request->user();
        $country = Countries::find($id);
        $sports = Sports::all();

        return view('admin.countries.edit', [
            'user' => $user,
            'pageTitle' => "Edit {$country->name}",
            'country' => $country,
            'sports' => $sports
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = $request->user();
        $rules = array(
            'name'         => 'required',
            'categoryId'   => 'numeric',
            'pageId'       => 'numeric',
            'sportId'      => 'numeric|not_in:0',
            'authorId'     => 'numeric'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->with([
                    'user' => $user,
                    'pageTitle' => 'Add Country'
                ])
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $country = Countries::find($id);
            $country->name            = $request->get('name');
            $country->category_id     = $request->get('categoryId');
            $country->category_path   = $request->get('categoryPath');
            $country->sport_id        = $request->get('sportId');
            $country->page_id         = $request->get('pageId');
            $country->author_id       = $request->get('authorId');
            $country->save();

            // redirect
            Session::flash('message', "Successfully updated #{$country->id} {$country->name}");
            return redirect('admin/countries')->with([
                'user' => $user,
                'pageTitle' => 'Countries'
            ]);
        }
    }

    public function delete(Request $request)
    {
        $country = Countries::find($request->get('modal_id'));

        if (!empty($country)) {
            $country->delete();
            Session::flash('message', "Successfully deleted the #$country->id $country->name");
        } else {
            Session::flash('error_message', "Unable to delete");
        }



        return redirect('admin/countries');
    }
}

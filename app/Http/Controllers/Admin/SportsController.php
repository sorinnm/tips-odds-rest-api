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

class SportsController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){}

    public function index(Request $request)
    {
        $user = $request->user();
        $sports = Sports::all();

        return view('admin.sports.index', [
            'user' => $user,
            'pageTitle' => 'Sports',
            'sports' => $sports
        ]);
    }

    public function add(Request $request)
    {
        $user = $request->user();
        return view('admin.sports.add', [
            'user' => $user,
            'pageTitle' => 'Add Sport',
        ]);
    }

    public function save(Request $request)
    {
        $user = $request->user();

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
                    'pageTitle' => 'Add Sport'
                ])
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $sport = new Sports;
            $sport->name            = $request->get('name');
            $sport->category_id     = $request->get('categoryId');
            $sport->category_path   = $request->get('categoryPath');
            $sport->page_id         = $request->get('pageId');
            $sport->save();

            // redirect
            Session::flash('message', 'Successfully created sport!');

            return redirect('admin/sports');
        }
    }

    public function edit(Request $request, int $id)
    {
        $user = $request->user();
        $sport = Sports::find($id);

        return view('admin.sports.edit', [
            'user' => $user,
            'pageTitle' => "Edit {$sport->name}",
            'sport' => $sport
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = $request->user();
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
                    'pageTitle' => 'Add Sport'
                ])
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $sport = Sports::find($id);
            $sport->name            = $request->get('name');
            $sport->category_id     = $request->get('categoryId');
            $sport->category_path   = $request->get('categoryPath');
            $sport->page_id         = $request->get('pageId');
            $sport->save();

            // redirect
            Session::flash('message', "Successfully updated #{$sport->id} {$sport->name}");

            return redirect('admin/sports');
        }
    }

    public function delete(Request $request)
    {
        $sport = Sports::find($request->get('modal_id'));

        if (!empty($sport)) {
            $sport->delete();
            Session::flash('message', "Successfully deleted the #$sport->id $sport->name");
        } else {
            Session::flash('message', "Unable to delete");
        }



        return redirect('admin/sports');
    }
}

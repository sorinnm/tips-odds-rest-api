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
use Illuminate\Support\Facades\Storage;

class ApiTestController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){}

    /**
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('admin.api.test', [
            'user' => $user,
            'pageTitle' => 'API Test'
        ]);
    }
}

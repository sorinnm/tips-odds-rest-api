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

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ){}

    /**
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('admin/login')
            ->with('pageTitle', 'TipsOddsPredictions Admin Dashboard');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $activeSeasons = Seasons::all()->where('is_active', '=', 1);
        $fixtures = Fixtures::all();

        $fixturesChartData = $this->adminService->getChartDataCurrentWeek('api-football');
        $chatGptChartData = $this->adminService->getChartDataCurrentWeek('chatgpt');
        $wordpressChartData = $this->adminService->getChartDataCurrentWeek('wordpress');

        $fixturesStatuses  = $this->adminService->getStatuses('fixtures');
        $generationsStatuses  = $this->adminService->getStatuses('generations');
        $wordpressStatuses  = $this->adminService->getStatuses('wordpress');

        return view('admin/index', [
            'user' => $user,
            'pageTitle' => 'Dashboard',
            'activeSeasons' => $activeSeasons,
            'fixturesChartData' => $fixturesChartData,
            'chatGptChartData' => $chatGptChartData,
            'wordpressChartData' => $wordpressChartData,
            'fixtures' => $fixtures,
            'fixturesStatuses' => $fixturesStatuses,
            'generationsStatuses' => $generationsStatuses,
            'wordpressStatuses' => $wordpressStatuses,
        ]);
    }

    public function fixtures(Request $request)
    {
        $user = $request->user();
        $fixtures = Fixtures::all();

        return view('admin/fixtures', [
            'user' => $user,
            'pageTitle' => 'Fixtures',
            'fixtures' => $fixtures
        ]);
    }

    public function logs(Request $request)
    {
        $user = $request->user();

        return view('admin/logs', ['user' => $user, 'pageTitle' => 'Logs']);
    }

    public function settings(Request $request)
    {
        $user = $request->user();

        return view('admin.settings.index', [
            'user' => $user,
            'pageTitle' => 'Settings'
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('/admin/dashboard');
        } else {
            return redirect()->back()->withErrors([
                'email' => 'The provided credentials do not match our records.'
            ]);
        }
    }
}

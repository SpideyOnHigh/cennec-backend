<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // public $generalService;

    // public function __construct(GeneralService $generalService)
    // {
    //     $this->generalService = $generalService;
    // }

    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return redirect()->route('dashboard');
    }

    public function root()
    {
        return view('index');
    }

    public function dashboard()
    {
        return $this->adminDashboard();
    }

    public function adminDashboard()
    {
        return view('backend.dashboard.admin-dashboard');
    }

    public function pageNotFound()
    {
        return view('backend.page-not-found');
    }
}

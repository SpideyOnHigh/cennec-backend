<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function home()
    {
        return view('frontend.pages.home');
    }
    public function termCondition()
    {
        return view('frontend.pages.term-condition');
    }
    public function privacyPolicy()
    {
        return view('frontend.pages.privacy-policy');
    }

    public function refundPolicy()
    {
        return view('frontend.pages.refund-policy');
    }

    public function pricing()
    {
        return view('frontend.pages.pricing');
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     *
     * @return Response
     */
    public function __invoke()
    {
        return 'smspower.co.ke coming soon...';
        //return view('frontend.homepage');
    }
}

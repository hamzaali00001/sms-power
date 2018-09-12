<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    /**
     * Show the reports.
     *
     * @return Response
     */
    public function __invoke()
    {
        return view('backend.sms-reports.index');
    }
}

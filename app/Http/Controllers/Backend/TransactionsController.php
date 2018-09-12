<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Purchases\BuyCreditRequest;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Jenssegers\Optimus\Optimus;

class TransactionsController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('purchases');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Jenssegers\Optimus\Optimus $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Optimus $optimus)
    {
        if (auth()->user()->hasRole('admin')) {
            $purchases = Purchase::with('user')->latest()->get();
        } else {
            $purchases = auth()->user()->purchases()->with('user')->latest()->get();
        }

        return view('backend.purchases.index', compact('purchases', 'optimus'));
    }
}

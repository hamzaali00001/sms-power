<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Purchases\BuyCreditRequest;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Jenssegers\Optimus\Optimus;

class PurchasesController extends Controller
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
    public function index(Optimus $optimus)
    {
        if (auth()->user()->hasRole('admin')) {
            $purchases = Purchase::with('user')->latest()->get();
        } else {
            $purchases = auth()->user()->purchases()->with('user')->latest()->get();
        }

        return view('backend.purchases.index', compact('purchases', 'optimus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Jenssegers\Optimus\Optimus $request
     * @return \Illuminate\Http\Response
     */
    public function create(Optimus $optimus)
    {
        return view('backend.purchases.create', compact('optimus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Backend\Purchases\BuyCreditRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyCreditRequest $request)
    {
        Purchase::create($request->all());

        flash()->success('The Credit Purchase has been successful.');

        return redirect()->route('purchases.index');
    }
}

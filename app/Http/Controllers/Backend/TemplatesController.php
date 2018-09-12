<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Templates\CreateTemplateRequest;
use App\Http\Requests\Backend\Templates\UpdateTemplateRequest;
use App\Http\Controllers\Controller;
use App\Models\Template;

class TemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $templates = Template::with('user')->latest()->get();
        } else {
            $templates = auth()->user()->templates()->with('user')->latest()->get();
        }

        return view('backend.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Backend\Templates\CreateTemplateRequest $request
     * @param  App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTemplateRequest $request)
    {
        Template::create($request->all());

        flash()->success('The template has been created successfully.');

        return redirect()->route('templates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        return view('backend.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        return view('backend.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Backend\Templates\UpdateTemplateRequest $request
     * @param  App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTemplateRequest $request, Template $template)
    {
        $template->update($request->all());

        flash()->success('The template has been updated successfully.');

        return redirect()->route('templates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        $template->delete();

        flash()->success('The template has been deleted successfully.');
        
        return redirect()->route('templates.index');
    }
}

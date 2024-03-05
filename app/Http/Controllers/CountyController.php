<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CountyController extends Controller
{
    public function load(Request $request)
    {
        // Load counties
        if ($request->input('paginate')){
            return County::filter($request)->paginate(50);
        } else {
            return County::filter($request)->get();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/County');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(County $county)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(County $county)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, County $county)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(County $county)
    {
        //
    }
}

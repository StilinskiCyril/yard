<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CountyController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return County::filter($request)->paginate(50);
        } else {
            return County::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/County');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:counties']
        ]);

        County::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, County $county)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('counties')->ignore($county->id)]
        ]);

        $county->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }


    public function destroy(County $county)
    {
        $county->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

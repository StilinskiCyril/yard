<?php

namespace App\Http\Controllers;

use App\Models\BodyType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BodyTypeController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return BodyType::filter($request)->paginate(50);
        } else {
            return BodyType::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/BodyType');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', 'unique:body_types']
        ]);

        BodyType::create([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, BodyType $bodyType)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', Rule::unique('body_types')->ignore($bodyType->id)]
        ]);

        $bodyType->update([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    public function destroy(BodyType $bodyType)
    {
        $bodyType->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

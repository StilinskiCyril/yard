<?php

namespace App\Http\Controllers;

use App\Models\FuelType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FuelTypeController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return FuelType::filter($request)->paginate(50);
        } else {
            return FuelType::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/FuelType');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', 'unique:fuel_types']
        ]);

        FuelType::create([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, FuelType $fuelType)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', Rule::unique('fuel_types')->ignore($fuelType->id)]
        ]);

        $fuelType->update([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    public function destroy(FuelType $fuelType)
    {
        $fuelType->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

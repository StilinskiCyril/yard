<?php

namespace App\Http\Controllers;

use App\Models\VehicleCondition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VehicleConditionController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return VehicleCondition::filter($request)->paginate(50);
        } else {
            return VehicleCondition::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/VehicleCondition');
    }

    public function store(Request $request)
    {
        $request->validate([
            'condition' => ['required', 'string', 'max:50', 'unique:vehicle_conditions']
        ]);

        VehicleCondition::create([
            'condition' => $request->condition
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, VehicleCondition $vehicleCondition)
    {
        $request->validate([
            'condition' => ['required', 'string', 'max:50', Rule::unique('vehicle_conditions')->ignore($vehicleCondition->id)]
        ]);

        $vehicleCondition->update([
            'condition' => $request->condition
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    public function destroy(VehicleCondition $vehicleCondition)
    {
        $vehicleCondition->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

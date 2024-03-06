<?php

namespace App\Http\Controllers;

use App\Models\TransmissionType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TransmissionTypeController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return TransmissionType::filter($request)->paginate(50);
        } else {
            return TransmissionType::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/TransmissionType');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', 'unique:transmission_types']
        ]);

        TransmissionType::create([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, TransmissionType $transmissionType)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', Rule::unique('drive_types')->ignore($transmissionType->id)]
        ]);

        $transmissionType->update([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    public function destroy(TransmissionType $transmissionType)
    {
        $transmissionType->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

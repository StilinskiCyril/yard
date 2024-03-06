<?php

namespace App\Http\Controllers;

use App\Models\DriveType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DriveTypeController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return DriveType::filter($request)->paginate(50);
        } else {
            return DriveType::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/DriveType');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', 'unique:drive_types']
        ]);

        DriveType::create([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, DriveType $driveType)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:50', Rule::unique('drive_types')->ignore($driveType->id)]
        ]);

        $driveType->update([
            'type' => $request->type
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    public function destroy(DriveType $driveType)
    {
        $driveType->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

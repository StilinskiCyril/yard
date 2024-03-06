<?php

namespace App\Http\Controllers;

use App\Models\DriveSetup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DriveSetupController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return DriveSetup::filter($request)->paginate(50);
        } else {
            return DriveSetup::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/DriveSetup');
    }

    public function store(Request $request)
    {
        $request->validate([
            'setup' => ['required', 'string', 'max:50', 'unique:drive_setups']
        ]);

        DriveSetup::create([
            'setup' => $request->setup
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, DriveSetup $driveSetup)
    {
        $request->validate([
            'setup' => ['required', 'string', 'max:50', Rule::unique('drive_setups')->ignore($driveSetup->id)]
        ]);

        $driveSetup->update([
            'setup' => $request->setup
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    public function destroy(DriveSetup $driveSetup)
    {
        $driveSetup->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

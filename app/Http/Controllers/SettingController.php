<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return Setting::filter($request)->paginate(50);
        } else {
            return Setting::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/Setting');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:settings'],
            'value' => ['required', 'string', 'max:50']
        ]);

        Setting::create([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('settings')->ignore($setting->id)],
            'value' => ['required', 'string', 'max:50']
        ]);

        $setting->update([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }


    public function destroy(Setting $setting)
    {
        $setting->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

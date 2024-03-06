<?php

namespace App\Http\Controllers;

use App\Models\Make;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MakeController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return Make::filter($request)->paginate(50);
        } else {
            return Make::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/Make');
    }

    public function store(Request $request)
    {
        $request->validate([
            'make' => ['required', 'string', 'max:50', 'unique:makes'],
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:1024']
        ]);

        if ($request->hasFile('logo')){
            $logoUrl = Storage::put('makes/logo', $request->file('logo'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Please upload logo'
            ]);
        }

        Make::create([
            'make' => $request->make,
            'logo_url' => $logoUrl
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, Make $make)
    {
        $request->validate([
            'make' => ['required', 'string', 'max:50', Rule::unique('makes')->ignore($make->id)],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024']
        ]);

        if ($request->hasFile('logo')){
            $logoUrl = Storage::put('makes/logo', $request->file('logo'));
            // Delete the old logo
            if (!is_null($make->logo_url) && Storage::exists($make->logo_url)) {
                Storage::delete($make->logo_url);
            }
        } else {
            $logoUrl = $make->logo_url;
        }

        $make->update([
            'make' => $request->make,
            'logo_url' => $logoUrl
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    public function destroy(Make $make)
    {
        if (!is_null($make->logo_url) && Storage::exists($make->logo_url)) {
            Storage::delete($make->logo_url);
        }

        $make->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }

    public function show(Make $make)
    {
        return Inertia::render('Admin/MakeModel', ['make' => $make]);
    }
}

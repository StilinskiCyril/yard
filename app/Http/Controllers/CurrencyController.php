<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    public function load(Request $request)
    {
        if ($request->input('paginate')){
            return Currency::filter($request)->paginate(50);
        } else {
            return Currency::filter($request)->get();
        }
    }

    public function index()
    {
        return Inertia::render('Admin/Currency');
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency' => ['required', 'string', 'max:50', 'unique:currencies']
        ]);

        Currency::create([
            'currency' => $request->currency
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'currency' => ['required', 'string', 'max:50', Rule::unique('currencies')->ignore($currency->id)]
        ]);

        $currency->update([
            'currency' => $request->currency
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }


    public function destroy(Currency $currency)
    {
        $currency->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

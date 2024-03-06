<?php

namespace App\Http\Controllers;

use App\Models\Make;
use App\Models\MakeModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MakeModelController extends Controller
{
    public function load(Request $request, Make $make)
    {
        if ($request->input('paginate')){
            return $make->makeModels()->filter($request)->paginate(50);
        } else {
            return $make->makeModels()->filter($request)->get();
        }
    }

    public function store(Request $request)
    {
        $make = Make::where('uuid', $request->uuid)->first();
        if (!$make){
            return response()->json([
                'status' => false,
                'message' => 'Make not found'
            ], 404);
        }

        $request->validate([
            'uuid' => ['required', 'string'],
            'model' => ['required', 'string', 'max:50', Rule::unique('make_models')->where('make_id', $make->id)]
        ]);

        $make->makeModels()->create([
            'model' => $request->model
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }


    public function update(Request $request, MakeModel $makeModel)
    {
        $request->validate([
            'model' => ['required', 'string', 'max:50', Rule::unique('make_models')->where('make_id', $makeModel->make_id)->ignore($makeModel->id)]
        ]);

        $makeModel->update([
            'model' => $request->model
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }


    public function destroy(MakeModel $makeModel)
    {
        $makeModel->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted'
        ]);
    }
}

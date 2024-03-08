<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function load(Request $request)
    {
        $query = Vehicle::filter($request);
        if ($request->input('paginate')) {
            $perPage = 50;
            if ($request->user()->hasRole('company-user')) {
                $query->where('user_id', $request->user()->id);
            } elseif ($request->user()->hasRole('company-admin')) {
                $query->where('company_id', $request->user()->company_id);
            }
            return $query->paginate($perPage);
        } else {
            if ($request->user()->hasRole('company-user')) {
                $query->where('user_id', $request->user()->id);
            } elseif ($request->user()->hasRole('company-admin')) {
                $query->where('company_id', $request->user()->company_id);
            }
            return $query->get();
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Shared/Vehicle');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'makeModelId' => ['required', 'numeric'],
            'transmissionTypeId' => ['required', 'numeric'],
            'vehicleConditionId' => ['required', 'numeric'],
            'engineCapacity' => ['required', 'numeric'],
            'fuelTypeId' => ['required', 'numeric'],
            'driveTypeId' => ['required', 'numeric'],
            'bodyTypeId' => ['required', 'numeric'],
            'driveSetupId' => ['required', 'numeric'],
            'currencyId' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'mileage' => ['required', 'numeric'],
            'yom' => ['required', 'numeric'],
            'color' => ['required', 'string'],
            'horsePower' => ['required', 'numeric'],
            'torque' => ['required', 'numeric'],
            'coverPhoto' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
            'youtubeUrl' => ['nullable', 'string', 'max:255'],
            'availability' => ['required', 'numeric', Rule::in([1, 3])],
            'features' => ['required', 'string']
        ]);

        if ($request->hasFile('coverPhoto')){
            $coverPhotoUrl = Storage::put('vehicles/coverphoto', $request->file('coverPhoto'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Please upload cover photo'
            ]);
        }

        Vehicle::create([
            'user_id' => $request->user()->id,
            'company_id' => $request->user()->company_id,
            'name' => $request->name,
            'make_model_id' => $request->makeModelId,
            'transmission_type_id' => $request->transmissionTypeId,
            'vehicle_condition_id' => $request->vehicleConditionId,
            'engine_capacity' => $request->engineCapacity,
            'fuel_type_id' => $request->fuelTypeId,
            'drive_type_id' => $request->driveTypeId,
            'body_type_id' => $request->bodyTypeId,
            'drive_setup_id' => $request->driveSetupId,
            'currency_id' => $request->currencyId,
            'price' => $request->price,
            'mileage' => $request->mileage,
            'yom' => $request->yom,
            'color' => $request->color,
            'horse_power' => $request->horsePower,
            'torque' => $request->torque,
            'cover_photo_url' => $coverPhotoUrl,
            'youtube_url' => $request->youtubeUrl,
            'features' => $request->features,
            'availability' => $request->availability
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record created'
        ]);
    }


    public function update(Request $request, Vehicle $vehicle)
    {
        if (!$request->user()->hasRole('admin') && $vehicle->status){
            return response()->json([
                'status' => false,
                'message' => 'You cannot update an approved vehicle. Contact customer support for assistance on '.env('CUSTOMER_SUPPORT_MSISDN')
            ], 403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'makeModelId' => ['required', 'numeric'],
            'transmissionTypeId' => ['required', 'numeric'],
            'vehicleConditionId' => ['required', 'numeric'],
            'engineCapacity' => ['required', 'numeric'],
            'fuelTypeId' => ['required', 'numeric'],
            'driveTypeId' => ['required', 'numeric'],
            'bodyTypeId' => ['required', 'numeric'],
            'driveSetupId' => ['required', 'numeric'],
            'currencyId' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'mileage' => ['required', 'numeric'],
            'yom' => ['required', 'numeric'],
            'color' => ['required', 'string'],
            'horsePower' => ['required', 'numeric'],
            'torque' => ['required', 'numeric'],
            'coverPhoto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
            'youtubeUrl' => ['nullable', 'string', 'max:255'],
            'availability' => ['required', 'numeric', Rule::in([1, 3])],
            'features' => ['required', 'string']
        ]);

        if ($request->hasFile('coverPhoto')){
            $coverPhotoUrl = Storage::put('vehicles/coverphoto', $request->file('coverPhoto'));
            // Delete the old file
            if (!is_null($vehicle->cover_photo_url) && Storage::exists($vehicle->cover_photo_url)) {
                Storage::delete($vehicle->cover_photo_url);
            }
        } else {
            $coverPhotoUrl = $vehicle->cover_photo_url;
        }

        $vehicle->update([
            'name' => $request->name,
            'make_model_id' => $request->makeModelId,
            'transmission_type_id' => $request->transmissionTypeId,
            'vehicle_condition_id' => $request->vehicleConditionId,
            'engine_capacity' => $request->engineCapacity,
            'fuel_type_id' => $request->fuelTypeId,
            'drive_type_id' => $request->driveTypeId,
            'body_type_id' => $request->bodyTypeId,
            'drive_setup_id' => $request->driveSetupId,
            'currency_id' => $request->currencyId,
            'price' => $request->price,
            'mileage' => $request->mileage,
            'yom' => $request->yom,
            'color' => $request->color,
            'horse_power' => $request->horsePower,
            'torque' => $request->torque,
            'cover_photo_url' => $coverPhotoUrl,
            'youtube_url' => $request->youtubeUrl,
            'features' => $request->features,
            'availability' => $request->availability
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Record updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}

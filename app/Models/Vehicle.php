<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'updated_at'];

    protected $appends = ['formatted_cover_photo_url'];

    protected $with = ['makeModel', 'transmissionType', 'vehicleCondition', 'fuelType', 'driveType', 'bodyType', 'driveSetup', 'currency'];

    public function getFormattedCoverPhotoUrlAttribute()
    {
        return !is_null($this->cover_photo_url) ? Storage::url($this->cover_photo_url) : '/images/404.png';
    }

    public function scopeFilter($q){
        if (!is_null(request('name')) && !empty(request('name'))) {
            $q->where('name', 'like', '%'.request('name').'%');
        }
        if (!is_null(request('makeModelId')) && !empty(request('makeModelId'))) {
            $q->where('make_model_id', request('makeModelId'));
        }
        if(!is_null(request('transmissionTypeId')) && !empty(request('transmissionTypeId'))){
            $q->where('transmission_type_id', request('transmissionTypeId'));
        }
        if(!is_null(request('vehicleConditionId')) && !empty(request('vehicleConditionId'))){
            $q->where('vehicle_condition_id', request('vehicleConditionId'));
        }
        if(!is_null(request('engineCapacity')) && !empty(request('engineCapacity'))){
            $q->where('engine_capacity', request('engineCapacity'));
        }
        if(!is_null(request('fuelTypeId')) && !empty(request('fuelTypeId'))){
            $q->where('fuel_type_id', request('fuelTypeId'));
        }
        if(!is_null(request('driveTypeId')) && !empty(request('driveTypeId'))){
            $q->where('drive_type_id', request('driveTypeId'));
        }
        if(!is_null(request('bodyTypeId')) && !empty(request('bodyTypeId'))){
            $q->where('body_type_id', request('bodyTypeId'));
        }
        if(!is_null(request('driveSetupId')) && !empty(request('driveSetupId'))){
            $q->where('drive_setup_id', request('driveSetupId'));
        }
        if(!is_null(request('currencyId')) && !empty(request('currencyId'))){
            $q->where('currency_id', request('currencyId'));
        }
        if(!is_null(request('price')) && !empty(request('price'))){
            $q->where('price', request('price'));
        }
        if(!is_null(request('mileage')) && !empty(request('mileage'))){
            $q->where('mileage', request('mileage'));
        }
        if(!is_null(request('yom')) && !empty(request('yom'))){
            $q->where('yom', request('yom'));
        }
        if(!is_null(request('color')) && !empty(request('color'))){
            $q->where('color', request('color'));
        }
        if(!is_null(request('horsePower')) && !empty(request('horsePower'))){
            $q->where('horse_power', request('horsePower'));
        }
        if(!is_null(request('torque')) && !empty(request('torque'))){
            $q->where('torque', request('torque'));
        }
        if(!is_null(request('availability')) && !empty(request('availability'))){
            $q->where('availability', request('availability'));
        }
        if(!is_null(request('status')) && !empty(request('status'))){
            $q->where('status', request('status'));
        }
        if(!is_null(request('paymentStatus')) && !empty(request('paymentStatus'))){
            $q->where('payment_status', request('paymentStatus'));
        }
        if (!is_null(request('sortBy')) && !empty(request('sortBy'))) {
            if (request('sortBy') == 'random') {
                $q->inRandomOrder();
            }
            if (request('sortBy') == 'latest') {
                $q->latest();
            }
            if (request('sortBy') == 'oldest') {
                $q->oldest();
            }
        }
        return $q;
    }

    public function makeModel()
    {
        return $this->belongsTo(MakeModel::class);
    }

    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class);
    }

    public function vehicleCondition()
    {
        return $this->belongsTo(VehicleCondition::class);
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }

    public function driveType()
    {
        return $this->belongsTo(DriveType::class);
    }

    public function bodyType()
    {
        return $this->belongsTo(BodyType::class);
    }

    public function driveSetup()
    {
        return $this->belongsTo(DriveSetup::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}

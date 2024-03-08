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

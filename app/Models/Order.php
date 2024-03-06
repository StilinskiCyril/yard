<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'updated_at'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model){
            $model->uuid = Str::orderedUuid()->toString();
            $model->account_no = 'OR-'.genRandInt(8);
        });
    }
}

<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MpesaAccessToken extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $guarded = ['id'];

    protected $hidden = ['id', 'updated_at', 'deleted_at'];

    public function scopeFilter($q){
        if (!is_null(request('type')) && !empty(request('type'))) {
            $q->where('type', request('type'));
        }
        if (!is_null(request('token')) && !empty(request('token'))) {
            $q->where('token', request('token'));
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
}

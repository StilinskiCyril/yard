<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MakeModel extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'updated_at'];

    protected $with = ['make'];

    public function scopeFilter($q){
        if (!is_null(request('model')) && !empty(request('model'))) {
            $q->where('model', 'like', '%'.request('model').'%');
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

    public function make()
    {
        return $this->belongsTo(Make::class);
    }
}

<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class County extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'updated_at'];

    public function scopeFilter($q){
        if (!is_null(request('name')) && !empty(request('name'))) {
            $q->where('name', request('name'));
        }
        if (!is_null(request('sort_by')) && !empty(request('sort_by'))) {
            if (request('sort_by') == 'random') {
                $q->inRandomOrder();
            }
            if (request('sort_by') == 'latest') {
                $q->latest();
            }
            if (request('sort_by') == 'oldest') {
                $q->oldest();
            }
        }
        return $q;
    }
}

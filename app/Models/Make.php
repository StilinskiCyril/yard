<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Make extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at', 'updated_at'];

    protected $appends = ['formatted_logo_url'];

    protected $withCount = ['makeModels'];

    public function getFormattedLogoUrlAttribute()
    {
        return !is_null($this->logo_url) ? Storage::url($this->logo_url) : '/images/404.png';
    }

    public function scopeFilter($q){
        if (!is_null(request('make')) && !empty(request('make'))) {
            $q->where('make', 'like', '%'.request('make').'%');
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

    public function makeModels()
    {
        return $this->hasMany(MakeModel::class);
    }
}

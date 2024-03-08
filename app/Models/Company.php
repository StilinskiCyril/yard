<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['id', 'deleted_at', 'updated_at'];

    protected $appends = ['formatted_logo_url', 'formatted_kyc_doc_url'];

    public function getFormattedLogoUrlAttribute()
    {
        return !is_null($this->logo_url) ? Storage::url($this->logo_url) : '/images/404.png';
    }

    public function getFormattedKycDocUrlAttribute()
    {
        return !is_null($this->kyc_doc_url) ? Storage::url($this->kyc_doc_url) : '/images/404.png';
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model){
            $model->uuid = Str::orderedUuid()->toString();
            $model->account_no = 'C0-'.genRandInt(8);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

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

    public function users()
    {
        return $this->hasManyThrough(User::class, CompanyUser::class, 'company_id', 'id', 'id', 'user_id');
    }

    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class, 'company_id', 'id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'company_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

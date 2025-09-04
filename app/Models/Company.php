<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = ['id'];
    public function usageHistory()
    {
        return $this->hasMany(ITAssetUsageHistory::class);
    }

    public function asset()
    {
        return $this->hasMany(ITAsset::class);
    }
}

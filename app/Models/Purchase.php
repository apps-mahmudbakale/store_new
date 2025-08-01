<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'json', // Assuming you want to store additional data as JSON
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    //
    protected $guarded = [];

    protected $casts = [
        'data' => 'json', // Assuming you want to store additional data as JSON
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

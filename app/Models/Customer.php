<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\Cast;

class Customer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'json', // Assuming you want to store additional data as JSON
    ];

    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }
}

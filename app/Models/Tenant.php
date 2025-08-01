<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact',
        'data', // Assuming you want to store additional data as JSON
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }
    public function customers() {
        return $this->hasMany(Customer::class);
    }

    public function providers() {
        return $this->hasMany(Provider::class);

    }
    public function units() {
        return $this->hasMany(Unit::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}

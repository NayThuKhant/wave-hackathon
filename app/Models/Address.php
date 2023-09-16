<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $appends = ['full_address'];

    protected $fillable = ['user_id', 'floor', 'street', 'township', 'city'];

    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn($value) => "$this->floor, $this->street, $this->township, $this->city",
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

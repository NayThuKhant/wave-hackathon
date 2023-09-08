<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'pricing_model'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

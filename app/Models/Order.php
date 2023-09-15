<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'started_at' => 'datetime',
    ];

    protected $fillable = ['address', 'employer_id', 'category_id', 'employee_id', 'status', 'started_at', 'rating', 'feedback'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withPivot('quantity');
    }
}

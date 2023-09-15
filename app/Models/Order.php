<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        "started_at" => "datetime",
    ];
    protected $fillable = ['address', 'employer_id', 'employee_id', 'status', 'started_at', 'rating', 'feedback'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}

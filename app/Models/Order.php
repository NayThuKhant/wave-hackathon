<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['address', 'employer_id', 'employee_id', 'status', 'started_at', 'rating', 'feedback'];
}

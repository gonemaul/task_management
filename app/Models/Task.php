<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'deadline',
        'end_date',
        'priority',
        'status',
    ];
}

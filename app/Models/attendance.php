<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances';
    protected $fillable = [
        'user_id',
        'clock_status',
        'time',
    ];
}

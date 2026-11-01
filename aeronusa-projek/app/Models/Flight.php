<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $guarded = [];

    protected $casts = [
        'occupied_seats' => 'array',
    ];
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'selected_seats' => 'array',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
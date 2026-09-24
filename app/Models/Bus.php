<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'bus_code',
        'bus_name',
        'bus_type',
        'plate_number',
        'total_seats',
        'facilities',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'facilities' => 'array',
    ];

    public function routes()
    {
        return $this->hasMany(TravelRoute::class, 'bus_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TravelRoute extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'routes';

    protected $fillable = [
        'bus_id',
        'origin_city',
        'origin_terminal',
        'destination_city',
        'destination_terminal',
        'departure_date',
        'departure_time',
        'estimated_arrival_time',
        'price',
        'available_seats',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'route_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'route_id');
    }

    public function averageRating(): ?float
    {
        $average = $this->reviews()->avg('rating');

        return $average === null ? null : round((float) $average, 1);
    }
}
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

    /**
     * URL Foto Bus resmi dengan fallback gambar berkualitas jika foto belum diunggah.
     */
    public function getImageUrlAttribute(): string
    {
        if (! empty($this->image)) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (str_starts_with($this->image, 'images/')) {
                return asset($this->image);
            }
            return asset('storage/' . $this->image);
        }

        return asset('images/hero-bus.jpg');
    }
}

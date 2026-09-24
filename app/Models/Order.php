<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'route_id',
        'order_code',
        'ticket_code',
        'total_passengers',
        'total_price',
        'payment_status',
        'order_status',
        'cancellation_note',
        'expired_at',
        'ticket_issued_at',
        'checked_in_at',
        'seats_released',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'expired_at' => 'datetime',
        'ticket_issued_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function route()
    {
        return $this->belongsTo(
            TravelRoute::class,
            'route_id'
        );
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

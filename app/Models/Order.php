<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'product_id',
        'total_amount',
        'payment_method',
        'payment_status',
        'effective_date',
        'receiver_name',
        'receiver_phone',
        'shipping_address',
        'insurance_link',
        'tracking_code',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

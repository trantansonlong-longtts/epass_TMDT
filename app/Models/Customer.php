<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // 1. Cho phép lưu các cột này
    protected $fillable = [
        'full_name',
        'phone_number',
        'identity_card',
        'email',
        'address',
    ];

    // 2. Một khách hàng có thể có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

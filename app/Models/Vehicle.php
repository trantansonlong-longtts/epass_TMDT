<?php

namespace App\Models;

use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // 1. Cho phép lưu các cột này
    protected $fillable = [
        'customer_id',
        'license_plate',
        'vehicle_type',
        'epass_tag_number',
        'owner_name',
        'owner_address',
        'plate_color',
        'payload',
        'seat_capacity',
        'chassis_number',
        'engine_number',
        'issue_date',
        'registration_image', // Cột ảnh Cà vẹt
        'front_vehicle_image',
        'inspection_image',
    ];

    // 2. Khai báo xe này thuộc về khách hàng nào
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected function registrationImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => UploadUrl::forPath($this->registration_image)
        );
    }

    protected function frontVehicleImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => UploadUrl::forPath($this->front_vehicle_image)
        );
    }

    protected function inspectionImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => UploadUrl::forPath($this->inspection_image)
        );
    }
}

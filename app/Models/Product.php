<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Support\UploadUrl;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'base_price',
        'vat_price',
        'selling_price',
        'description',
        'sort_order',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if (! $this->image) {
                    return null;
                }

                if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                    return $this->image;
                }

                if (str_starts_with($this->image, 'images/')) {
                    return asset($this->image);
                }

                return UploadUrl::forPath($this->image);
            }
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()
            ->whereIn('name', [
                'Tập lái dưới 9 chỗ',
                'Dưới 6 chỗ TN (Không kinh doanh)',
                'Dưới 6 chỗ KD (Kinh doanh)',
                'Xe tải dưới 3T',
            ])
            ->update(['is_active' => false]);

        $products = [
            ...$this->insuranceProducts(),
            [
                'name' => 'Phí dán thẻ Epass',
                'category' => 'EPASS',
                'base_price' => 109090,
                'vat_price' => 120000,
                'selling_price' => 120000,
                'description' => 'Phí hỗ trợ dán thẻ và tiếp nhận hồ sơ ePass cho khách hàng cần kích hoạt thu phí không dừng.',
                'image' => 'images/catalog/epass.svg',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }

    private function insuranceProducts(): array
    {
        $rows = [
            ['Bán tải - Không kinh doanh (biển trắng)', 530700, 360000],
            ['5 chỗ - Không kinh doanh (biển trắng)', 530700, 360000],
            ['7 chỗ - Không kinh doanh (biển trắng)', 943400, 641000],
            ['16 chỗ - Không kinh doanh (biển trắng)', 1557000, 1056000],
            ['29 chỗ - Không kinh doanh (biển trắng)', 2297000, 1558000],
            ['44 chỗ - Không kinh doanh (biển trắng)', 2448000, 1655000],
            ['59 chỗ - Không kinh doanh (biển trắng)', 2597000, 1753000],
            ['Dưới 3 tấn - Không kinh doanh (biển trắng)', 968000, 660000],
            ['Trên 3 đến dưới 8 tấn - Không kinh doanh (biển trắng)', 1856000, 1265000],
            ['Từ 8 đến dưới 15 tấn - Không kinh doanh (biển trắng)', 3050000, 2079000],
            ['Trên 15 tấn - Không kinh doanh (biển trắng)', 3550000, 2420000],
            ['Bán tải - Kinh doanh (biển vàng)', 842000, 574000],
            ['5 chỗ - Kinh doanh (biển vàng)', 842000, 574000],
            ['7 chỗ - Kinh doanh (biển vàng)', 1198000, 817000],
            ['16 chỗ - Kinh doanh (biển vàng)', 3370000, 2297000],
            ['29 chỗ - Kinh doanh (biển vàng)', 5436000, 3706000],
            ['44 chỗ - Kinh doanh (biển vàng)', 5932000, 4044000],
            ['59 chỗ - Kinh doanh (biển vàng)', 6426000, 4382000],
            ['Dưới 3 tấn - Kinh doanh (biển vàng)', 968000, 660000],
            ['Từ 3 đến dưới 8 tấn - Kinh doanh (biển vàng)', 1856000, 1265000],
            ['Từ 8 đến dưới 15 tấn - Kinh doanh (biển vàng)', 3050000, 2079000],
            ['Trên 15 tấn - Kinh doanh (biển vàng)', 3550000, 2420000],
        ];

        return array_map(function (array $row) {
            [$name, $vatPrice, $sellingPrice] = $row;

            return [
                'name' => $name,
                'category' => 'INSURANCE',
                'base_price' => round($vatPrice / 1.1),
                'vat_price' => $vatPrice,
                'selling_price' => $sellingPrice,
                'description' => $this->insuranceDescription($name),
                'image' => $this->insuranceImage($name),
                'is_active' => true,
            ];
        }, $rows);
    }

    private function insuranceDescription(string $name): string
    {
        if (str_contains($name, 'Kinh doanh')) {
            return 'Gói bảo hiểm dành cho xe kinh doanh vận tải, hỗ trợ báo giá và tiếp nhận hồ sơ nhanh theo đúng nhóm sử dụng.';
        }

        return 'Gói bảo hiểm dành cho xe không kinh doanh, phù hợp cho xe cá nhân và xe gia đình cần mức phí rõ ràng, dễ tra cứu.';
    }

    private function insuranceImage(string $name): string
    {
        return match (true) {
            str_contains($name, 'Bán tải') => 'images/catalog/pickup.svg',
            str_contains($name, '5 chỗ') => 'images/catalog/sedan.svg',
            str_contains($name, '7 chỗ') => 'images/catalog/suv.svg',
            str_contains($name, '16 chỗ'), str_contains($name, '29 chỗ'), str_contains($name, '44 chỗ'), str_contains($name, '59 chỗ') => 'images/catalog/bus.svg',
            str_contains($name, 'tấn') => 'images/catalog/truck.svg',
            default => 'images/catalog/sedan.svg',
        };
    }
}

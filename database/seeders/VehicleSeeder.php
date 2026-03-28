<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Dùng để tạo email tự động từ tên

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Danh sách thông tin 4 xe bóc tách từ ảnh
        $vehicleData = [
            [
                'owner_name' => 'CÔNG TY TNHH VẬN TẢI THÀNH DANH',
                'owner_address' => '321/3B Đường số 6, KP6, P. Bình Trưng Tây, TP. Thủ Đức, TP. HCM',
                'license_plate' => '50H-231.86',
                'vehicle_type' => 'Ô tô tải (có mui)',
                'plate_color' => 'Vàng',
                'payload' => '4450 kg',
                'seat_capacity' => 3,
                'chassis_number' => 'JDPB121G00000078',
                'engine_number' => 'N04C-8176510',
                'issue_date' => '2023-01-09',
            ],
            [
                'owner_name' => 'ĐÀO TƯỚNG TÌNH',
                'owner_address' => '184A/6 Đường 10, P. Linh Xuân, Q. Thủ Đức, TP. HCM',
                'license_plate' => '51H-045.54',
                'vehicle_type' => 'Ô tô con',
                'plate_color' => 'Trắng',
                'payload' => '0 kg',
                'seat_capacity' => 5,
                'chassis_number' => 'RLCCT412BJX02138',
                'engine_number' => 'G4FGJU387431',
                'issue_date' => '2019-01-16',
            ],
            [
                'owner_name' => 'PHẠM VĂN QUẢNG',
                'owner_address' => '172/178/14 An Dương Vương, P.16, Q.8, TP. HCM',
                'license_plate' => '51F-507.03',
                'vehicle_type' => 'Ô tô con',
                'plate_color' => 'Bạc',
                'payload' => '0 kg',
                'seat_capacity' => 8,
                'chassis_number' => '52GG9018047',
                'engine_number' => '2TR5488106',
                'issue_date' => '2015-06-18',
            ],
            [
                'owner_name' => 'VÕ THANH CÔNG',
                'owner_address' => '73/19 Vườn Lài, P. Phú Thọ Hòa, Q. Tân Phú, TP. HCM',
                'license_plate' => '51F-438.16',
                'vehicle_type' => 'Ô tô con',
                'plate_color' => 'Trắng',
                'payload' => '0 kg',
                'seat_capacity' => 5,
                'chassis_number' => 'RLCBA414BEH01170',
                'engine_number' => 'G4LADF580226',
                'issue_date' => '2015-05-13',
            ],
        ];

        // Vòng lặp: Chạy qua từng chiếc xe để tạo Khách hàng và Xe tương ứng
        foreach ($vehicleData as $data) {

            // 1. Tạo Khách hàng trước
            $customerId = DB::table('customers')->insertGetId([
                'full_name' => $data['owner_name'], // Lấy tên trên cà vẹt làm tên khách
                'phone_number' => '090'.rand(1000000, 9999999), // Tạo số ĐT giả định
                'identity_card' => '079'.rand(100000000, 999999999), // Tạo số CCCD giả định
                'email' => Str::slug($data['owner_name']).'@gmail.com', // Tạo email tự động (vd: vo-thanh-cong@gmail.com)
                'address' => $data['owner_address'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 2. Tạo Phương tiện và gắn ID khách hàng vừa tạo vào
            DB::table('vehicles')->insert([
                'customer_id' => $customerId,
                'license_plate' => $data['license_plate'],
                'vehicle_type' => $data['vehicle_type'],
                'epass_tag_number' => 'EPASS-'.strtoupper(Str::random(8)), // Cấp ngẫu nhiên 1 mã thẻ Epass
                'owner_name' => $data['owner_name'],
                'owner_address' => $data['owner_address'],
                'plate_color' => $data['plate_color'],
                'payload' => $data['payload'],
                'seat_capacity' => $data['seat_capacity'],
                'chassis_number' => $data['chassis_number'],
                'engine_number' => $data['engine_number'],
                'issue_date' => $data['issue_date'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

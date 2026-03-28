<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_log_in_and_access_dashboard(): void
    {
        Config::set('admin.username', 'admin');
        Config::set('admin.password', 'secret123');

        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $dashboard = $this->withSession(['is_admin_authenticated' => true])->get('/admin/dashboard');

        $dashboard->assertOk();
    }

    public function test_admin_can_update_order_status(): void
    {
        $order = $this->createOrder();

        $response = $this
            ->withSession(['is_admin_authenticated' => true])
            ->post(route('admin.order.updateStatus', $order->id), [
                'status' => 'completed',
                'insurance_link' => 'https://example.com/policy.pdf',
                'tracking_code' => 'VTP123456',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'completed',
            'tracking_code' => 'VTP123456',
        ]);
    }

    private function createOrder(): Order
    {
        $customer = Customer::create([
            'full_name' => 'Nguyen Van A',
            'phone_number' => '0901234567',
            'identity_card' => '012345678901',
        ]);

        $product = Product::create([
            'name' => 'Bao hiem vat chat',
            'category' => 'INSURANCE',
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
        ]);

        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'license_plate' => '51H12345',
            'vehicle_type' => 'O to con',
        ]);

        return Order::create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'product_id' => $product->id,
            'total_amount' => 120000,
            'payment_method' => 'Chuyen khoan VietQR',
            'payment_status' => 'pending',
            'effective_date' => '2026-03-24',
            'receiver_name' => 'Nguyen Van A',
            'receiver_phone' => '0901234567',
            'shipping_address' => 'Ho Chi Minh',
        ]);
    }
}

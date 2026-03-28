<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_order_from_checkout_form(): void
    {
        Storage::fake('public');

        $product = Product::create([
            'name' => 'Bao hiem TNDS',
            'category' => 'INSURANCE',
            'base_price' => 400000,
            'vat_price' => 440000,
            'selling_price' => 450000,
        ]);

        $response = $this->post(route('process.checkout', $product->id), [
            'full_name' => 'Tran Thi B',
            'phone_number' => '0911222333',
            'identity_card' => '079123456789',
            'cavet_image' => UploadedFile::fake()->create('cavet.jpg', 200, 'image/jpeg'),
            'license_plate' => '51H - 123.45',
            'vehicle_type' => 'Ô tô con',
            'plate_color' => 'Trắng',
            'effective_date' => '2026-03-25',
            'receiver_name' => 'Tran Thi B',
            'receiver_phone' => '0911222333',
            'shipping_address' => 'Thu Duc, Ho Chi Minh',
        ]);

        $order = Order::first();

        $response->assertRedirect(route('payment.qr', $order?->id));
        $this->assertDatabaseHas('customers', [
            'identity_card' => '079123456789',
            'full_name' => 'Tran Thi B',
        ]);
        $this->assertDatabaseHas('vehicles', [
            'license_plate' => '51H12345',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order?->id,
            'product_id' => $product->id,
            'payment_status' => 'pending',
        ]);
    }

    public function test_customer_can_create_epass_order_without_effective_date(): void
    {
        Storage::fake('public');

        $product = Product::create([
            'name' => 'Dang ky ePass',
            'category' => 'EPASS',
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
        ]);

        $response = $this->post(route('process.checkout', $product->id), [
            'full_name' => 'Le Van C',
            'phone_number' => '0988777666',
            'identity_card' => '012301230123',
            'cavet_image' => UploadedFile::fake()->create('cavet.jpg', 200, 'image/jpeg'),
            'front_vehicle_image' => UploadedFile::fake()->create('front.jpg', 200, 'image/jpeg'),
            'inspection_image' => UploadedFile::fake()->create('inspection.jpg', 200, 'image/jpeg'),
            'license_plate' => '60K 143.51',
            'vehicle_type' => 'Ô tô con',
            'plate_color' => 'Trắng',
            'receiver_name' => 'Le Van C',
            'receiver_phone' => '0988777666',
            'shipping_address' => 'Bien Hoa',
        ]);

        $order = Order::first();

        $response->assertRedirect(route('payment.qr', $order?->id));
        $this->assertDatabaseHas('orders', [
            'id' => $order?->id,
            'product_id' => $product->id,
            'effective_date' => null,
        ]);
        $this->assertDatabaseHas('vehicles', [
            'license_plate' => '60K14351',
        ]);
        $this->assertNotNull(Order::first()?->vehicle?->registration_image);
    }

    public function test_track_order_normalizes_license_plate(): void
    {
        $product = Product::create([
            'name' => 'Epass',
            'category' => 'EPASS',
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
        ]);

        $this->post(route('process.checkout', $product->id), [
            'full_name' => 'Le Van C',
            'phone_number' => '0988777666',
            'identity_card' => '012301230123',
            'cavet_image' => UploadedFile::fake()->create('cavet.jpg', 200, 'image/jpeg'),
            'front_vehicle_image' => UploadedFile::fake()->create('front.jpg', 200, 'image/jpeg'),
            'inspection_image' => UploadedFile::fake()->create('inspection.jpg', 200, 'image/jpeg'),
            'license_plate' => '60K 143.51',
            'vehicle_type' => 'Ô tô con',
            'plate_color' => 'Trắng',
            'receiver_name' => 'Le Van C',
            'receiver_phone' => '0988777666',
            'shipping_address' => 'Bien Hoa',
        ]);

        $response = $this->get(route('order.track', ['license_plate' => '60K 143.51']));

        $response->assertOk();
        $response->assertSee('60K 143.51');
        $response->assertSee('Epass');
    }

    public function test_checkout_returns_validation_error_when_phone_number_belongs_to_another_customer(): void
    {
        Storage::fake('public');

        Customer::create([
            'full_name' => 'Khach cu',
            'phone_number' => '123',
            'identity_card' => '111111111111',
        ]);

        $product = Product::create([
            'name' => 'Dang ky ePass',
            'category' => 'EPASS',
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
        ]);

        $response = $this->from(route('checkout', $product->id))
            ->post(route('process.checkout', $product->id), [
                'full_name' => 'CTY TNHH XDTM VA SX ME KONG',
                'phone_number' => '123',
                'identity_card' => '2345234',
                'cavet_image' => UploadedFile::fake()->create('cavet.jpg', 200, 'image/jpeg'),
                'front_vehicle_image' => UploadedFile::fake()->create('front.jpg', 200, 'image/jpeg'),
                'inspection_image' => UploadedFile::fake()->create('inspection.jpg', 200, 'image/jpeg'),
                'license_plate' => '60K 143.51',
                'vehicle_type' => 'Ô tô con',
                'plate_color' => 'Trắng',
                'receiver_name' => 'CTY TNHH XDTM VA SX ME KONG',
                'receiver_phone' => '123',
                'shipping_address' => 'Bien Hoa',
            ]);

        $response->assertRedirect(route('checkout', $product->id));
        $response->assertSessionHasErrors('phone_number');
    }
}

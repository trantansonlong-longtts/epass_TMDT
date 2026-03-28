<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_product_dashboard(): void
    {
        $response = $this
            ->withSession(['is_admin_authenticated' => true])
            ->get(route('admin.products.index'));

        $response->assertOk();
        $response->assertSee('Quản lý sản phẩm');
    }

    public function test_admin_can_create_product(): void
    {
        Storage::fake('public');

        $response = $this
            ->withSession(['is_admin_authenticated' => true])
            ->post(route('admin.products.store'), [
                'name' => 'Gói bảo hiểm mới',
                'category' => 'INSURANCE',
                'sort_order' => 5,
                'base_price' => 100000,
                'vat_price' => 110000,
                'selling_price' => 120000,
                'description' => 'Mô tả thử nghiệm',
                'image' => UploadedFile::fake()->create('product.jpg', 128, 'image/jpeg'),
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Gói bảo hiểm mới',
            'category' => 'INSURANCE',
            'sort_order' => 5,
            'is_active' => true,
        ]);
        Storage::disk('public')->assertExists(Product::first()->image);
    }

    public function test_admin_can_update_and_delete_product(): void
    {
        Storage::fake('public');

        $product = Product::create([
            'name' => 'Phí dán thẻ',
            'category' => 'EPASS',
            'sort_order' => 3,
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
            'is_active' => true,
        ]);

        $updateResponse = $this
            ->withSession(['is_admin_authenticated' => true])
            ->put(route('admin.products.update', $product), [
                'name' => 'Phí dán thẻ VIP',
                'category' => 'EPASS',
                'sort_order' => 1,
                'base_price' => 120000,
                'vat_price' => 132000,
                'selling_price' => 135000,
                'description' => 'Đã cập nhật',
                'is_active' => '0',
            ]);

        $updateResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Phí dán thẻ VIP',
            'sort_order' => 1,
            'selling_price' => 135000,
            'is_active' => false,
        ]);

        $toggleResponse = $this
            ->withSession(['is_admin_authenticated' => true])
            ->patch(route('admin.products.toggleVisibility', $product));

        $toggleResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => true,
        ]);

        $deleteResponse = $this
            ->withSession(['is_admin_authenticated' => true])
            ->delete(route('admin.products.destroy', $product));

        $deleteResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_homepage_only_shows_active_products(): void
    {
        Product::create([
            'name' => '5 chỗ - Không kinh doanh (biển trắng)',
            'category' => 'INSURANCE',
            'sort_order' => 2,
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => '5 chỗ - Kinh doanh (biển vàng)',
            'category' => 'INSURANCE',
            'sort_order' => 1,
            'base_price' => 200000,
            'vat_price' => 220000,
            'selling_price' => 240000,
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('5 chỗ');
        $response->assertDontSee('5 chỗ - Kinh doanh (biển vàng)');
    }

    public function test_homepage_respects_sort_order_for_active_products(): void
    {
        Product::create([
            'name' => '7 chỗ - Không kinh doanh (biển trắng)',
            'category' => 'INSURANCE',
            'sort_order' => 2,
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
            'is_active' => true,
        ]);

        Product::create([
            'name' => '5 chỗ - Không kinh doanh (biển trắng)',
            'category' => 'INSURANCE',
            'sort_order' => 1,
            'base_price' => 90000,
            'vat_price' => 99000,
            'selling_price' => 100000,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeInOrder([
            '5 chỗ',
            '7 chỗ',
        ]);
    }

    public function test_admin_can_reorder_products(): void
    {
        $first = Product::create([
            'name' => 'Sản phẩm A',
            'category' => 'EPASS',
            'sort_order' => 1,
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
            'is_active' => true,
        ]);

        $second = Product::create([
            'name' => 'Sản phẩm B',
            'category' => 'EPASS',
            'sort_order' => 2,
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
            'is_active' => true,
        ]);

        $response = $this
            ->withSession(['is_admin_authenticated' => true])
            ->postJson(route('admin.products.reorder'), [
                'product_ids' => [$second->id, $first->id],
            ]);

        $response
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('products', [
            'id' => $second->id,
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $first->id,
            'sort_order' => 2,
        ]);
    }

    public function test_admin_cannot_delete_product_that_has_orders(): void
    {
        $product = Product::create([
            'name' => 'Gói bảo hiểm đang dùng',
            'category' => 'INSURANCE',
            'sort_order' => 1,
            'base_price' => 100000,
            'vat_price' => 110000,
            'selling_price' => 120000,
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'full_name' => 'Khach Hang',
            'identity_card' => '012345678901',
            'phone_number' => '0900000000',
        ]);

        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'license_plate' => '51H12345',
            'vehicle_type' => 'Xe con',
            'plate_color' => 'white',
        ]);

        Order::create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'product_id' => $product->id,
            'total_amount' => 120000,
            'payment_method' => 'Chuyen khoan VietQR',
            'payment_status' => 'pending',
            'effective_date' => now()->toDateString(),
            'receiver_name' => 'Khach Hang',
            'receiver_phone' => '0900000000',
            'shipping_address' => 'TP.HCM',
        ]);

        $response = $this
            ->withSession(['is_admin_authenticated' => true])
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }
}

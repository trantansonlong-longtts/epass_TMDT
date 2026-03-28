<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminHomepageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_homepage_content(): void
    {
        Storage::fake('public');

        $response = $this
            ->withSession(['is_admin_authenticated' => true])
            ->post(route('admin.content.homepage.update'), [
                'hero_kicker' => 'Thong diep moi',
                'hero_title' => 'Tieu de trang chu moi',
                'hero_description' => 'Mo ta da duoc cap nhat boi admin.',
                'hero_primary_cta_text' => 'Xem ngay',
                'hero_secondary_cta_text' => 'Tra cuu ngay',
                'hero_point_1' => 'Diem nhan 1',
                'hero_point_2' => 'Diem nhan 2',
                'hero_point_3' => 'Diem nhan 3',
                'slide_1_eyebrow' => 'Slide 1',
                'slide_1_title' => 'Tieu de slide 1',
                'slide_1_text' => 'Mo ta slide 1',
                'slide_2_eyebrow' => 'Slide 2',
                'slide_2_title' => 'Tieu de slide 2',
                'slide_2_text' => 'Mo ta slide 2',
                'slide_3_eyebrow' => 'Slide 3',
                'slide_3_title' => 'Tieu de slide 3',
                'slide_3_text' => 'Mo ta slide 3',
                'slide_1_image' => UploadedFile::fake()->create('slide-1.jpg', 256, 'image/jpeg'),
                'insurance_kicker' => 'Bao hiem',
                'insurance_title' => 'Bang gia moi',
                'insurance_description' => 'Mo ta bao hiem',
                'epass_kicker' => 'Epass',
                'epass_title' => 'Dich vu moi',
                'epass_description' => 'Mo ta epass',
                'customer_info_title' => 'Khach hang',
                'customer_info_description' => 'Thong tin cho khach',
                'operator_info_title' => 'Van hanh',
                'operator_info_description' => 'Thong tin cho van hanh',
            ]);

        $response->assertRedirect(route('admin.content.homepage.edit'));
        $this->assertDatabaseHas('site_settings', [
            'key' => 'hero_title',
            'value' => 'Tieu de trang chu moi',
        ]);

        $imagePath = SiteSetting::where('key', 'slide_1_image')->value('value');
        Storage::disk('public')->assertExists($imagePath);

        $homepageResponse = $this->get('/');
        $homepageResponse->assertOk();
        $homepageResponse->assertSee('Tieu de trang chu moi');
        $homepageResponse->assertSee('Bang gia moi');
    }
}

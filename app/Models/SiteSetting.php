<?php

namespace App\Models;

use App\Support\UploadUrl;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function homepageDefaults(): array
    {
        return [
            'hero_kicker' => 'Dịch vụ ô tô trực tuyến',
            'hero_title' => 'Chọn đúng gói bảo hiểm hoặc ePass trong một giao diện gọn, dễ hiểu và dễ chốt.',
            'hero_description' => 'Cách trình bày mới ưu tiên sự rõ ràng: phân nhóm ngay từ đầu, giá dễ quét, hồ sơ dễ gửi và trạng thái dễ theo dõi cho cả khách hàng lẫn đội vận hành.',
            'hero_primary_cta_text' => 'Xem bảng giá',
            'hero_secondary_cta_text' => 'Tra cứu hồ sơ hiện tại',
            'hero_point_1' => 'Tách rõ bảo hiểm biển trắng, biển vàng và ePass.',
            'hero_point_2' => 'OCR cavet hỗ trợ điền nhanh, không phải nhập lại nhiều lần.',
            'hero_point_3' => 'Thanh toán VietQR và tra cứu hồ sơ trong cùng một luồng.',
            'slide_1_eyebrow' => 'ePass toàn trình',
            'slide_1_title' => 'Dịch vụ thu phí không dừng và bảo hiểm Bắt buộc cho ôTô',
            'slide_1_text' => 'Khách hàng có thể xem biểu phí, gửi hồ sơ và theo dõi tiến độ trên cùng một hệ thống thay vì phải làm việc rời rạc từng bước.',
            'slide_2_eyebrow' => 'Tiếp nhận trực tuyến',
            'slide_2_title' => 'Làm hồ sơ ePass nhanh hơn với giao diện hiện đại, dễ hiểu và dễ tư vấn.',
            'slide_2_text' => 'Phù hợp cho luồng vận hành cần báo giá nhanh, tiếp nhận ảnh cavet và chuyển khách sang bước thanh toán không bị đứt mạch.',
            'slide_3_eyebrow' => 'Tư vấn chuyên nghiệp',
            'slide_3_title' => 'Phần nhìn đầu trang được làm như một banner dịch vụ thật sự, không còn cảm giác trang nội bộ đơn điệu.',
            'slide_3_text' => 'Giữ tông xanh tin cậy, typography rõ ràng hơn và thêm hình ảnh ePass để tạo cảm giác sản phẩm hoàn chỉnh hơn.',
            'insurance_kicker' => 'Biểu phí bảo hiểm ô tô',
            'insurance_title' => 'Phân chia rõ theo từng nhóm sử dụng xe',
            'insurance_description' => 'Trong mỗi bảng, các gói được sắp xếp theo thứ tự bạn quản lý để đội ngũ tư vấn và khách hàng so sánh nhanh hơn.',
            'epass_kicker' => 'Dịch vụ ePass',
            'epass_title' => 'Nhóm dịch vụ ePass',
            'epass_description' => 'Các gói được trình bày như một danh mục dịch vụ rõ ràng, dễ bấm đăng ký và thuận tiện cho đội ngũ tiếp nhận hồ sơ xử lý ngay.',
            'customer_info_title' => 'Thông tin đủ rõ để ra quyết định mà không phải hỏi lại nhiều lần',
            'customer_info_description' => 'Khách nhìn vào có thể phân biệt đúng nhóm xe, xem giá theo thứ tự hợp lý và chuyển sang bước gửi hồ sơ ngay khi đã chọn được gói phù hợp.',
            'operator_info_title' => 'Báo giá nhanh, tra cứu nhanh, làm việc với khách tự tin hơn',
            'operator_info_description' => 'Giao diện được tổ chức theo cách hỗ trợ tư vấn thực tế: dễ báo giá, dễ đối chiếu và đáng tin hơn khi sử dụng trực tiếp trước mặt khách hàng.',
            'slide_1_image' => null,
            'slide_2_image' => null,
            'slide_3_image' => null,
        ];
    }

    public static function getMany(array $defaults): array
    {
        $stored = static::query()
            ->whereIn('key', array_keys($defaults))
            ->pluck('value', 'key')
            ->all();

        return array_merge($defaults, $stored);
    }

    public static function putMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    public static function homepageContent(): array
    {
        return static::getMany(static::homepageDefaults());
    }

    public static function assetUrlForPath(?string $path, ?string $fallback = null): ?string
    {
        return UploadUrl::forPath($path, $fallback);
    }
}

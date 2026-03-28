@extends('layouts.app')

@section('title', 'Trang chủ - Dịch vụ ePass và bảo hiểm ô tô')

@section('masthead')
@php
    use App\Models\SiteSetting;
    $homepageContent = $homepageContent ?? [];
    $homepageImage = function (?string $storedPath, string $fallbackPath) {
        return SiteSetting::assetUrlForPath($storedPath, $fallbackPath);
    };
    $heroSlides = [
        [
            'image' => $homepageImage($homepageContent['slide_1_image'] ?? null, 'images/brand/carousel1.png'),
            'eyebrow' => $homepageContent['slide_1_eyebrow'] ?? 'ePass toàn trình',
            'title' => $homepageContent['slide_1_title'] ?? 'Dịch vụ thu phí không dừng và bảo hiểm Bắt buộc cho ôTô',
            'text' => $homepageContent['slide_1_text'] ?? 'Khách hàng có thể xem biểu phí, gửi hồ sơ và theo dõi tiến độ trên cùng một hệ thống thay vì phải làm việc rời rạc từng bước.',
        ],
        [
            'image' => $homepageImage($homepageContent['slide_2_image'] ?? null, 'images/brand/carousel2.png'),
            'eyebrow' => $homepageContent['slide_2_eyebrow'] ?? 'Tiếp nhận trực tuyến',
            'title' => $homepageContent['slide_2_title'] ?? 'Làm hồ sơ ePass nhanh hơn với giao diện hiện đại, dễ hiểu và dễ tư vấn.',
            'text' => $homepageContent['slide_2_text'] ?? 'Phù hợp cho luồng vận hành cần báo giá nhanh, tiếp nhận ảnh cavet và chuyển khách sang bước thanh toán không bị đứt mạch.',
        ],
        [
            'image' => $homepageImage($homepageContent['slide_3_image'] ?? null, 'images/brand/carousel3.png'),
            'eyebrow' => $homepageContent['slide_3_eyebrow'] ?? 'Tư vấn chuyên nghiệp',
            'title' => $homepageContent['slide_3_title'] ?? 'Phần nhìn đầu trang được làm như một banner dịch vụ thật sự, không còn cảm giác trang nội bộ đơn điệu.',
            'text' => $homepageContent['slide_3_text'] ?? 'Giữ tông xanh tin cậy, typography rõ ràng hơn và thêm hình ảnh ePass để tạo cảm giác sản phẩm hoàn chỉnh hơn.',
        ],
    ];
@endphp

<section class="app-masthead">
    <div class="container">
        <div id="epassHeroCarousel" class="carousel slide app-masthead-carousel" data-bs-ride="carousel" data-bs-interval="6200">
            <div class="carousel-indicators">
                @foreach($heroSlides as $slide)
                    <button type="button" data-bs-target="#epassHeroCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach($heroSlides as $slide)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="app-masthead-card">
                            <div class="row g-4 align-items-center">
                                <div class="col-lg-5">
                                    <div class="app-kicker mb-3">{{ $slide['eyebrow'] }}</div>
                                    <h2 class="app-masthead-title">{{ $slide['title'] }}</h2>
                                    <p class="app-masthead-text mb-0">{{ $slide['text'] }}</p>
                                </div>
                                <div class="col-lg-7">
                                    <img src="{{ $slide['image'] }}" alt="{{ $slide['eyebrow'] }}" class="img-fluid app-masthead-image">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev app-masthead-control" type="button" data-bs-target="#epassHeroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next app-masthead-control" type="button" data-bs-target="#epassHeroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>
@endsection

@section('content')
@php
    $insuranceProducts = $products->where('category', 'INSURANCE')->values();
    $epassProducts = $products->where('category', 'EPASS')->values();
    $personalInsuranceProducts = $insuranceProducts
        ->filter(fn ($product) => str_contains($product->name, 'Không kinh doanh (biển trắng)'))
        ->values();
    $businessInsuranceProducts = $insuranceProducts
        ->filter(fn ($product) => str_contains($product->name, 'Kinh doanh (biển vàng)'))
        ->values();
    $epassProducts = $epassProducts->values();
    $heroVehicle = $personalInsuranceProducts->first()?->imageUrl
        ?? $businessInsuranceProducts->first()?->imageUrl
        ?? asset('images/catalog/sedan.svg');
@endphp

<div class="container">
    <section class="app-section">
        <div class="app-discovery-bar">
            <a href="#bang-gia-bao-hiem" class="app-discovery-chip is-active">
                <i class="bi bi-shield-check"></i>
                <span>Bảo hiểm ô tô</span>
            </a>
            <a href="#dich-vu-epass" class="app-discovery-chip">
                <i class="bi bi-credit-card-2-front"></i>
                <span>Dán / đổi thẻ ePass</span>
            </a>
            <a href="{{ route('order.track') }}" class="app-discovery-chip">
                <i class="bi bi-search"></i>
                <span>Tra cứu hồ sơ</span>
            </a>
        </div>
    </section>

    <section class="app-section">
        <div class="app-surface app-hero app-home-hero">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="app-kicker mb-3">{{ $homepageContent['hero_kicker'] ?? 'Dịch vụ ô tô trực tuyến' }}</div>
                    <h1 class="display-4 mb-3">{{ $homepageContent['hero_title'] ?? 'Chọn đúng gói bảo hiểm hoặc ePass trong một giao diện gọn, dễ hiểu và dễ chốt.' }}</h1>
                    <p class="fs-5 mb-4 app-home-hero-copy">{{ $homepageContent['hero_description'] ?? 'Cách trình bày mới ưu tiên sự rõ ràng: phân nhóm ngay từ đầu, giá dễ quét, hồ sơ dễ gửi và trạng thái dễ theo dõi cho cả khách hàng lẫn đội vận hành.' }}</p>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="#bang-gia-bao-hiem" class="btn app-btn app-btn-primary">{{ $homepageContent['hero_primary_cta_text'] ?? 'Xem bảng giá' }}</a>
                        <a href="{{ route('order.track') }}" class="btn app-btn app-btn-outline app-btn-soft">{{ $homepageContent['hero_secondary_cta_text'] ?? 'Tra cứu hồ sơ hiện tại' }}</a>
                    </div>

                    <div class="home-hero-points">
                        <div class="home-hero-point">
                            <i class="bi bi-check2-circle"></i>
                            <span>{{ $homepageContent['hero_point_1'] ?? 'Tách rõ bảo hiểm biển trắng, biển vàng và ePass.' }}</span>
                        </div>
                        <div class="home-hero-point">
                            <i class="bi bi-check2-circle"></i>
                            <span>{{ $homepageContent['hero_point_2'] ?? 'OCR cavet hỗ trợ điền nhanh, không phải nhập lại nhiều lần.' }}</span>
                        </div>
                        <div class="home-hero-point">
                            <i class="bi bi-check2-circle"></i>
                            <span>{{ $homepageContent['hero_point_3'] ?? 'Thanh toán VietQR và tra cứu hồ sơ trong cùng một luồng.' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="home-search-card">
                        <div class="home-search-tabs">
                            <button type="button" class="home-search-tab is-active">Bảo hiểm</button>
                            <button type="button" class="home-search-tab">ePass</button>
                            <button type="button" class="home-search-tab">Tra cứu</button>
                        </div>
                        <div class="home-search-grid">
                            <div class="home-search-field">
                                <span class="home-search-label">Nhóm dịch vụ</span>
                                <strong>Bảo hiểm bắt buộc / dán thẻ</strong>
                            </div>
                            <div class="home-search-field">
                                <span class="home-search-label">Nhóm xe</span>
                                <strong>Biển trắng hoặc biển vàng</strong>
                            </div>
                            <div class="home-search-field">
                                <span class="home-search-label">Hồ sơ</span>
                                <strong>Cavet, đăng kiểm, ảnh đầu xe</strong>
                            </div>
                            <a href="#bang-gia-bao-hiem" class="btn app-btn app-btn-primary home-search-button">Bắt đầu</a>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-sm-6">
                                <div class="home-insight-card">
                                    <span>Danh mục hoạt động</span>
                                    <strong>{{ $insuranceProducts->count() }} gói bảo hiểm</strong>
                                    <small>Sắp xếp theo thứ tự bạn quản lý từ dashboard.</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="home-insight-card">
                                    <span>Dịch vụ đi kèm</span>
                                    <strong>{{ $epassProducts->count() }} gói ePass</strong>
                                    <small>Dán thẻ, đổi thẻ và tiếp nhận hồ sơ online.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="app-card step-card home-step-card h-100">
                    <div class="step-number">01</div>
                    <h3 class="h5">Chọn gói phù hợp</h3>
                    <p class="app-muted mb-0">So sánh nhanh mức phí, phân biệt rõ xe không kinh doanh biển trắng và xe kinh doanh biển vàng.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="app-card step-card home-step-card h-100">
                    <div class="step-number">02</div>
                    <h3 class="h5">Điền hồ sơ nhanh bằng AI</h3>
                    <p class="app-muted mb-0">Tải ảnh giấy đăng ký xe để hệ thống gợi ý biển số, số khung, số máy và thông tin chủ xe.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="app-card step-card home-step-card h-100">
                    <div class="step-number">03</div>
                    <h3 class="h5">Thanh toán và tra cứu</h3>
                    <p class="app-muted mb-0">Khách hàng xác nhận chuyển khoản, sau đó có thể theo dõi tình trạng đơn và ấn chỉ trên một trang riêng.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section" id="bang-gia-bao-hiem">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <div class="app-kicker mb-2">{{ $homepageContent['insurance_kicker'] ?? 'Biểu phí bảo hiểm ô tô' }}</div>
                    <h2 class="app-heading mb-2">{{ $homepageContent['insurance_title'] ?? 'Phân chia rõ theo từng nhóm sử dụng xe' }}</h2>
                    <p class="app-muted mb-0">{{ $homepageContent['insurance_description'] ?? 'Trong mỗi bảng, các gói được sắp xếp theo thứ tự bạn quản lý để đội ngũ tư vấn và khách hàng so sánh nhanh hơn.' }}</p>
                </div>
                <span class="app-pill"><i class="bi bi-card-checklist"></i> Tổng gói bảo hiểm: {{ $insuranceProducts->count() }}</span>
            </div>

        <div class="row g-4">
            <div class="col-xl-6">
                <div class="app-surface pricing-board pricing-board-personal h-100">
                    <div class="pricing-board-header">
                        <div class="pricing-board-glow"></div>
                        <span class="pricing-board-tag"><i class="bi bi-shield-check"></i> Biển trắng</span>
                        <h3 class="pricing-board-title">Không kinh doanh (biển trắng)</h3>
                        <p class="pricing-board-subtitle">Dành cho xe cá nhân, xe gia đình và các trường hợp không hoạt động kinh doanh vận tải.</p>
                    </div>
                    <div class="table-responsive pricing-board-table">
                        <table class="table app-pricing-table app-pricing-table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>Loại xe</th>
                                    <th>Giá gốc</th>
                                    <th>Giá bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personalInsuranceProducts as $product)
                                    <tr class="pricing-row-link" onclick="window.location='{{ route('checkout', $product->id) }}'">
                                        <td>
                                            <div class="pricing-vehicle">
                                                <span class="pricing-vehicle-mark">
                                                    @if($product->imageUrl)
                                                        <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="pricing-vehicle-image">
                                                    @endif
                                                </span>
                                                <div class="pricing-vehicle-meta">
                                                    <strong>{{ str_replace(' - Không kinh doanh (biển trắng)', '', $product->name) }}</strong>
                                                    <small>Xe cá nhân, gia đình hoặc không kinh doanh vận tải</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="pricing-price">{{ number_format($product->vat_price, 0, ',', '.') }}&nbsp;đ</span></td>
                                        <td><span class="pricing-price pricing-price-accent">{{ number_format($product->selling_price, 0, ',', '.') }}&nbsp;đ</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="app-surface pricing-board pricing-board-business h-100">
                    <div class="pricing-board-header">
                        <div class="pricing-board-glow"></div>
                        <span class="pricing-board-tag"><i class="bi bi-lightning-charge"></i> Biển vàng</span>
                        <h3 class="pricing-board-title">Kinh doanh (biển vàng)</h3>
                        <p class="pricing-board-subtitle">Dành cho xe vận tải, xe dịch vụ và nhóm phương tiện cần biểu phí kinh doanh chuẩn xác hơn.</p>
                    </div>
                    <div class="table-responsive pricing-board-table">
                        <table class="table app-pricing-table app-pricing-table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>Loại xe</th>
                                    <th>Giá gốc</th>
                                    <th>Giá bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($businessInsuranceProducts as $product)
                                    <tr class="pricing-row-link" onclick="window.location='{{ route('checkout', $product->id) }}'">
                                        <td>
                                            <div class="pricing-vehicle">
                                                <span class="pricing-vehicle-mark">
                                                    @if($product->imageUrl)
                                                        <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="pricing-vehicle-image">
                                                    @endif
                                                </span>
                                                <div class="pricing-vehicle-meta">
                                                    <strong>{{ str_replace(' - Kinh doanh (biển vàng)', '', $product->name) }}</strong>
                                                    <small>Xe vận tải, xe dịch vụ hoặc kinh doanh vận chuyển</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="pricing-price">{{ number_format($product->vat_price, 0, ',', '.') }}&nbsp;đ</span></td>
                                        <td><span class="pricing-price pricing-price-accent">{{ number_format($product->selling_price, 0, ',', '.') }}&nbsp;đ</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($epassProducts->isNotEmpty())
        <section class="app-section" id="dich-vu-epass">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                    <div>
                        <div class="app-kicker mb-2">{{ $homepageContent['epass_kicker'] ?? 'Dịch vụ ePass' }}</div>
                        <h2 class="app-heading mb-2">{{ $homepageContent['epass_title'] ?? 'Nhóm dịch vụ ePass' }}</h2>
                        <p class="app-muted mb-0">{{ $homepageContent['epass_description'] ?? 'Các gói được trình bày như một danh mục dịch vụ rõ ràng, dễ bấm đăng ký và thuận tiện cho đội ngũ tiếp nhận hồ sơ xử lý ngay.' }}</p>
                    </div>
                    <span class="app-pill"><i class="bi bi-credit-card-2-front"></i> Tổng gói ePass: {{ $epassProducts->count() }}</span>
            </div>

            <div class="row g-4">
                @foreach($epassProducts as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="app-card service-card epass-highlight {{ $loop->first ? 'service-card-highlight' : '' }}">
                            @if($product->image)
                                <img
                                    src="{{ $product->imageUrl }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid rounded-4 border mb-2"
                                    style="height: 200px; object-fit: cover; width: 100%;"
                                >
                            @endif
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <span class="service-badge service-badge-epass">ePass</span>
                                <i class="bi bi-credit-card-2-front fs-4 text-secondary"></i>
                            </div>

                            <div>
                                <h3 class="h4 mb-2">{{ $product->name }}</h3>
                                <p class="app-muted mb-0">{{ $product->description ?: 'Dịch vụ hỗ trợ xử lý hồ sơ và dán thẻ ePass.' }}</p>
                            </div>

                            <div>
                                <div class="service-price">{{ number_format($product->selling_price, 0, ',', '.') }} đ</div>
                                <div class="service-meta mt-3">
                                    <div><strong>Phí chuẩn:</strong> {{ number_format($product->base_price, 0, ',', '.') }} đ</div>
                                    <div><strong>Phí + VAT:</strong> {{ number_format($product->vat_price, 0, ',', '.') }} đ</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-auto">
                                <a href="{{ route('checkout', $product->id) }}" class="btn app-btn app-btn-primary">Đăng ký ngay</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="app-section">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="app-card info-card h-100">
                    <div class="app-kicker mb-2">Dành cho khách hàng</div>
                    <h3 class="h4 mb-3">{{ $homepageContent['customer_info_title'] ?? 'Thông tin đủ rõ để ra quyết định mà không phải hỏi lại nhiều lần' }}</h3>
                    <p class="app-muted mb-0">{{ $homepageContent['customer_info_description'] ?? 'Khách nhìn vào có thể phân biệt đúng nhóm xe, xem giá theo thứ tự hợp lý và chuyển sang bước gửi hồ sơ ngay khi đã chọn được gói phù hợp.' }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="app-card info-card h-100">
                    <div class="app-kicker mb-2">Dành cho đội vận hành</div>
                    <h3 class="h4 mb-3">{{ $homepageContent['operator_info_title'] ?? 'Báo giá nhanh, tra cứu nhanh, làm việc với khách tự tin hơn' }}</h3>
                    <p class="app-muted mb-0">{{ $homepageContent['operator_info_description'] ?? 'Giao diện được tổ chức theo cách hỗ trợ tư vấn thực tế: dễ báo giá, dễ đối chiếu và đáng tin hơn khi sử dụng trực tiếp trước mặt khách hàng.' }}</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

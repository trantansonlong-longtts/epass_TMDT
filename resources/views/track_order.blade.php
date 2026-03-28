@extends('layouts.app')

@section('title', 'Tra cứu đơn hàng')

@section('content')
<div class="container">
    <section class="app-section">
        <div class="app-surface app-hero app-track-hero">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="app-kicker mb-2">Theo dõi tiến độ hồ sơ</div>
                    <h1 class="h2 mb-3">Tra cứu tình trạng đơn hàng theo biển số xe</h1>
                    <p class="app-muted mb-0">Nhập biển số để xem lịch sử hồ sơ, trạng thái thanh toán và thông tin ấn chỉ đã cấp. Trang này phù hợp để khách tự theo dõi mà không cần gọi hỏi thủ công.</p>
                </div>
                <div class="col-lg-4">
                    <div class="app-card info-card">
                        <div class="app-muted mb-2">Gợi ý tìm kiếm</div>
                        <div class="service-meta">
                            <div>Nhập biển số gần đúng, hệ thống sẽ tự chuẩn hóa khoảng trắng.</div>
                            <div>Kết quả được sắp xếp theo đơn mới nhất.</div>
                            <div>Nếu đơn đã hoàn thành, link ấn chỉ và mã vận đơn sẽ hiển thị tại đây.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="app-card track-search-card track-search-shell">
            <form action="{{ route('order.track') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-9">
                        <label class="form-label">Biển số xe</label>
                        <input type="text" name="license_plate" class="form-control form-control-lg" placeholder="VD: 60K-143.51" value="{{ $licensePlate }}" required>
                    </div>
                    <div class="col-lg-3 d-grid">
                        <button type="submit" class="btn app-btn app-btn-primary">Tra cứu ngay</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @if($searched)
        <section class="app-section">
            @if(count($orders) > 0)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <span class="app-pill"><i class="bi bi-search"></i> Tìm thấy {{ count($orders) }} đơn cho biển số {{ $licensePlate }}</span>
                    <a href="{{ route('order.track', ['license_plate' => $licensePlate]) }}" class="btn app-btn app-btn-outline">Làm mới trạng thái</a>
                </div>

                <div class="row g-4">
                    @foreach($orders as $order)
                        <div class="col-12">
                            <div class="app-card order-card">
                                <div class="order-card-header mb-3">
                                    <div>
                                        <div class="app-muted mb-1">Mã đơn hàng</div>
                                        <h2 class="h4 mb-0">#{{ $order->id }}</h2>
                                    </div>
                                    <div>
                                        @if($order->payment_status == 'pending')
                                            <span class="order-status pending">Chờ thanh toán</span>
                                        @elseif($order->payment_status == 'pending_verification')
                                            <span class="order-status pending_verification">Đang kiểm tra giao dịch</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="order-status paid">Đã thanh toán, chờ cấp ấn chỉ</span>
                                        @elseif($order->payment_status == 'completed')
                                            <span class="order-status completed">Hoàn thành, đã gửi ấn chỉ</span>
                                        @else
                                            <span class="order-status pending">{{ $order->payment_status }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="order-card-meta mb-3">
                                    <span><strong>Sản phẩm:</strong> {{ $order->product->name }}</span>
                                    <span><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
                                    <span><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    <span><strong>Hiệu lực:</strong> {{ optional($order->effective_date)->format('d/m/Y') }}</span>
                                </div>

                                @if($order->payment_status == 'completed')
                                    <div class="app-surface p-4">
                                        <div class="app-kicker mb-2">Thông tin bàn giao</div>
                                        <h3 class="h5 mb-3">Ấn chỉ đã sẵn sàng</h3>
                                        <div class="service-meta">
                                            @if($order->insurance_link)
                                                <div>
                                                    <strong>Bản điện tử:</strong>
                                                    <a href="{{ $order->insurance_link }}" target="_blank" class="ms-2">Mở file / tải xuống</a>
                                                </div>
                                            @endif

                                            @if($order->tracking_code)
                                                <div class="d-flex flex-wrap align-items-center gap-2">
                                                    <strong>Vận đơn:</strong>
                                                    <span class="app-pill">{{ $order->tracking_code }}</span>
                                                    <button type="button" class="btn btn-sm btn-light border rounded-circle" onclick="copyTrackingCode('{{ $order->tracking_code }}', this)" title="Copy mã vận đơn">
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                    <a href="https://viettelpost.com.vn/tra-cuu-hanh-trinh-don/{{ $order->tracking_code }}" target="_blank">Tra cứu ViettelPost</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <script>
                    setTimeout(function() {
                        window.location.reload();
                    }, 30000);
                </script>
                <script>
                    function copyTrackingCode(text, buttonElement) {
                        navigator.clipboard.writeText(text).then(function() {
                            let originalHTML = buttonElement.innerHTML;
                            buttonElement.innerHTML = '<i class="bi bi-clipboard-check-fill text-success"></i>';

                            setTimeout(function() {
                                buttonElement.innerHTML = originalHTML;
                            }, 2000);
                        }).catch(function() {
                            alert('Trình duyệt của bạn không hỗ trợ copy tự động.');
                        });
                    }
                </script>
            @else
                <div class="app-card info-card text-center">
                    <div class="app-kicker mb-2">Chưa có kết quả</div>
                    <h2 class="h4 mb-3">Không tìm thấy hồ sơ cho biển số {{ $licensePlate }}</h2>
                    <p class="app-muted mb-0">Bạn hãy kiểm tra lại ký tự biển số hoặc thử tra cứu sau khi hệ thống đã ghi nhận đơn hàng.</p>
                </div>
            @endif
        </section>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Thanh toán VietQR')

@section('content')
<div class="container">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">
            <div class="app-surface app-hero text-center">
                <div class="app-kicker mb-2">Thanh toán đơn hàng</div>
                <h1 class="h2 mb-3">Đơn #{{ $order->id }} đã sẵn sàng để chuyển khoản</h1>
                <p class="app-muted mb-0">Quét mã QR bằng ứng dụng ngân hàng để thanh toán chính xác số tiền và nội dung. Sau đó bấm xác nhận để hệ thống chuyển đơn sang trạng thái chờ kiểm tra.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center mt-1">
        <div class="col-lg-5">
            <div class="app-card info-card text-center h-100 payment-qr-card">
                <div class="app-muted mb-2">Tổng thanh toán</div>
                <div class="service-price mb-3">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</div>

                @if($qrUrl)
                    <img
                        src="{{ $qrUrl }}"
                        alt="Mã VietQR"
                        class="img-fluid border rounded-4 p-2 bg-white shadow-sm mb-3 payment-qr-image"
                        onerror="this.classList.add('d-none'); document.getElementById('qrFallback').classList.remove('d-none');"
                    >
                    <p class="app-muted mb-3">Mã QR đã chứa sẵn số tiền và nội dung chuyển khoản cho đơn hàng này.</p>
                    <div id="qrFallback" class="app-upload-box text-start d-none">
                        <p class="mb-2"><strong>Ngân hàng:</strong> {{ $bankCode ?: 'Chưa cấu hình' }}</p>
                        <p class="mb-2"><strong>Số tài khoản:</strong> {{ $accountNo ?: 'Chưa cấu hình' }}</p>
                        <p class="mb-2"><strong>Chủ tài khoản:</strong> {{ $accountName ?: 'Chưa cấu hình' }}</p>
                        <p class="mb-0"><strong>Nội dung:</strong> {{ $orderInfo }}</p>
                    </div>
                @else
                    <div class="app-upload-box text-start">
                        <p class="mb-2"><strong>Ngân hàng:</strong> {{ $bankCode ?: 'Chưa cấu hình' }}</p>
                        <p class="mb-2"><strong>Số tài khoản:</strong> {{ $accountNo ?: 'Chưa cấu hình' }}</p>
                        <p class="mb-2"><strong>Chủ tài khoản:</strong> {{ $accountName ?: 'Chưa cấu hình' }}</p>
                        <p class="mb-0"><strong>Nội dung:</strong> {{ $orderInfo }}</p>
                    </div>
                @endif

                <div class="app-upload-box text-start mt-3">
                    <p class="mb-2"><strong>Mã thanh toán:</strong> {{ $orderInfo }}</p>
                    <p class="mb-0"><strong>Số tài khoản nhận:</strong> {{ $accountNo ?: 'Chưa cấu hình' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="app-card info-card h-100 d-flex flex-column">
                <span class="app-pill mb-3"><i class="bi bi-info-circle"></i> Sau khi thanh toán</span>
                <div class="service-meta mb-4">
                    <div>Hệ thống sẽ ghi nhận yêu cầu xác minh giao dịch.</div>
                    <div>Admin kiểm tra tiền vào và cập nhật trạng thái đơn.</div>
                    <div>Bạn có thể tra cứu lại tiến độ theo biển số xe ngay sau đó.</div>
                </div>

                <form action="{{ route('payment.confirm', $order->id) }}" method="POST" class="mt-auto d-grid">
                    @csrf
                    <button type="submit" class="btn app-btn app-btn-primary">Tôi đã chuyển khoản thành công</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

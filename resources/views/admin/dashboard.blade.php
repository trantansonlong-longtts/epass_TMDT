@extends('layouts.app')

@section('title', 'Dashboard quản trị')

@section('content')
@php
    $pendingCount = $orders->where('payment_status', 'pending')->count();
    $verifyingCount = $orders->where('payment_status', 'pending_verification')->count();
    $paidCount = $orders->where('payment_status', 'paid')->count();
    $completedCount = $orders->where('payment_status', 'completed')->count();
@endphp

<div class="container">
    <section class="app-section">
        <div class="app-surface app-hero app-admin-hero app-dashboard-hero">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="app-kicker mb-2">Khu vực quản trị đơn hàng</div>
                    <h1 class="h2 mb-3">Theo dõi toàn bộ hồ sơ bảo hiểm và ePass trên một dashboard.</h1>
                    <p class="app-muted mb-0">Màn hình này được tối ưu để admin kiểm tra ảnh cavet, chỉnh sửa thông tin người mua, cập nhật trạng thái thanh toán và bàn giao ấn chỉ ngay trong cùng một luồng.</p>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <a href="{{ route('admin.content.homepage.edit') }}" class="btn app-btn app-btn-outline">Nội dung trang chủ</a>
                        <a href="{{ route('admin.products.index') }}" class="btn app-btn app-btn-outline">Quản lý sản phẩm</a>
                        <a href="{{ url('/') }}" class="btn app-btn app-btn-outline">Về trang khách</a>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn app-btn app-btn-accent">Đăng xuất</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">Chờ thanh toán</div>
                    <span class="admin-summary-number">{{ $pendingCount }}</span>
                    <small>Đơn mới chưa xác nhận chuyển khoản</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">Chờ xác minh</div>
                    <span class="admin-summary-number">{{ $verifyingCount }}</span>
                    <small>Đã bấm xác nhận chuyển khoản</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">Đã nhận tiền</div>
                    <span class="admin-summary-number">{{ $paidCount }}</span>
                    <small>Đang xử lý cấp phát hồ sơ</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">Hoàn thành</div>
                    <span class="admin-summary-number">{{ $completedCount }}</span>
                    <small>Đã gửi ấn chỉ cho khách</small>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="app-card dashboard-toolbar">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="app-kicker mb-2">Danh sách đơn</div>
                    <h2 class="h4 mb-0">Tổng cộng {{ $orders->count() }} hồ sơ</h2>
                </div>
                <span class="app-pill"><i class="bi bi-clock-history"></i> Ưu tiên xử lý từ đơn mới nhất</span>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="app-surface app-table-wrap app-dashboard-table-shell">
            <div class="table-responsive">
                <table class="table app-pricing-table align-middle order-dashboard-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Khách hàng</th>
                            <th>Phương tiện</th>
                            <th>Sản phẩm</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td data-label="Mã đơn"><strong>#{{ $order->id }}</strong></td>
                                <td data-label="Ngày đặt">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td data-label="Khách hàng">
                                    <strong>{{ $order->customer->full_name ?? 'N/A' }}</strong><br>
                                    <span class="app-muted">{{ $order->customer->phone_number ?? 'N/A' }}</span>
                                </td>
                                <td data-label="Phương tiện">
                                    <strong>{{ $order->vehicle->license_plate ?? 'N/A' }}</strong><br>
                                    <span class="app-muted">{{ $order->vehicle->vehicle_type ?? '' }}</span>
                                </td>
                                <td data-label="Sản phẩm">
                                    <strong>{{ $order->product->name ?? 'N/A' }}</strong><br>
                                    <span class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                                </td>
                                <td data-label="Trạng thái">
                                    <span class="order-status {{ $order->payment_status }}">{{ $order->payment_status }}</span>
                                </td>
                                <td class="text-end" data-label="Thao tác">
                                    <button type="button" class="btn app-btn app-btn-outline app-btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                        Xem chi tiết
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Chưa có đơn hàng nào trong hệ thống.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @foreach($orders as $order)
        @php($isEpassOrder = ($order->product->category ?? null) === 'EPASS')
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f766e, #0b4f4b);">
                        <h5 class="modal-title fw-bold">Chi tiết đơn #{{ $order->id }} - {{ $order->product->name ?? '' }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="app-card info-card h-100">
                                    <div class="app-kicker mb-2">Thông tin phương tiện</div>
                                    <div class="service-meta">
                                        <div><strong>Biển số:</strong> {{ $order->vehicle->license_plate ?? 'N/A' }}</div>
                                        <div><strong>Loại xe:</strong> {{ $order->vehicle->vehicle_type ?? 'N/A' }}</div>
                                        <div><strong>Số khung:</strong> {{ $order->vehicle->chassis_number ?? 'N/A' }}</div>
                                        <div><strong>Số máy:</strong> {{ $order->vehicle->engine_number ?? 'N/A' }}</div>
                                        <div><strong>Chủ xe:</strong> {{ $order->vehicle->owner_name ?? 'N/A' }}</div>
                                        <div><strong>Địa chỉ:</strong> {{ $order->vehicle->owner_address ?? 'N/A' }}</div>
                                        <div><strong>Màu biển:</strong> {{ $order->vehicle->plate_color ?? 'N/A' }}</div>
                                        <div><strong>Trọng tải:</strong> {{ $order->vehicle->payload ?? 'N/A' }}</div>
                                        <div><strong>Số chỗ:</strong> {{ $order->vehicle->seat_capacity ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="app-card info-card h-100">
                                    <div class="app-kicker mb-2">Người mua và giao nhận</div>
                                    <div class="service-meta">
                                        <div><strong>Người mua:</strong> {{ $order->customer->full_name ?? 'N/A' }}</div>
                                        <div><strong>SĐT người mua:</strong> {{ $order->customer->phone_number ?? 'N/A' }}</div>
                                        @unless($isEpassOrder)
                                            <div><strong>Hiệu lực BH:</strong> {{ $order->effective_date ? $order->effective_date->format('d/m/Y') : 'N/A' }}</div>
                                        @endunless
                                        <div><strong>Người nhận:</strong> {{ $order->receiver_name ?? 'N/A' }}</div>
                                        <div><strong>SĐT nhận:</strong> {{ $order->receiver_phone ?? 'N/A' }}</div>
                                        <div><strong>Địa chỉ giao:</strong> {{ $order->shipping_address ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                            @if($isEpassOrder)
                                <div class="col-lg-6">
                                    <div class="app-card info-card h-100">
                                        <div class="app-kicker mb-2">Ảnh Giấy đăng ký xe</div>
                                        @if($order->vehicle && $order->vehicle->registration_image)
                                            <a href="{{ $order->vehicle->registration_image_url }}" target="_blank" class="d-inline-block">
                                                <img src="{{ $order->vehicle->registration_image_url }}" alt="Ảnh Giấy đăng ký xe" class="img-fluid rounded-4 shadow-sm" style="max-height: 360px; object-fit: contain;">
                                            </a>
                                        @else
                                            <p class="app-muted mb-0">Khách hàng chưa tải ảnh giấy đăng ký xe.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="app-card info-card h-100">
                                        <div class="app-kicker mb-2">Ảnh đầu xe</div>
                                        @if($order->vehicle && $order->vehicle->front_vehicle_image)
                                            <a href="{{ $order->vehicle->front_vehicle_image_url }}" target="_blank" class="d-inline-block">
                                                <img src="{{ $order->vehicle->front_vehicle_image_url }}" alt="Ảnh đầu xe" class="img-fluid rounded-4 shadow-sm" style="max-height: 360px; object-fit: contain;">
                                            </a>
                                        @else
                                            <p class="app-muted mb-0">Khách hàng chưa tải ảnh đầu xe.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="app-card info-card h-100">
                                        <div class="app-kicker mb-2">Ảnh giấy đăng kiểm</div>
                                        @if($order->vehicle && $order->vehicle->inspection_image)
                                            <a href="{{ $order->vehicle->inspection_image_url }}" target="_blank" class="d-inline-block">
                                                <img src="{{ $order->vehicle->inspection_image_url }}" alt="Ảnh giấy đăng kiểm" class="img-fluid rounded-4 shadow-sm" style="max-height: 360px; object-fit: contain;">
                                            </a>
                                        @else
                                            <p class="app-muted mb-0">Khách hàng chưa tải ảnh giấy đăng kiểm.</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="col-12">
                                    <div class="app-card info-card">
                                        <div class="app-kicker mb-2">Ảnh cavet gốc</div>
                                        @if($order->vehicle && $order->vehicle->registration_image)
                                            <a href="{{ $order->vehicle->registration_image_url }}" target="_blank" class="d-inline-block">
                                                <img src="{{ $order->vehicle->registration_image_url }}" alt="Ảnh Cà Vẹt" class="img-fluid rounded-4 shadow-sm" style="max-height: 420px; object-fit: contain;">
                                            </a>
                                        @else
                                            <p class="app-muted mb-0">Khách hàng chưa tải ảnh cavet.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer bg-light d-block">
                        <div class="d-flex flex-wrap justify-content-between gap-2 border-bottom pb-3 mb-3">
                            <button type="button" class="btn app-btn app-btn-outline" data-bs-toggle="modal" data-bs-target="#editModal{{ $order->id }}">
                                Chỉnh sửa thông tin
                            </button>
                            <button type="button" class="btn app-btn app-btn-outline" data-bs-dismiss="modal">Đóng</button>
                        </div>

                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Trạng thái đơn hàng</label>
                                    <select name="status" class="form-select" id="status_select_{{ $order->id }}" onchange="toggleFulfillmentFields({{ $order->id }})">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                        <option value="pending_verification" {{ $order->payment_status == 'pending_verification' ? 'selected' : '' }}>Đang chờ xác minh</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã nhận tiền</option>
                                        <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    </select>
                                </div>
                            </div>

                            <div class="app-card info-card mt-3 {{ $order->payment_status == 'completed' ? '' : 'd-none' }}" id="fulfillment_fields_{{ $order->id }}">
                                <div class="app-kicker mb-2">Bàn giao ấn chỉ</div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Link bảo hiểm điện tử</label>
                                        <input type="url" name="insurance_link" class="form-control" value="{{ $order->insurance_link }}" placeholder="https://...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mã vận đơn</label>
                                        <input type="text" name="tracking_code" class="form-control text-uppercase" value="{{ $order->tracking_code }}" placeholder="VD: VTP123456789">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn app-btn app-btn-primary">Lưu trạng thái và giao nhận</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4">
                    <form action="{{ route('admin.order.updateDetails', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-header text-dark" style="background: linear-gradient(135deg, #f4a261, #ffd29d);">
                            <h5 class="modal-title fw-bold">Chỉnh sửa đơn #{{ $order->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Biển số xe</label>
                                    <input type="text" name="license_plate" class="form-control" value="{{ $order->vehicle->license_plate ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Số khung</label>
                                    <input type="text" name="chassis_number" class="form-control text-uppercase" value="{{ $order->vehicle->chassis_number ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Số máy</label>
                                    <input type="text" name="engine_number" class="form-control text-uppercase" value="{{ $order->vehicle->engine_number ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Chủ xe</label>
                                    <input type="text" name="owner_name" class="form-control" value="{{ $order->vehicle->owner_name ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Loại xe</label>
                                    <input type="text" name="vehicle_type" class="form-control" value="{{ $order->vehicle->vehicle_type ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Địa chỉ đăng ký</label>
                                    <input type="text" name="owner_address" class="form-control" value="{{ $order->vehicle->owner_address ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ $isEpassOrder ? 'Ngày hiệu lực (nếu có)' : 'Ngày hiệu lực' }}</label>
                                    <input type="date" name="effective_date" class="form-control" value="{{ $order->effective_date ? $order->effective_date->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tên người mua</label>
                                    <input type="text" name="full_name" class="form-control" value="{{ $order->customer->full_name ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">SĐT người mua</label>
                                    <input type="text" name="customer_phone" class="form-control" value="{{ $order->customer->phone_number ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ $isEpassOrder ? 'Người nhận thẻ' : 'Người nhận ấn chỉ' }}</label>
                                    <input type="text" name="receiver_name" class="form-control" value="{{ $order->receiver_name ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">SĐT nhận</label>
                                    <input type="text" name="receiver_phone" class="form-control" value="{{ $order->receiver_phone ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Địa chỉ giao</label>
                                    <input type="text" name="shipping_address" class="form-control" value="{{ $order->shipping_address ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn app-btn app-btn-outline" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn app-btn app-btn-primary">Lưu thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    function toggleFulfillmentFields(orderId) {
        var status = document.getElementById('status_select_' + orderId).value;
        var fields = document.getElementById('fulfillment_fields_' + orderId);

        if (status === 'completed') {
            fields.classList.remove('d-none');
        } else {
            fields.classList.add('d-none');
        }
    }
</script>
@endsection

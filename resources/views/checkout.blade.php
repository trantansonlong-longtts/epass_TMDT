@extends('layouts.app')

@section('title', 'Đăng ký dịch vụ')

@section('content')
@php($isEpass = $product->category === 'EPASS')
<div class="container">
    <section class="app-section">
        <div class="app-surface app-hero app-admin-hero app-booking-hero">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="app-kicker mb-2">Đăng ký dịch vụ</div>
                    <h1 class="h2 mb-3">{{ $product->name }}</h1>
                    <p class="app-muted mb-0">
                        @if($isEpass)
                            Điền thông tin xe và người nhận, tải ảnh đầu xe cùng giấy đăng kiểm để nhân viên tiếp nhận hồ sơ, add xe lên hệ thống và kích hoạt thẻ.
                        @else
                            Điền hồ sơ theo từng bước, tải ảnh giấy đăng ký xe để hệ thống hỗ trợ nhận diện thông tin, sau đó chuyển sang màn hình VietQR để hoàn tất thanh toán.
                        @endif
                    </p>
                    <div class="booking-hero-points">
                        <span><i class="bi bi-check2-circle"></i> Gửi hồ sơ trong một form duy nhất</span>
                        <span><i class="bi bi-check2-circle"></i> AI hỗ trợ đọc cavet để nhập nhanh</span>
                        <span><i class="bi bi-check2-circle"></i> Chuyển thẳng sang thanh toán VietQR</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="app-card info-card home-insight-card booking-price-card">
                        <div class="app-muted mb-2">Mức phí đang áp dụng</div>
                        <div class="service-price mb-2">{{ number_format($product->selling_price, 0, ',', '.') }} VNĐ</div>
                        <div class="service-meta">
                            <div><strong>Phí chuẩn:</strong> {{ number_format($product->base_price, 0, ',', '.') }} đ</div>
                            <div><strong>Phí + VAT:</strong> {{ number_format($product->vat_price, 0, ',', '.') }} đ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="app-card app-form-card app-booking-form-card">
                <form action="{{ route('process.checkout', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <section class="app-form-section">
                        <div class="app-form-section-title"><span>1</span> Thông tin người mua</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" class="form-control" required placeholder="VD: Nguyễn Văn A">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="form-control" required placeholder="VD: 0901234567">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Số CMND / CCCD</label>
                                <input type="text" name="identity_card" value="{{ old('identity_card') }}" class="form-control" required placeholder="Nhập số giấy tờ tuỳ thân">
                            </div>
                        </div>
                    </section>

                    <section class="app-form-section">
                        <div class="app-form-section-title"><span>2</span> Thông tin phương tiện</div>

                        <div class="app-upload-box mb-4">
                            <label class="form-label fw-semibold">Tải ảnh giấy đăng ký xe để hệ thống hỗ trợ nhận diện</label>
                            <input class="form-control" type="file" id="upload-cavet" name="cavet_image" accept="image/*" required>
                            <div id="ai-loading" class="text-primary mt-3" style="display: none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Hệ thống đang quét thông tin từ ảnh cavet...
                            </div>
                        </div>

                        @if($isEpass)
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="app-upload-box h-100">
                                        <label class="form-label fw-semibold">Tải hình đầu xe</label>
                                        <input class="form-control" type="file" name="front_vehicle_image" accept="image/*" required>
                                        <div class="app-muted small mt-2">Ảnh rõ đầu xe để nhân viên đối chiếu khi add xe và kích hoạt thẻ.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="app-upload-box h-100">
                                        <label class="form-label fw-semibold">Tải hình giấy đăng kiểm</label>
                                        <input class="form-control" type="file" name="inspection_image" accept="image/*" required>
                                        <div class="app-muted small mt-2">Ảnh rõ thông tin đăng kiểm để hỗ trợ nhập xe lên hệ thống ePass.</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Biển số xe</label>
                                <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" class="form-control" required placeholder="VD: 51H-123.45">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số khung</label>
                                <input type="text" name="chassis_number" id="chassis_number" value="{{ old('chassis_number') }}" class="form-control" placeholder="Tự động điền">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số máy</label>
                                <input type="text" name="engine_number" id="engine_number" value="{{ old('engine_number') }}" class="form-control" placeholder="Tự động điền">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tên chủ xe</label>
                                <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" class="form-control" placeholder="Tự động điền hoặc nhập tay">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Địa chỉ trên đăng ký</label>
                                <input type="text" name="owner_address" id="owner_address" value="{{ old('owner_address') }}" class="form-control" placeholder="Tự động điền hoặc nhập tay">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Loại xe</label>
                                <select name="vehicle_type" id="vehicle_type" class="form-select" required>
                                    <option value="">-- Chọn loại xe --</option>
                                    <option value="Ô tô con" {{ old('vehicle_type') == 'Ô tô con' ? 'selected' : '' }}>Ô tô con</option>
                                    <option value="Ô tô tải" {{ old('vehicle_type') == 'Ô tô tải' ? 'selected' : '' }}>Ô tô tải</option>
                                    <option value="Ô tô khách" {{ old('vehicle_type') == 'Ô tô khách' ? 'selected' : '' }}>Ô tô khách</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Màu biển</label>
                                <select name="plate_color" id="plate_color" class="form-select" required>
                                    <option value="">-- Chọn màu biển --</option>
                                    <option value="Trắng" {{ old('plate_color') == 'Trắng' ? 'selected' : '' }}>Biển trắng</option>
                                    <option value="Vàng" {{ old('plate_color') == 'Vàng' ? 'selected' : '' }}>Biển vàng</option>
                                    <option value="Xanh" {{ old('plate_color') == 'Xanh' ? 'selected' : '' }}>Biển xanh</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Trọng tải</label>
                                <input type="text" name="payload" id="payload" value="{{ old('payload') }}" class="form-control" placeholder="Nếu có">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Số chỗ</label>
                                <input type="number" name="seat_capacity" id="seat_capacity" value="{{ old('seat_capacity') }}" class="form-control" placeholder="Nếu có">
                            </div>
                        </div>
                    </section>

                    <section class="app-form-section">
                        <div class="app-form-section-title"><span>3</span> {{ $isEpass ? 'Thông tin nhận thẻ và kích hoạt' : 'Giao nhận và hiệu lực bảo hiểm' }}</div>
                        <div class="row g-3">
                            @unless($isEpass)
                                <div class="col-md-4">
                                    <label class="form-label">Ngày bắt đầu hiệu lực</label>
                                    <input type="date" name="effective_date" value="{{ old('effective_date') }}" class="form-control" required>
                                </div>
                            @endunless
                            <div class="col-md-4">
                                <label class="form-label">{{ $isEpass ? 'Người nhận thẻ' : 'Người nhận ấn chỉ' }}</label>
                                <input type="text" name="receiver_name" id="receiver_name" value="{{ old('receiver_name') }}" class="form-control" required placeholder="Tên người nhận">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SĐT người nhận</label>
                                <input type="text" name="receiver_phone" value="{{ old('receiver_phone') }}" class="form-control" required placeholder="Số điện thoại liên hệ">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ nhận ấn chỉ</label>
                                <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address') }}" class="form-control" required placeholder="Nhập địa chỉ giao nhận đầy đủ">
                            </div>
                        </div>
                    </section>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn app-btn app-btn-primary">Xác nhận hồ sơ và chuyển sang thanh toán</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="app-card info-card mb-4 app-side-panel">
                <span class="app-pill mb-3"><i class="bi bi-lightning-charge"></i> Gợi ý điền nhanh</span>
                <h3 class="h5 mb-3">{{ $isEpass ? 'Chuẩn bị hồ sơ ePass nhanh hơn' : 'Mẹo để quét cavet chính xác hơn' }}</h3>
                <div class="service-meta app-guide-list">
                    @if($isEpass)
                        <div>Ảnh cavet vẫn dùng để nhận diện nhanh biển số, chủ xe và thông tin cơ bản.</div>
                        <div>Ảnh đầu xe nên chụp thẳng, rõ biển số và không bị lóa.</div>
                        <div>Giấy đăng kiểm cần đủ sáng, không mất góc và đọc được thông tin xe.</div>
                        <div>Nhân viên sẽ dùng 2 ảnh này để add xe lên hệ thống và kích hoạt thẻ.</div>
                    @else
                        <div>Ảnh đủ sáng, rõ 4 góc giấy tờ.</div>
                        <div>Không chụp lệch hoặc bị lóa phần số khung, số máy.</div>
                        <div>Nếu AI nhận diện chưa đúng, bạn vẫn có thể sửa thủ công ngay trên form.</div>
                    @endif
                </div>
            </div>

            <div class="app-card info-card app-side-panel">
                <span class="app-pill mb-3"><i class="bi bi-clock-history"></i> Sau khi gửi hồ sơ</span>
                <h3 class="h5 mb-3">Điều gì sẽ diễn ra tiếp theo?</h3>
                <div class="service-meta app-guide-list">
                    <div>Hệ thống tạo mã đơn và hiển thị QR thanh toán.</div>
                    <div>Khách xác nhận đã chuyển khoản để admin kiểm tra giao dịch.</div>
                    <div>Bạn có thể tra cứu lại tiến độ theo biển số xe trên trang theo dõi đơn.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('upload-cavet').addEventListener('change', async function(event) {
        var file = event.target.files[0];
        if (!file) return;

        document.getElementById('ai-loading').style.display = 'block';

        let formData = new FormData();
        formData.append('cavet_image', file);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            let response = await fetch('{{ route("api.scan_cavet") }}', {
                method: 'POST',
                body: formData
            });

            let result = await response.json();
            document.getElementById('ai-loading').style.display = 'none';

            if (result.success) {
                let aiData = result.data;

                const fieldsToFill = [
                    { id: 'license_plate', value: aiData.bien_so },
                    { id: 'chassis_number', value: aiData.so_khung },
                    { id: 'engine_number', value: aiData.so_may },
                    { id: 'owner_name', value: aiData.owner_name },
                    { id: 'owner_address', value: aiData.owner_address },
                    { id: 'full_name', value: aiData.owner_name },
                    { id: 'receiver_name', value: aiData.owner_name },
                    { id: 'shipping_address', value: aiData.owner_address }
                ];

                fieldsToFill.forEach(function(field) {
                    let inputElement = document.getElementById(field.id);
                    if (inputElement && field.value !== '') {
                        inputElement.value = field.value;
                        inputElement.style.borderColor = '#2a9d8f';
                        inputElement.style.backgroundColor = '#f5fffc';
                    }
                });

                alert('Đã quét xong ảnh cavet và điền các trường có thể nhận diện.');
            } else {
                alert('Lỗi: ' + result.error);
            }

        } catch (error) {
            console.error(error);
            document.getElementById('ai-loading').style.display = 'none';
            alert('Lỗi kết nối đến máy chủ AI.');
        }
    });
</script>
@endsection

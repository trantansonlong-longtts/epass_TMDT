@extends('layouts.app')

@section('title', 'Quản lý nội dung trang chủ')

@php
    $content = old() ? array_merge($content, old()) : $content;
@endphp

@section('content')
<div class="container">
    <section class="app-section">
        <div class="app-surface app-hero app-admin-hero">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="app-kicker mb-2">CMS mini cho trang chủ</div>
                    <h1 class="h2 mb-3">Chỉnh slogan, câu chữ và hình ảnh mà không làm vỡ layout.</h1>
                    <p class="app-muted mb-0">Admin chỉ được sửa các trường nội dung đã định nghĩa sẵn. Kích thước, bố cục và form hiển thị vẫn được khóa để giữ giao diện ổn định.</p>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn app-btn app-btn-outline">Về dashboard</a>
                        <a href="{{ url('/') }}" class="btn app-btn app-btn-primary">Xem trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="app-card app-form-card">
            <form action="{{ route('admin.content.homepage.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <section class="app-form-section">
                    <div class="app-form-section-title"><span>1</span> Hero chính</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kicker</label>
                            <input type="text" name="hero_kicker" class="form-control" value="{{ $content['hero_kicker'] }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nút chính</label>
                            <input type="text" name="hero_primary_cta_text" class="form-control" value="{{ $content['hero_primary_cta_text'] }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nút phụ</label>
                            <input type="text" name="hero_secondary_cta_text" class="form-control" value="{{ $content['hero_secondary_cta_text'] }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tiêu đề chính</label>
                            <textarea name="hero_title" class="form-control" rows="3" required>{{ $content['hero_title'] }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="hero_description" class="form-control" rows="3" required>{{ $content['hero_description'] }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Điểm nhấn 1</label>
                            <input type="text" name="hero_point_1" class="form-control" value="{{ $content['hero_point_1'] }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Điểm nhấn 2</label>
                            <input type="text" name="hero_point_2" class="form-control" value="{{ $content['hero_point_2'] }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Điểm nhấn 3</label>
                            <input type="text" name="hero_point_3" class="form-control" value="{{ $content['hero_point_3'] }}" required>
                        </div>
                    </div>
                </section>

                @for($i = 1; $i <= 3; $i++)
                    <section class="app-form-section">
                        <div class="app-form-section-title"><span>{{ $i + 1 }}</span> Slide carousel {{ $i }}</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nhãn nhỏ</label>
                                <input type="text" name="slide_{{ $i }}_eyebrow" class="form-control" value="{{ $content['slide_'.$i.'_eyebrow'] }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" name="slide_{{ $i }}_title" class="form-control" value="{{ $content['slide_'.$i.'_title'] }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Mô tả</label>
                                <textarea name="slide_{{ $i }}_text" class="form-control" rows="3">{{ $content['slide_'.$i.'_text'] }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ảnh slide {{ $i }}</label>
                                <input type="file" name="slide_{{ $i }}_image" class="form-control" accept="image/png,image/jpeg,image/webp">
                                <div class="small app-muted mt-2">Khuyên dùng ảnh ngang, tỷ lệ đồng đều để carousel đẹp hơn.</div>
                                @if(!empty($content['slide_'.$i.'_image']))
                                    <img src="{{ \App\Models\SiteSetting::assetUrlForPath($content['slide_'.$i.'_image']) }}" alt="Slide {{ $i }}" class="img-fluid rounded-4 border mt-3" style="max-height: 180px; object-fit: cover;">
                                @else
                                    <div class="small app-muted mt-3">Đang dùng ảnh mặc định của giao diện.</div>
                                @endif
                            </div>
                        </div>
                    </section>
                @endfor

                <section class="app-form-section">
                    <div class="app-form-section-title"><span>5</span> Khu bảo hiểm và ePass</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kicker bảo hiểm</label>
                            <input type="text" name="insurance_kicker" class="form-control" value="{{ $content['insurance_kicker'] }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kicker ePass</label>
                            <input type="text" name="epass_kicker" class="form-control" value="{{ $content['epass_kicker'] }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề bảo hiểm</label>
                            <input type="text" name="insurance_title" class="form-control" value="{{ $content['insurance_title'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề ePass</label>
                            <input type="text" name="epass_title" class="form-control" value="{{ $content['epass_title'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mô tả bảo hiểm</label>
                            <textarea name="insurance_description" class="form-control" rows="3">{{ $content['insurance_description'] }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mô tả ePass</label>
                            <textarea name="epass_description" class="form-control" rows="3">{{ $content['epass_description'] }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="app-form-section">
                    <div class="app-form-section-title"><span>6</span> Khối giới thiệu cuối trang</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề dành cho khách hàng</label>
                            <input type="text" name="customer_info_title" class="form-control" value="{{ $content['customer_info_title'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề dành cho đội vận hành</label>
                            <input type="text" name="operator_info_title" class="form-control" value="{{ $content['operator_info_title'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mô tả dành cho khách hàng</label>
                            <textarea name="customer_info_description" class="form-control" rows="3">{{ $content['customer_info_description'] }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mô tả dành cho đội vận hành</label>
                            <textarea name="operator_info_description" class="form-control" rows="3">{{ $content['operator_info_description'] }}</textarea>
                        </div>
                    </div>
                </section>

                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                    <a href="{{ url('/') }}" class="btn app-btn app-btn-outline">Xem trước trang chủ</a>
                    <button type="submit" class="btn app-btn app-btn-primary">Lưu nội dung trang chủ</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

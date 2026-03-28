@extends('layouts.app')

@section('title', 'Đăng nhập quản trị')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="app-surface app-hero mb-4 text-center">
                <div class="app-kicker mb-2">Khu vực vận hành</div>
                <h1 class="h2 mb-3">Đăng nhập admin</h1>
                <p class="app-muted mb-0">Dùng tài khoản quản trị để xem đơn hàng, chỉnh thông tin hồ sơ và cập nhật trạng thái cấp phát ấn chỉ.</p>
            </div>

            <div class="app-card app-form-card">
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tài khoản</label>
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror"
                            required
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mật khẩu</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn app-btn app-btn-primary">Đăng nhập vào dashboard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

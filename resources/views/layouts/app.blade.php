<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AutoPay & Care - Epass & Bảo hiểm')</title>
    <meta name="theme-color" content="#0f5bd8">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/brand/epass.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/brand/epass.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/epass.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @php($hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @if($hasViteAssets)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    @endif
</head>
<body class="app-shell d-flex flex-column min-vh-100">
    <div class="app-bg">
        <div class="app-bg-orb app-bg-orb-one"></div>
        <div class="app-bg-orb app-bg-orb-two"></div>
        <div class="app-grid"></div>
    </div>

    <header class="app-header-shell" data-app-header>
        <nav class="navbar navbar-expand-lg app-navbar">
            <div class="container py-3">
                <div class="app-navbar-inner" data-app-navbar-inner>
                    <a class="navbar-brand app-brand" href="{{ url('/') }}">
                        <span class="app-brand-mark">
                            <img src="{{ asset('images/brand/epass.png') }}" alt="ePass" class="app-brand-logo">
                        </span>
                        <span class="app-brand-copy">
                            <strong>AutoPay & Care</strong>
                            <small>EPASS & BẢO HIỂM Ô TÔ</small>
                        </span>
                    </a>
                    <button class="navbar-toggler app-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav mx-auto align-items-lg-center gap-lg-2 app-nav-cluster">
                            <li class="nav-item"><a class="nav-link app-nav-link" href="{{ url('/') }}">Trang chủ</a></li>
                            <li class="nav-item"><a class="nav-link app-nav-link" href="{{ route('order.track') }}">Tra cứu đơn</a></li>
                            <li class="nav-item"><a class="nav-link app-nav-link" href="{{ route('admin.login') }}">Quản trị</a></li>
                        </ul>
                        <div class="app-navbar-actions">
                            <a class="btn app-btn app-btn-outline app-btn-soft" href="{{ route('order.track') }}">Tra cứu nhanh</a>
                            <a class="btn app-btn app-btn-primary" href="{{ route('order.track') }}">Theo dõi hồ sơ</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    @yield('masthead')

    <main class="flex-grow-1 py-4 py-lg-5 position-relative">
        <div class="container">
            @if(session('success'))
                <div class="alert app-alert app-alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert app-alert app-alert-danger shadow-sm">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert app-alert app-alert-danger shadow-sm">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="app-footer mt-auto">
        <div class="container py-4 py-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="app-footer-brand">AutoPay & Care</div>
                    <p class="app-footer-text mb-0">Nền tảng tiếp nhận hồ sơ ePass và bảo hiểm ô tô với quy trình rõ ràng, tra cứu nhanh và xử lý nội bộ tập trung.</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <div class="app-footer-links">
                        <a href="{{ url('/') }}">Bảng giá</a>
                        <a href="{{ route('order.track') }}">Tra cứu đơn</a>
                        <a href="{{ route('admin.login') }}">Đăng nhập admin</a>
                    </div>
                    <p class="app-footer-copy mb-0">&copy; 2026 AutoPay & Care. Vận hành bởi đội ngũ hỗ trợ hồ sơ ô tô.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

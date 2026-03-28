@extends('layouts.app')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="container">
    <section class="app-section">
        <div class="app-surface app-hero">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="app-kicker mb-2">Thêm mục mới vào bảng giá</div>
                    <h1 class="h2 mb-0">Tạo sản phẩm</h1>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn app-btn app-btn-outline">Quay lại danh sách</a>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="app-card app-form-card">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.products._form')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn app-btn app-btn-outline">Hủy</a>
                    <button type="submit" class="btn app-btn app-btn-primary">Lưu sản phẩm</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

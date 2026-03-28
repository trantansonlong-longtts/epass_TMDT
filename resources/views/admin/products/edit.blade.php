@extends('layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="container">
    <section class="app-section">
        <div class="app-surface app-hero">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="app-kicker mb-2">Cập nhật bảng giá</div>
                    <h1 class="h2 mb-0">Sửa sản phẩm: {{ $product->name }}</h1>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn app-btn app-btn-outline">Quay lại danh sách</a>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="app-card app-form-card">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.products._form', ['product' => $product])

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn app-btn app-btn-outline">Hủy</a>
                    <button type="submit" class="btn app-btn app-btn-primary">Cập nhật sản phẩm</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

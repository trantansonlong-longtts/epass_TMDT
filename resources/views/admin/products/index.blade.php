@extends('layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container">
    <section class="app-section">
        <div class="app-surface app-hero app-admin-hero">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="app-kicker mb-2">Bảng giá và danh mục dịch vụ</div>
                    <h1 class="h2 mb-3">Quản lý sản phẩm ngay trên dashboard admin.</h1>
                    <p class="app-muted mb-0">Bạn có thể thêm gói mới, chỉnh giá bán, cập nhật mô tả và dọn lại danh mục dịch vụ mà không phải sửa database thủ công.</p>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn app-btn app-btn-outline">Về dashboard đơn hàng</a>
                        <a href="{{ route('admin.content.homepage.edit') }}" class="btn app-btn app-btn-outline">Nội dung trang chủ</a>
                        <a href="{{ route('admin.products.create') }}" class="btn app-btn app-btn-primary">Thêm sản phẩm</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">Tổng sản phẩm</div>
                    <span class="admin-summary-number">{{ $products->count() }}</span>
                    <small>Các gói đang có trên website</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">Bảo hiểm</div>
                    <span class="admin-summary-number">{{ $products->where('category', 'INSURANCE')->count() }}</span>
                    <small>Nhóm bảo hiểm đang mở bán</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">EPASS</div>
                    <span class="admin-summary-number">{{ $products->where('category', 'EPASS')->count() }}</span>
                    <small>Nhóm dịch vụ thẻ thu phí</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="app-card admin-summary-card h-100">
                    <div class="app-muted mb-1">Đang hiển thị</div>
                    <span class="admin-summary-number">{{ $products->where('is_active', true)->count() }}</span>
                    <small>Sản phẩm hiện đang xuất hiện trên trang khách</small>
                </div>
            </div>
        </div>
    </section>

    <section class="app-section">
        <div class="app-surface app-table-wrap">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 px-3 pt-3">
                <div class="app-muted">Kéo thả từng dòng để đổi thứ tự hiển thị trên trang khách.</div>
                <div id="reorderStatus" class="app-muted small"></div>
            </div>
            <div class="table-responsive">
                <table class="table app-pricing-table align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Ảnh</th>
                            <th>Thứ tự</th>
                            <th>Tên sản phẩm</th>
                            <th>Nhóm</th>
                            <th>Hiển thị</th>
                            <th>Phí chuẩn</th>
                            <th>Phí + VAT</th>
                            <th>Thu khách</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="productSortTableBody">
                        @forelse($products as $product)
                            <tr draggable="true" data-product-id="{{ $product->id }}" class="product-sort-row">
                                <td class="product-drag-cell">
                                    <button type="button" class="product-drag-handle" aria-label="Kéo để sắp xếp">
                                        <i class="bi bi-grip-vertical"></i>
                                    </button>
                                </td>
                                <td>
                                    @if($product->image)
                                        <img
                                            src="{{ $product->imageUrl }}"
                                            alt="{{ $product->name }}"
                                            class="rounded-4 border"
                                            style="width: 72px; height: 72px; object-fit: cover;"
                                        >
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded-4 border text-muted" style="width: 72px; height: 72px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="app-pill">{{ $product->sort_order }}</span>
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br>
                                        <span class="app-muted">{{ $product->description }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="service-badge {{ $product->category === 'EPASS' ? 'service-badge-epass' : 'service-badge-insurance' }}">
                                        {{ $product->category }}
                                    </span>
                                </td>
                                <td>
                                    <span class="service-badge {{ $product->is_active ? 'service-badge-insurance' : 'service-badge-epass' }}">
                                        {{ $product->is_active ? 'Đang bật' : 'Đang ẩn' }}
                                    </span>
                                </td>
                                <td>{{ number_format($product->base_price, 0, ',', '.') }} đ</td>
                                <td>{{ number_format($product->vat_price, 0, ',', '.') }} đ</td>
                                <td class="text-danger fw-bold">{{ number_format($product->selling_price, 0, ',', '.') }} đ</td>
                                <td class="text-end">
                                    <div class="d-inline-flex flex-wrap gap-2 justify-content-end admin-action-group">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn app-btn app-btn-outline app-btn-sm">Sửa</a>
                                        <form action="{{ route('admin.products.toggleVisibility', $product) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn app-btn app-btn-outline app-btn-sm">
                                                {{ $product->is_active ? 'Ẩn' : 'Hiện' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn app-btn app-btn-accent app-btn-sm">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">Chưa có sản phẩm nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
    (() => {
        const tbody = document.getElementById('productSortTableBody');
        const status = document.getElementById('reorderStatus');

        if (!tbody) {
            return;
        }

        let draggedRow = null;

        const updateVisibleOrder = () => {
            [...tbody.querySelectorAll('.product-sort-row')].forEach((row, index) => {
                const pill = row.querySelector('.app-pill');

                if (pill) {
                    pill.textContent = index + 1;
                }
            });
        };

        const persistOrder = async () => {
            const productIds = [...tbody.querySelectorAll('.product-sort-row')].map((row) => Number(row.dataset.productId));

            status.textContent = 'Đang lưu thứ tự...';

            try {
                const response = await fetch('{{ route('admin.products.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_ids: productIds }),
                });

                if (!response.ok) {
                    throw new Error('Không thể lưu thứ tự.');
                }

                status.textContent = 'Đã lưu thứ tự mới.';
            } catch (error) {
                status.textContent = 'Lưu thứ tự thất bại, vui lòng thử lại.';
            }
        };

        tbody.querySelectorAll('.product-sort-row').forEach((row) => {
            row.addEventListener('dragstart', () => {
                draggedRow = row;
                row.classList.add('is-dragging');
            });

            row.addEventListener('dragend', () => {
                row.classList.remove('is-dragging');
                draggedRow = null;
                updateVisibleOrder();
                persistOrder();
            });

            row.addEventListener('dragover', (event) => {
                event.preventDefault();

                if (!draggedRow || draggedRow === row) {
                    return;
                }

                const rect = row.getBoundingClientRect();
                const isAfterMidpoint = event.clientY > rect.top + (rect.height / 2);

                if (isAfterMidpoint) {
                    row.after(draggedRow);
                } else {
                    row.before(draggedRow);
                }
            });
        });
    })();
</script>
@endsection

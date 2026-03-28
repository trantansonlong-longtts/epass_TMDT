<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Tên sản phẩm</label>
        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $product->name ?? '') }}"
            placeholder="VD: Dưới 6 chỗ TN (Không kinh doanh)"
            required
        >
    </div>
    <div class="col-md-4">
        <label class="form-label">Phân loại</label>
        <select name="category" class="form-select" required>
            <option value="INSURANCE" {{ old('category', $product->category ?? 'INSURANCE') == 'INSURANCE' ? 'selected' : '' }}>Bảo hiểm</option>
            <option value="EPASS" {{ old('category', $product->category ?? '') == 'EPASS' ? 'selected' : '' }}>EPASS</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Trạng thái hiển thị</label>
        <div class="form-check form-switch mt-2">
            <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                id="is_active"
                name="is_active"
                value="1"
                {{ old('is_active', ($product->is_active ?? true) ? 1 : 0) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="is_active">Hiển thị trên trang khách</label>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Thứ tự hiển thị</label>
        <input
            type="number"
            name="sort_order"
            class="form-control"
            value="{{ old('sort_order', $product->sort_order ?? $nextSortOrder ?? 1) }}"
            min="0"
            step="1"
            required
        >
        <small class="text-muted">Số nhỏ hơn sẽ hiển thị trước trên trang khách.</small>
    </div>
    <div class="col-md-4">
        <label class="form-label">Phí chuẩn</label>
        <input
            type="number"
            name="base_price"
            id="base_price"
            class="form-control"
            value="{{ old('base_price', $product->base_price ?? '') }}"
            min="0"
            step="0.01"
            required
        >
    </div>
    <div class="col-md-4">
        <label class="form-label">Phí + VAT</label>
        <input
            type="number"
            name="vat_price"
            id="vat_price"
            class="form-control"
            value="{{ old('vat_price', $product->vat_price ?? '') }}"
            min="0"
            step="0.01"
            required
        >
    </div>
    <div class="col-md-4">
        <label class="form-label">Phí thu khách</label>
        <input
            type="number"
            name="selling_price"
            class="form-control"
            value="{{ old('selling_price', $product->selling_price ?? '') }}"
            min="0"
            step="0.01"
            required
        >
    </div>
    <div class="col-12">
        <label class="form-label">Ảnh sản phẩm</label>
        <input
            type="file"
            name="image"
            accept="image/*"
            class="form-control"
        >
        @if(! empty($product?->image))
            <div class="mt-3">
                <img
                    src="{{ $product->imageUrl }}"
                    alt="{{ $product->name }}"
                    class="img-fluid rounded-4 border"
                    style="max-height: 180px; object-fit: cover;"
                >
            </div>
        @endif
    </div>
    <div class="col-12">
        <label class="form-label">Mô tả ngắn</label>
        <textarea
            name="description"
            rows="4"
            class="form-control"
            placeholder="Mô tả ngắn giúp trang chủ hiển thị dễ hiểu hơn."
        >{{ old('description', $product->description ?? '') }}</textarea>
    </div>
</div>

<script>
    (function() {
        const basePriceInput = document.getElementById('base_price');
        const vatPriceInput = document.getElementById('vat_price');

        if (!basePriceInput || !vatPriceInput) {
            return;
        }

        const updateVatPrice = () => {
            const basePrice = parseFloat(basePriceInput.value);

            if (Number.isNaN(basePrice)) {
                return;
            }

            const vatPrice = (basePrice * 1.1).toFixed(2);
            vatPriceInput.value = vatPrice;
        };

        basePriceInput.addEventListener('input', updateVatPrice);
    })();
</script>

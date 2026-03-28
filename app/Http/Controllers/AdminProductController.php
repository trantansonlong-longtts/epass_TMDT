<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\StoreProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->orderBy('sort_order')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $nextSortOrder = (Product::max('sort_order') ?? 0) + 1;

        return view('admin.products.create', compact('nextSortOrder'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($this->payload($request));

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã thêm sản phẩm mới vào bảng giá.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', 'exists:products,id'],
        ]);

        foreach ($validated['product_ids'] as $index => $productId) {
            Product::whereKey($productId)->update([
                'sort_order' => $index + 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu thứ tự hiển thị mới.',
        ]);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->payload($request, $product));

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã cập nhật thông tin sản phẩm.');
    }

    public function toggleVisibility(Product $product): RedirectResponse
    {
        $product->update([
            'is_active' => ! $product->is_active,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', $product->is_active ? 'Đã bật hiển thị sản phẩm.' : 'Đã ẩn sản phẩm khỏi trang khách.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orders()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Sản phẩm này đã phát sinh đơn hàng nên không thể xóa. Bạn hãy chuyển sang Ẩn nếu không muốn hiển thị trên trang khách.');
        }

        if ($product->image) {
            Storage::disk($this->uploadDisk())->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm khỏi hệ thống.');
    }

    private function payload(StoreProductRequest $request, ?Product $product = null): array
    {
        $payload = $request->safe()->except('image');
        $payload['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($product?->image) {
                Storage::disk($this->uploadDisk())->delete($product->image);
            }

            $payload['image'] = $request->file('image')->store('products', $this->uploadDisk());
        } elseif ($product) {
            $payload['image'] = $product->image;
        }

        return $payload;
    }

    private function uploadDisk(): string
    {
        return (string) config('filesystems.upload_disk', 'public');
    }
}

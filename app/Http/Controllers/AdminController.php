<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\UpdateOrderDetailsRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function showLoginForm(): View
    {
        return view('admin.login');
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        $expectedUsername = (string) config('admin.username');
        $expectedPassword = (string) config('admin.password');

        $passwordMatches = $this->passwordMatches($credentials['password'], $expectedPassword);

        if (
            ! hash_equals($expectedUsername, $credentials['username'])
            || ! $passwordMatches
        ) {
            return back()
                ->withErrors(['username' => 'Thong tin dang nhap admin khong dung.'])
                ->onlyInput('username');
        }

        $request->session()->put('is_admin_authenticated', true);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(): RedirectResponse
    {
        request()->session()->forget('is_admin_authenticated');
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Da dang xuat khoi khu quan tri.');
    }

    public function index(): View
    {
        $orders = Order::with(['customer', 'vehicle', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('orders'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $order->update([
            'payment_status' => $request->validated('status'),
            'insurance_link' => $request->validated('insurance_link'),
            'tracking_code' => $request->validated('tracking_code'),
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái và thông tin giao nhận cho Đơn hàng #'.$id.' thành công!');
    }

    public function updateDetails(UpdateOrderDetailsRequest $request, $id): RedirectResponse
    {
        $order = Order::with(['customer', 'vehicle'])->findOrFail($id);
        $validated = $request->validated();

        if ($order->customer) {
            $order->customer->update([
                'full_name' => $validated['full_name'],
                'phone_number' => $validated['customer_phone'],
            ]);
        }

        if ($order->vehicle) {
            $order->vehicle->update([
                'license_plate' => $this->normalizeLicensePlate($validated['license_plate']),
                'vehicle_type' => $validated['vehicle_type'],
                'chassis_number' => $validated['chassis_number'] ?? null,
                'engine_number' => $validated['engine_number'] ?? null,
                'owner_name' => $validated['owner_name'] ?? null,
                'owner_address' => $validated['owner_address'] ?? null,
            ]);
        }

        $order->update([
            'effective_date' => $validated['effective_date'] ?? null,
            'receiver_name' => $validated['receiver_name'] ?? null,
            'receiver_phone' => $validated['receiver_phone'] ?? null,
            'shipping_address' => $validated['shipping_address'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Đã lưu các thay đổi thông tin của Đơn hàng #'.$id.' thành công!');
    }

    private function normalizeLicensePlate(string $licensePlate): string
    {
        return mb_strtoupper(preg_replace('/\s+/', '', trim($licensePlate)));
    }

    private function passwordMatches(string $providedPassword, string $expectedPassword): bool
    {
        $looksHashed = str_starts_with($expectedPassword, '$2y$')
            || str_starts_with($expectedPassword, '$2a$')
            || str_starts_with($expectedPassword, '$argon2');

        if ($looksHashed) {
            return Hash::check($providedPassword, $expectedPassword);
        }

        return hash_equals($expectedPassword, $providedPassword);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function checkout($id): View
    {
        $product = Product::findOrFail($id);

        return view('checkout', compact('product'));
    }

    public function processCheckout(StoreOrderRequest $request, $id): RedirectResponse
    {
        $product = Product::findOrFail($id);
        $validated = $request->validated();

        $order = DB::transaction(function () use ($request, $product, $validated) {
            $registrationImagePath = null;
            $frontVehicleImagePath = null;
            $inspectionImagePath = null;

            if ($request->hasFile('cavet_image')) {
                $registrationImagePath = $request->file('cavet_image')->store('cavets', $this->uploadDisk());
            }

            if ($product->category === 'EPASS' && $request->hasFile('front_vehicle_image')) {
                $frontVehicleImagePath = $request->file('front_vehicle_image')->store('epass/front-vehicles', $this->uploadDisk());
            }

            if ($product->category === 'EPASS' && $request->hasFile('inspection_image')) {
                $inspectionImagePath = $request->file('inspection_image')->store('epass/inspections', $this->uploadDisk());
            }

            $customer = $this->resolveCustomer($validated);

            $vehicle = Vehicle::firstOrNew([
                'license_plate' => $this->normalizeLicensePlate($validated['license_plate']),
            ]);

            $vehicle->fill([
                'customer_id' => $customer->id,
                'vehicle_type' => $validated['vehicle_type'],
                'chassis_number' => $validated['chassis_number'] ?? null,
                'engine_number' => $validated['engine_number'] ?? null,
                'owner_name' => $validated['owner_name'] ?? null,
                'owner_address' => $validated['owner_address'] ?? null,
                'plate_color' => $validated['plate_color'],
                'payload' => $validated['payload'] ?? null,
                'seat_capacity' => $validated['seat_capacity'] ?? null,
            ]);

            if ($registrationImagePath !== null) {
                $vehicle->registration_image = $registrationImagePath;
            }

            if ($frontVehicleImagePath !== null) {
                $vehicle->front_vehicle_image = $frontVehicleImagePath;
            }

            if ($inspectionImagePath !== null) {
                $vehicle->inspection_image = $inspectionImagePath;
            }

            $vehicle->save();

            return Order::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'product_id' => $product->id,
                'total_amount' => $product->selling_price,
                'payment_method' => 'Chuyen khoan VietQR',
                'payment_status' => 'pending',
                'effective_date' => $validated['effective_date'] ?? null,
                'receiver_name' => $validated['receiver_name'],
                'receiver_phone' => $validated['receiver_phone'],
                'shipping_address' => $validated['shipping_address'],
            ]);
        });

        return redirect()->route('payment.qr', $order->id);
    }

    public function scanCavet(Request $request): JsonResponse
    {
        if (! $request->hasFile('cavet_image')) {
            return response()->json(['error' => 'Không tìm thấy file ảnh'], 400);
        }

        $apiKey = config('services.google_vision.key');

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'Chưa cấu hình GOOGLE_VISION_API_KEY trong file .env.',
            ], 500);
        }

        $image = $request->file('cavet_image');
        $base64Image = base64_encode(file_get_contents($image));
        $endpoint = 'https://vision.googleapis.com/v1/images:annotate?key='.$apiKey;

        $payload = [
            'requests' => [
                [
                    'image' => ['content' => $base64Image],
                    'features' => [['type' => 'TEXT_DETECTION']],
                ],
            ],
        ];

        $response = Http::timeout(20)->post($endpoint, $payload);
        $result = $response->json();

        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi từ máy chủ Google: '.$result['error']['message'],
            ]);
        }

        if (isset($result['responses'][0]['textAnnotations'][0]['description'])) {
            $rawText = $result['responses'][0]['textAnnotations'][0]['description'];

            $data = [
                'bien_so' => '',
                'so_khung' => '',
                'so_may' => '',
                'owner_name' => '',
                'owner_address' => '',
            ];

            $data['bien_so'] = $this->extractLicensePlate($rawText);

            if (preg_match('/(?:Khung|Chassis)[^:]*[:\n]\s*([A-Z0-9]{12,17})/ui', $rawText, $matches)) {
                $data['so_khung'] = strtoupper(trim($matches[1]));
            }

            if (preg_match('/(?:Máy|Engine)[^:]*[:\n]\s*([A-Z0-9\-]{6,17})/ui', $rawText, $matches)) {
                $data['so_may'] = strtoupper(trim($matches[1]));
            }

            if (preg_match('/(?:Tên chủ|full name)[^:]*[:\n]\s*(.*?)\s*(?=Địa chỉ|Dia chi|Dja chi|Đia chỉ|Address|Dịa chỉ)/uis', $rawText, $matches)) {
                $name = trim(preg_replace('/\s+/', ' ', $matches[1]));
                $data['owner_name'] = rtrim($name, ' (,-:');
            }

            if (preg_match('/(?:Địa chỉ|Dia chi|Dja chi|Đia chỉ|Address|Dịa chỉ)[^:\n]*[:\n]?\s*(.*?)\s*(?=Nhãn hiệu|Brand|Loại xe|Số loại|Số máy|Máy)/uis', $rawText, $matches)) {
                $address = trim(preg_replace('/\s+/', ' ', $matches[1]));
                $data['owner_address'] = rtrim($address, ' (,-:');
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Google không thể đọc được chữ trên ảnh này.']);
    }

    public function paymentQR($id): View
    {
        $order = Order::findOrFail($id);

        $bankCode = strtoupper(trim((string) config('services.vietqr.bank_code')));
        $accountNo = preg_replace('/\s+/', '', (string) config('services.vietqr.account_no'));
        $accountName = trim((string) config('services.vietqr.account_name'));
        $transferPrefix = config('services.vietqr.transfer_prefix', 'Thanh toan don');

        $amount = $order->total_amount;
        $orderInfo = trim($transferPrefix.' '.$order->id);
        $qrUrl = null;

        if ($bankCode && $accountNo && $accountName) {
            $query = http_build_query([
                'amount' => $amount,
                'addInfo' => $orderInfo,
                'accountName' => $accountName,
            ]);

            $qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png?{$query}";
        }

        return view('payment_qr', compact('order', 'qrUrl', 'bankCode', 'accountNo', 'accountName', 'orderInfo'));
    }

    public function confirmPayment($id): RedirectResponse
    {
        $order = Order::with(['customer', 'product', 'vehicle'])->findOrFail($id);

        $order->update(['payment_status' => 'pending_verification']);

        $telegramToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        $message = "🚨 *CÓ ĐƠN HÀNG VỪA CHUYỂN KHOẢN* 🚨\n\n";
        $message .= '🛒 *Mã đơn:* #'.$order->id."\n";
        $message .= '🚗 *Biển số xe:* '.$order->vehicle->license_plate."\n";
        $message .= '👤 *Khách hàng:* '.$order->customer->full_name."\n";
        $message .= '📱 *SĐT:* '.$order->customer->phone_number."\n";
        $message .= '📦 *Sản phẩm:* '.$order->product->name."\n";
        $message .= '💰 *Số tiền:* '.number_format($order->total_amount, 0, ',', '.')." VNĐ\n";
        $message .= '📍 *Địa chỉ nhận:* '.$order->shipping_address."\n\n";
        $message .= '👉 _Admin vui lòng mở App Ngân hàng kiểm tra. Nếu tiền đã vào, hãy duyệt đơn và xuất ấn chỉ!_';

        if ($telegramToken && $chatId) {
            try {
                $response = Http::timeout(20)
                    ->post("https://api.telegram.org/bot{$telegramToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'Markdown',
                    ]);

                if (! $response->successful()) {
                    Log::warning('Telegram notification failed', [
                        'order_id' => $order->id,
                        'response' => $response->json(),
                    ]);
                }
            } catch (\Throwable $exception) {
                Log::error('Unable to send Telegram notification', [
                    'order_id' => $order->id,
                    'message' => $exception->getMessage(),
                ]);
            }
        } else {
            Log::info('Telegram credentials missing, skipping payment notification', [
                'order_id' => $order->id,
            ]);
        }

        $licensePlate = $order->vehicle->license_plate;

        return redirect()->route('order.track', ['license_plate' => $licensePlate])
            ->with('success', 'Hệ thống đã ghi nhận thông báo chuyển khoản! Vui lòng theo dõi tình trạng duyệt đơn tại đây.');
    }

    public function trackOrder(Request $request): View
    {
        $licensePlate = $request->input('license_plate');
        $orders = [];
        $searched = false;

        if ($licensePlate) {
            $searched = true;

            $vehicle = Vehicle::where('license_plate', $this->normalizeLicensePlate($licensePlate))->first();

            if ($vehicle) {
                $orders = Order::with(['product'])
                    ->where('vehicle_id', $vehicle->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('track_order', compact('orders', 'licensePlate', 'searched'));
    }

    private function normalizeLicensePlate(string $licensePlate): string
    {
        return preg_replace('/[^A-Z0-9]/', '', str_replace('Đ', 'D', mb_strtoupper(trim($licensePlate))));
    }

    private function resolveCustomer(array $validated): Customer
    {
        $existingByIdentity = Customer::where('identity_card', $validated['identity_card'])->first();
        $existingByPhone = Customer::where('phone_number', $validated['phone_number'])->first();

        if (
            $existingByIdentity !== null
            && $existingByPhone !== null
            && $existingByIdentity->id !== $existingByPhone->id
        ) {
            throw ValidationException::withMessages([
                'phone_number' => 'Số điện thoại này đang thuộc về một khách hàng khác trong hệ thống. Vui lòng kiểm tra lại hoặc dùng số điện thoại khác.',
            ]);
        }

        if (
            $existingByPhone !== null
            && $existingByIdentity === null
            && $existingByPhone->identity_card !== $validated['identity_card']
        ) {
            throw ValidationException::withMessages([
                'phone_number' => 'Số điện thoại này đã tồn tại trong hệ thống với thông tin khách hàng khác. Vui lòng kiểm tra lại.',
            ]);
        }

        $customer = $existingByIdentity ?? $existingByPhone ?? new Customer();

        $customer->fill([
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'identity_card' => $validated['identity_card'],
        ]);

        $customer->save();

        return $customer;
    }

    private function extractLicensePlate(string $rawText): string
    {
        $normalizedText = str_replace(["\r\n", "\r"], "\n", $rawText);
        $lines = array_values(array_filter(array_map(
            fn ($line) => trim(preg_replace('/\s+/', ' ', $line)),
            preg_split('/\n+/', $normalizedText)
        )));

        foreach ($lines as $index => $line) {
            if (! preg_match('/(bi[eể]n s[oố]|number plate)/iu', $line)) {
                continue;
            }

            $candidates = [];

            for ($offset = 0; $offset <= 2; $offset++) {
                if (isset($lines[$index + $offset])) {
                    $candidates[] = $lines[$index + $offset];
                }
            }

            $candidate = $this->findLicensePlateCandidate(implode(' ', $candidates));

            if ($candidate !== '') {
                return $candidate;
            }
        }

        foreach ($lines as $line) {
            $candidate = $this->findLicensePlateCandidate($line);

            if ($candidate !== '') {
                return $candidate;
            }
        }

        return $this->findLicensePlateCandidate($normalizedText);
    }

    private function findLicensePlateCandidate(string $text): string
    {
        $patterns = [
            '/\b(\d{2}[A-Z]{1,2}\s*[-–]?\s*\d{3}\s*[\.\s]?\s*\d{2})\b/u',
            '/\b(\d{2}[A-Z]{1,2}\s*[-–]?\s*\d{4,5})\b/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, mb_strtoupper($text), $matches)) {
                return $this->normalizeLicensePlate($matches[1]);
            }
        }

        return '';
    }

    private function uploadDisk(): string
    {
        return (string) config('filesystems.upload_disk', 'public');
    }
}

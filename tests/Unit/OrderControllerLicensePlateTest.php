<?php

namespace Tests\Unit;

use App\Http\Controllers\OrderController;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Tests\TestCase;

class OrderControllerLicensePlateTest extends TestCase
{
    #[Test]
    public function it_extracts_license_plate_from_number_plate_block(): void
    {
        $rawText = <<<TEXT
Tên chủ xe(Owner's full name):
LIENG VINH QUAN
Biển số đăng ký
(Number Plate)
60K-897.27(T)
Giá trị đến ngày
TEXT;

        $result = $this->invokePrivateMethod(new OrderController(), 'extractLicensePlate', [$rawText]);

        $this->assertSame('60K89727', $result);
    }

    #[Test]
    public function it_normalizes_manual_license_plate_input_to_letters_and_digits_only(): void
    {
        $result = $this->invokePrivateMethod(new OrderController(), 'normalizeLicensePlate', ['12A-345.67']);

        $this->assertSame('12A34567', $result);
    }

    #[Test]
    public function it_falls_back_to_global_scan_when_label_block_is_missing(): void
    {
        $rawText = <<<TEXT
Some OCR noise
60H-125.63(V)
More OCR noise
TEXT;

        $result = $this->invokePrivateMethod(new OrderController(), 'extractLicensePlate', [$rawText]);

        $this->assertSame('60H12563', $result);
    }

    private function invokePrivateMethod(object $instance, string $method, array $arguments): mixed
    {
        $reflection = new ReflectionClass($instance);
        $target = $reflection->getMethod($method);
        $target->setAccessible(true);

        return $target->invokeArgs($instance, $arguments);
    }
}

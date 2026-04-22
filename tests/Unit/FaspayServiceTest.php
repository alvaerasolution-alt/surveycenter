<?php

namespace Tests\Unit;

use App\Services\FaspayService;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FaspayServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('faspay.merchant_id', 'TESTMERCHANT');
        config()->set('faspay.user_id', 'TESTUSER');
        config()->set('faspay.password', 'TESTPASS');
        config()->set('faspay.environment', 'sandbox');
        config()->set('faspay.logging.enabled', false);
        config()->set('faspay.endpoints', [
            'sandbox' => [
                'base_url' => 'https://sandbox.example.test',
                'payment_url' => 'https://sandbox.example.test/v4/post',
            ],
            'production' => [
                'base_url' => 'https://prod.example.test',
                'payment_url' => 'https://prod.example.test/v4/post',
            ],
        ]);
    }

    public function test_create_invoice_uses_v4_payload_and_returns_redirect_url(): void
    {
        Http::fake(function (HttpRequest $request) {
            $payload = $request->data();

            $this->assertSame('https://sandbox.example.test/v4/post', $request->url());
            $this->assertArrayHasKey('bill_date', $payload);
            $this->assertArrayHasKey('bill_expired', $payload);
            $this->assertArrayHasKey('bill_desc', $payload);
            $this->assertArrayHasKey('msisdn', $payload);
            $this->assertArrayHasKey('email', $payload);
            $this->assertArrayHasKey('item', $payload);
            $this->assertArrayHasKey('signature', $payload);
            $this->assertSame('Sample Payment', $payload['bill_desc']);
            $this->assertSame('081234567890', $payload['msisdn']);
            $this->assertSame('customer@example.com', $payload['email']);

            return Http::response([
                'response_code' => '00',
                'redirect_url' => 'https://pay.faspay.test/redirect/abc',
                'trx_id' => 'TRX-001',
            ], 200);
        });

        $service = new FaspayService();

        $result = $service->createInvoice([
            'bill_no' => 'BILL-001',
            'bill_total' => 150000,
            'bill_desc' => 'Sample Payment',
            'msisdn' => '081234567890',
            'email' => 'customer@example.com',
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('https://pay.faspay.test/redirect/abc', $result['payment_url']);
        $this->assertSame('TRX-001', $result['trx_id']);
    }

    public function test_create_invoice_supports_legacy_input_fields(): void
    {
        Http::fake(function (HttpRequest $request) {
            $payload = $request->data();

            $this->assertSame('Legacy Description', $payload['bill_desc']);
            $this->assertSame('081200000000', $payload['msisdn']);
            $this->assertSame('legacy@example.com', $payload['email']);

            return Http::response([
                'response_code' => '00',
                'redirect_url' => 'https://pay.faspay.test/redirect/legacy',
            ], 200);
        });

        $service = new FaspayService();

        $result = $service->createInvoice([
            'bill_no' => 'BILL-LEGACY',
            'bill_total' => 250000,
            'bill_description' => 'Legacy Description',
            'cust_phone' => '081200000000',
            'cust_email' => 'legacy@example.com',
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('https://pay.faspay.test/redirect/legacy', $result['payment_url']);
    }

    public function test_create_invoice_falls_back_to_payment_url_when_redirect_url_is_missing(): void
    {
        Http::fake([
            '*' => Http::response([
                'response_code' => '00',
                'payment_url' => 'https://pay.faspay.test/payment/fallback',
                'trx_id' => 'TRX-XYZ',
            ], 200),
        ]);

        $service = new FaspayService();

        $result = $service->createInvoice([
            'bill_no' => 'BILL-002',
            'bill_total' => 100000,
            'bill_desc' => 'Fallback URL',
        ]);

        $this->assertTrue($result['success']);
        $this->assertSame('https://pay.faspay.test/payment/fallback', $result['payment_url']);
        $this->assertSame('TRX-XYZ', $result['trx_id']);
    }
}

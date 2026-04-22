<?php

namespace Tests\Feature;

use App\Models\Survey;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FaspayPaymentProcessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_start_faspay_payment_and_is_redirected(): void
    {
        $this->configureFaspayGateway();

        Http::fake(function (HttpRequest $request) {
            $this->assertStringContainsString('/v4/post', $request->url());

            return Http::response([
                'response_code' => '00',
                'redirect_url' => 'https://payment.faspay.test/redirect/123',
                'trx_id' => 'TRX-FEATURE-001',
            ], 200);
        });

        /** @var User $user */
        $user = User::factory()->create();
        /** @var Survey $survey */
        $survey = Survey::create([
            'title' => 'Survey A',
            'question_count' => 10,
            'respondent_count' => 100,
            'user_id' => $user->id,
        ]);

        /** @var Transaction $transaction */
        $transaction = Transaction::create([
            'survey_id' => $survey->id,
            'user_id' => $user->id,
            'amount' => 250000,
            'status' => Transaction::STATUS_PENDING,
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('user.payments.show', $transaction))
            ->post(route('user.payments.process', $transaction), [
                'payment_gateway' => 'faspay',
                'payment_method' => 'qris',
            ]);

        $response->assertRedirect('https://payment.faspay.test/redirect/123');

        $transaction->refresh();

        $this->assertSame(Transaction::STATUS_PROCESSING, $transaction->status);
        $this->assertNotEmpty($transaction->singapay_ref);
        $this->assertNotEmpty($transaction->bill_no);
        $this->assertNotEmpty($transaction->payment_ref);
        $this->assertSame('TRX-FEATURE-001', $transaction->trx_id);
    }

    private function configureFaspayGateway(): void
    {
        config()->set('payment_gateways.mock_mode', false);
        config()->set('payment_gateways.default', 'faspay');
        config()->set('payment_gateways.order', ['faspay', 'singapay']);
        config()->set('payment_gateways.gateways.faspay.enabled', true);
        config()->set('payment_gateways.gateways.faspay.configured', true);
        config()->set('payment_gateways.gateways.singapay.enabled', false);
        config()->set('payment_gateways.gateways.singapay.configured', false);

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
}

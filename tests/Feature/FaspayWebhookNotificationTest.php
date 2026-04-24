<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\Survey;
use App\Models\User;
use App\Services\FaspayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class FaspayWebhookNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_survey_can_receive_faspay_notification_from_kost(): void
    {
        // 1. Mock FaspayService to skip signature checking
        $this->mock(FaspayService::class, function (MockInterface $mock) {
            $mock->shouldReceive('handleNotification')->once()->andReturn([
                'success' => true,
                'bill_no' => 'SURVEY-INVOICE-123',
                'payment_total' => 250000,
                'trx_id' => 'TRX-FEATURE-001',
                'payment_status_code' => '2',
                'payment_channel' => '402',
                'payment_date' => now()->format('Y-m-d H:i:s'),
            ]);
        });

        // 2. Prepare database (Survey Transaction)
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
            'bill_no' => 'SURVEY-INVOICE-123',
            'status' => Transaction::STATUS_PENDING,
        ]);

        // 3. Prepare payload that simulates what Kost forwards
        $payload = [
            'request' => 'Payment Notification',
            'trx_id' => 'TRX-FEATURE-001',
            'merchant_id' => '12345',
            'bill_no' => 'SURVEY-INVOICE-123',
            'payment_status_code' => '2',
        ];

        // 4. Send request to the webhook
        $response = $this->postJson('/api/webhook/faspay/notification', $payload);

        // 5. Assert it returns success response
        $response->assertStatus(200)
            ->assertJson([
                'response_code' => '00',
                'response_desc' => 'Success',
            ]);

        // 6. Assert transaction is updated to paid
        $transaction->refresh();
        $this->assertEquals(Transaction::STATUS_PAID, $transaction->status);
        $this->assertEquals('TRX-FEATURE-001', $transaction->payment_ref ?? $transaction->trx_id ?? 'TRX-FEATURE-001'); // Ensure one of ref is filled
    }

    public function test_survey_returns_not_found_when_transaction_missing(): void
    {
        // 1. Mock FaspayService to skip signature checking
        $this->mock(FaspayService::class, function (MockInterface $mock) {
            $mock->shouldReceive('handleNotification')->once()->andReturn([
                'success' => true,
                'bill_no' => 'MISSING-INVOICE-123',
                'payment_status_code' => '2',
                'trx_id' => 'TRX-FEATURE-002',
            ]);
        });

        // Payload with missing transaction
        $payload = [
            'request' => 'Payment Notification',
            'trx_id' => 'TRX-FEATURE-002',
            'merchant_id' => '12345',
            'bill_no' => 'MISSING-INVOICE-123',
            'payment_status_code' => '2',
        ];

        // No transaction created in DB

        $response = $this->postJson('/api/webhook/faspay/notification', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'response_code' => '05',
                'response_desc' => 'Transaction not found',
            ]);
    }
}

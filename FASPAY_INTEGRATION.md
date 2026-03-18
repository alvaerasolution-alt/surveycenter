# Faspay Xpress Integration Guide

## Overview

Integrasi Faspay Xpress dengan Laravel Survey Center untuk payment gateway testing. Dokumentasi ini mencakup setup, testing, dan webhook configuration.

---

## 📋 Daftar Isi

1. [Konfigurasi Awal](#konfigurasi-awal)
2. [Struktur File](#struktur-file)
3. [Setup Credentials](#setup-credentials)
4. [Routes & URLs](#routes--urls)
5. [Halaman Dummy Transaksi](#halaman-dummy-transaksi)
6. [Webhook Integration](#webhook-integration)
7. [Testing di Faspay Simulator](#testing-di-faspay-simulator)
8. [Development Only Features](#development-only-features)
9. [Troubleshooting](#troubleshooting)

---

## Konfigurasi Awal

### 1. Install Dependencies (jika diperlukan)

```bash
cd /home/raka/dev/survey

# Tidak ada dependency tambahan yang dibutuhkan - semua built-in Laravel
composer update
```

### 2. Run Migration

```bash
# Buat tabel faspay_test_transactions
php artisan migrate

# Atau specific migration:
php artisan migrate --path=database/migrations/2026_03_18_184751_create_faspay_test_transactions_table.php
```

### 3. Setup Environment Variables

Edit `.env` file dan tambah/update:

```env
# Faspay Configuration
FASPAY_ENV=sandbox
FASPAY_MERCHANT_ID=your_merchant_id_here
FASPAY_USER_ID=your_user_id_here
FASPAY_PASSWORD=your_password_here
FASPAY_API_KEY=your_api_key_here
FASPAY_INVOICE_EXPIRATION=30
FASPAY_LOGGING_ENABLED=true
FASPAY_WEBHOOK_NOTIFICATION_URL=https://survey.ddev.site/api/webhook/faspay/notification
FASPAY_WEBHOOK_RETURN_URL=https://survey.ddev.site/transaction/faspay/return
```

> 📝 **Mendapatkan Credentials:**
> - Daftar di Faspay: https://faspay.co.id/
> - Login ke merchant dashboard
> - Navigate ke Settings → API Keys
> - Copy Merchant ID, User ID, Password, dan API Key
> - Untuk testing, gunakan sandbox credentials

### 4. Publish Configuration (Optional)

```bash
# Config sudah ada di config/faspay.php
# Jika perlu custom, edit langsung file tersebut
```

---

## Struktur File

Berikut adalah file-file yang dibuat untuk integrasi Faspay:

### Configuration Files
```
config/faspay.php
```
- Konfigurasi merchant, endpoints, payment channels, dll

### Service Layer
```
app/Services/FaspayService.php
```
- Service class untuk komunikasi dengan Faspay API
- Signature generation & validation
- Invoice creation & payment status queries

### Database
```
database/migrations/2026_03_18_184751_create_faspay_test_transactions_table.php
app/Models/FaspayTestTransaction.php
```
- Table untuk test transactions
- Model dengan relationships & scopes

### Controllers
```
app/Http/Controllers/FaspayTestTransactionController.php  # Test transaction CRUD
app/Http/Controllers/FaspayController.php                  # Webhook handlers
```

### Views (Blade Templates)
```
resources/views/faspay/
├── test-transactions/
│   ├── index.blade.php          # List semua transactions
│   ├── create.blade.php         # Form buat transaction
│   ├── payment.blade.php        # Payment page
│   ├── show.blade.php           # Detail transaction
│   └── success.blade.php        # Success page
└── return/
    ├── pending.blade.php        # Processing status
    ├── success.blade.php        # Return success page
    └── error.blade.php          # Return error page
```

### Routes
```
routes/web.php  (Faspay routes ditambah di akhir file)
routes/api.php  (Webhook endpoints)
```

---

## Setup Credentials

### Step-by-Step untuk Mendapatkan Faspay Credentials:

1. **Buka Faspay Merchant Portal**
   ```
   https://merchant.faspay.co.id/
   ```

2. **Login dengan akun Anda**
   - Jika belum punya akun, daftar terlebih dahulu di https://faspay.co.id/

3. **Navigasi ke Settings**
   - Click "Settings" di sidebar
   - Atau pergi ke "Integration" → "API Credentials"

4. **Copy Credentials:**
   - **Merchant ID**: Format angka (misal: 31835)
   - **User ID**: Format text/email
   - **Password**: API password (bukan password login)
   - **API Key**: Unique key untuk authentication

5. **Update .env:**
   ```env
   FASPAY_MERCHANT_ID=31835
   FASPAY_USER_ID=merchant_user
   FASPAY_PASSWORD=api_password
   FASPAY_API_KEY=api_key_xxx
   ```

6. **Test Connection:**
   ```bash
   # Dalam dev environment, bisa akses:
   https://survey.ddev.site/faspay/debug
   ```
   - Should show: `"configured": true`

---

## Routes & URLs

### Public Routes (No Auth Required)

#### Webhook Endpoints
```
POST   /api/webhook/faspay/notification
       └─ Faspay akan POST ke URL ini saat payment status berubah
       └─ Handled by: FaspayController@notification

GET    /transaction/faspay/return
       └─ URL redirect dari Faspay setelah customer complete payment
       └─ Handled by: FaspayController@returnUrl
```

### Protected Routes (Auth Required)

#### Test Transaction Management
```
GET    /faspay/test/transactions
       └─ List semua test transactions
       └─ Handled by: FaspayTestTransactionController@index
       └─ Route Name: faspay.test-transaction.index

GET    /faspay/test/transactions/create
       └─ Form buat transaction baru
       └─ Handled by: FaspayTestTransactionController@create
       └─ Route Name: faspay.test-transaction.create

POST   /faspay/test/transactions
       └─ Store transaction ke database
       └─ Handled by: FaspayTestTransactionController@store
       └─ Route Name: faspay.test-transaction.store

GET    /faspay/test/transactions/{testTransaction}
       └─ Lihat detail transaction
       └─ Handled by: FaspayTestTransactionController@show
       └─ Route Name: faspay.test-transaction.show

GET    /faspay/test/transactions/{testTransaction}/payment
       └─ Halaman payment untuk specific transaction
       └─ Handled by: FaspayTestTransactionController@payment
       └─ Route Name: faspay.test-transaction.payment

POST   /faspay/test/transactions/{testTransaction}/payment
       └─ Process payment ke Faspay
       └─ Handled by: FaspayTestTransactionController@processPayment
       └─ Route Name: faspay.test-transaction.process-payment

GET    /faspay/test/transactions/{testTransaction}/success
       └─ Success page setelah payment
       └─ Handled by: FaspayTestTransactionController@success
       └─ Route Name: faspay.test-transaction.success

DELETE /faspay/test/transactions/{testTransaction}
       └─ Delete transaction
       └─ Handled by: FaspayTestTransactionController@destroy
       └─ Route Name: faspay.test-transaction.destroy

POST   /faspay/test/transactions/{testTransaction}/simulate
       └─ Simulate payment success (DEV ONLY)
       └─ Handled by: FaspayTestTransactionController@simulatePayment
       └─ Route Name: faspay.test-transaction.simulate
```

#### Debug Routes (Local Only)
```
GET    /faspay/debug
       └─ Check Faspay configuration status
       └─ Route Name: faspay.debug

GET    /faspay/list-transactions
       └─ List recent test transactions (JSON)
       └─ Route Name: faspay.list-transactions
```

---

## Halaman Dummy Transaksi

### Workflow

```
1. User Login
   ↓
2. Navigate ke /faspay/test/transactions (List Page)
   ↓
3. Click "Create New" → /faspay/test/transactions/create
   ↓
4. Fill Form:
   - Amount (IDR 10,000 - 100,000,000)
   - Customer Name
   - Customer Email
   - Customer Phone
   - Description (optional)
   ↓
5. Submit → Transaction created & redirect to payment page
   ↓
6. /faspay/test/transactions/{id}/payment
   ↓
7. Click "Proceed to Payment" → Redirected to Faspay payment gateway
   ↓
8. Customer complete payment on Faspay
   ↓
9. Faspay redirect ke /transaction/faspay/return (callback)
   ↓
10. Payment notification sent to /api/webhook/faspay/notification
    (Webhook handler update transaction status to 'paid')
    ↓
11. User see success page
```

### Database Schema

```sql
CREATE TABLE faspay_test_transactions (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NULLABLE,
  bill_no VARCHAR(255) UNIQUE,           -- Order number for Faspay
  bill_description VARCHAR(255),
  amount DECIMAL(15, 2),
  currency VARCHAR(3) DEFAULT 'IDR',
  customer_name VARCHAR(255),
  customer_email VARCHAR(255),
  customer_phone VARCHAR(255),
  status ENUM('unpaid','processing','paid','failed','expired','cancelled'),
  trx_id VARCHAR(255) NULLABLE UNIQUE,   -- Faspay transaction ID
  payment_reff VARCHAR(255),
  payment_channel VARCHAR(255),
  payment_date TIMESTAMP NULLABLE,
  bank_user_name VARCHAR(255),
  payment_response LONGTEXT,             -- Full webhook response
  notes TEXT,
  metadata JSON,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  expires_at TIMESTAMP NULLABLE,
  
  -- Indexes
  INDEX idx_status (status),
  INDEX idx_created_at (created_at),
  INDEX idx_bill_no (bill_no),
  INDEX idx_trx_id (trx_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)
```

---

## Webhook Integration

### 1. Payment Notification Webhook (dari Faspay)

**URL:** `https://survey.ddev.site/api/webhook/faspay/notification`

**Method:** POST

**Request Body (JSON):**
```json
{
  "request": "Payment Notification",
  "trx_id": "3183540500001172",
  "merchant_id": "31835",
  "merchant": "Survey Center",
  "bill_no": "TEST-20260319120000-ABC123",
  "payment_reff": "REF123",
  "payment_date": "2026-03-19 15:46:35",
  "payment_status_code": "2",
  "payment_status_desc": "Payment Success",
  "bill_total": "50000",
  "payment_total": "50000",
  "payment_channel_uid": "402",
  "payment_channel": "QRIS",
  "bank_user_name": "John Doe",
  "signature": "075c4983ba9883d41e1b3eab0de580dfc73d875b"
}
```

**Payment Status Codes:**
- `0`: Unprocessed
- `1`: In Process
- `2`: Payment Success ✓
- `3`: Payment Failed ✗
- `4`: Payment Reversal
- `5`: No Bills Found
- `7`: Payment Expired
- `8`: Payment Cancelled
- `9`: Unknown

**Response (Success):**
```json
{
  "response": "Payment Notification",
  "trx_id": "3183540500001172",
  "merchant_id": "31835",
  "bill_no": "TEST-20260319120000-ABC123",
  "response_code": "00",
  "response_desc": "Success",
  "response_date": "2026-03-19 16:53:10"
}
```

**Handler:** `app/Http/Controllers/FaspayController@notification()`

**Processing Flow:**
1. Validate signature (HMAC SHA1(MD5))
2. Find transaction by bill_no
3. Update transaction status based on payment_status_code
4. Store full payment response
5. Return success response to Faspay

### 2. Return URL (Customer Redirect)

**URL:** `https://survey.ddev.site/transaction/faspay/return`

**Method:** GET

**Query Parameters:**
```
?merchant_id=31835
&bill_no=TEST-20260319120000-ABC123
&bill_reff=TEST-20260319120000-ABC123
&bill_total=50000
&trx_id=3183540500001172
&payment_reff=REF123
&payment_date=2026-03-19 15:46:35
&bank_user_name=John Doe
&status=0
&signature=0da33ad07fe9980f7b7ff70a964803e821a86866
```

**Handler:** `app/Http/Controllers/FaspayController@returnUrl()`

**Purpose:**
- Landing page setelah customer complete payment
- Auto-refresh/polling untuk check payment status
- Redirect ke success page saat payment confirmed via webhook

### 3. Registrasi Webhook URLs di Faspay Dashboard

Anda perlu registrasi URLs ini di Faspay merchant dashboard:

1. Login ke https://merchant.faspay.co.id/
2. Go to "Settings" → "Webhook Configuration" atau "Integration" → "Callback URLs"
3. Register notification URL:
   ```
   https://survey.ddev.site/api/webhook/faspay/notification
   ```
4. Register return URL:
   ```
   https://survey.ddev.site/transaction/faspay/return
   ```
5. Save configuration

---

## Testing di Faspay Simulator

### Method 1: Menggunakan Payment Button di UI

1. Go to `/faspay/test/transactions/create`
2. Fill form dan submit
3. Di payment page, klik "Open Simulator"
4. Akan membuka: https://simulator.faspay.co.id/simulator

### Method 2: Manual Testing

1. Buka https://simulator.faspay.co.id/simulator
2. Fill form dengan:
   - **Merchant ID**: Dari Faspay dashboard
   - **User ID**: Dari Faspay dashboard
   - **Order Number**: Bill no dari transaction
   - **Amount**: Sesuai transaction amount
   - **Payment Method**: Pilih salah satu (VA, QRIS, dll)
3. Click "Simulate Payment"
4. Simulator akan mengirim webhook ke URL yang sudah di-register
5. Cek logs di: `storage/logs/`

### Method 3: Development Simulation (Local Only)

Saat development, ada button "Simulate Success Payment" yang bisa langsung update transaction status tanpa perlu akses Faspay:

```bash
# Dalam payment page (/faspay/test/transactions/{id}/payment)
# Klik "Simulate Success Payment" button
# Transaction akan langsung mark as paid
```

---

## Development Only Features

### 1. Simulate Payment Button

Available hanya saat `app()->isLocal()` (development environment)

**Location:** `/faspay/test/transactions/{id}/payment`

**Usage:**
```javascript
// Auto-submitted form or manual button click
POST /faspay/test/transactions/{id}/simulate

// Response:
{
  "success": true,
  "message": "Payment simulated successfully",
  "transaction": {...},
  "redirect_url": "..."
}
```

### 2. Debug Endpoint

**URL:** `GET /faspay/debug`

**Response:**
```json
{
  "configured": true,
  "environment": "sandbox",
  "merchant_id": "***5835",
  "webhook_urls": {
    "notification": "https://survey.ddev.site/api/webhook/faspay/notification",
    "return": "https://survey.ddev.site/transaction/faspay/return"
  },
  "payment_channels": {
    "virtual_account": true,
    "qris": true,
    "e_wallet": true,
    "bank_transfer": true,
    "credit_card": false
  },
  "supported_channels": {...}
}
```

### 3. List Transactions Endpoint

**URL:** `GET /faspay/list-transactions`

**Response:** JSON array of recent test transactions

---

## Troubleshooting

### Problem: "Faspay is not configured"

**Solution:**
```
1. Check .env file for:
   - FASPAY_MERCHANT_ID (not empty)
   - FASPAY_USER_ID (not empty)
   - FASPAY_PASSWORD (not empty)
   
2. Run: php artisan config:cache
   (Clear cached config)

3. Test with: https://survey.ddev.site/faspay/debug
   (Should show "configured": true)
```

### Problem: "Signature validation failed"

**Solution:**
```
1. Verify credentials match exactly:
   - User ID di .env = User ID di Faspay dashboard
   - Password di .env = Password di Faspay dashboard
   
2. Check logs in: storage/logs/

3. Signature calculation (in FaspayService):
   signature = SHA1(MD5(user_id + password + bill_no + payment_status_code))
   
4. Ensure clocks are synchronized (server time match Faspay server)
```

### Problem: Webhook tidak diterima / Transaction tidak update

**Solution:**
```
1. Verify webhook URL registered di Faspay dashboard:
   - Settings → Webhook Configuration
   - Should be: https://survey.ddev.site/api/webhook/faspay/notification

2. Check application logs:
   tail -f storage/logs/laravel.log

3. Verify CSRF token middleware is disabled for webhook:
   - Route: withoutMiddleware(VerifyCsrfToken::class) ✓

4. Test webhook manually:
   curl -X POST https://survey.ddev.site/api/webhook/faspay/notification \
     -H "Content-Type: application/json" \
     -d '{"bill_no":"TEST-123","payment_status_code":"2","trx_id":"123",...}'

5. Verify transaction exists:
   - Check database: SELECT * FROM faspay_test_transactions WHERE bill_no='...'
```

### Problem: "Transaction not found" when paying

**Solution:**
```
1. Verify bill_no was saved correctly:
   SELECT * FROM faspay_test_transactions WHERE bill_no='...';

2. Transaction might have expired:
   - Check expires_at column vs current time
   - Recreate transaction

3. Check transaction status:
   - If already paid, must go to payment page again
```

### Problem: Redirect to payment gateway not working

**Solution:**
```
1. Check if Faspay credentials are valid:
   - Test at Faspay simulator: https://simulator.faspay.co.id/
   - Verify credentials in .env

2. Check API response:
   - Enable detailed logging: FASPAY_LOGGING_ENABLED=true
   - Check logs in: storage/logs/

3. Test API endpoint manually:
   php artisan tinker
   >>> $service = new \App\Services\FaspayService();
   >>> $response = $service->createInvoice([...]);
   >>> dd($response);
```

### Problem: Cannot access test transaction pages

**Solution:**
```
1. Make sure you're logged in
   - Middleware requires auth
   - Login at: /login

2. Check routes are registered:
   php artisan route:list | grep faspay

3. Clear route cache:
   php artisan route:clear
   php artisan config:clear
```

---

## Logging

Semua Faspay activities di-log ke:

```
storage/logs/laravel.log
```

**Log Levels:**
- `INFO`: Normal operations (invoice creation, webhook received)
- `WARNING`: Non-critical issues (signature validation failed)
- `ERROR`: Critical errors (API connection failed)

**Enable/Disable Logging:**
```env
FASPAY_LOGGING_ENABLED=true   # true untuk log, false untuk skip logging
```

---

## API Reference

### FaspayService Methods

```php
// Create invoice at Faspay
$service->createInvoice(array $data): array

// Validate notification signature
$service->validateNotificationSignature(array $data): bool

// Handle webhook notification
$service->handleNotification(array $data): array

// Get payment status
$service->getPaymentStatus(string $billNo): array

// Generate signature
$service->generateSignature(string $billNo, string $paymentStatusCode): string

// Check if configured
$service->isConfigured(): bool

// Get payment channels
$service->getPaymentChannels(): array

// Get supported channels list
$service->getSupportedChannels(): array
```

### FaspayTestTransaction Model Methods

```php
// Check if transaction is expired
$transaction->isExpired(): bool

// Check if payment is completed
$transaction->isPaid(): bool

// Mark as paid
$transaction->markAsPaid(array $paymentData): void

// Mark as failed
$transaction->markAsFailed(string $reason): void

// Get formatted amount
$transaction->getFormattedAmountAttribute(): string

// Scopes
$query->unpaid()           // Get unpaid transactions
$query->paid()             // Get paid transactions
$query->expired()          // Get expired transactions
$query->active()           // Get active (not paid, not expired)
```

---

## Production Deployment

Saat deploy ke production:

1. **Update environment:**
   ```env
   FASPAY_ENV=production
   FASPAY_WEBHOOK_NOTIFICATION_URL=https://surveycenter.co.id/api/webhook/faspay/notification
   FASPAY_WEBHOOK_RETURN_URL=https://surveycenter.co.id/transaction/faspay/return
   ```

2. **Register webhook URLs di Faspay production:**
   - Login ke https://merchant.faspay.co.id/ (production account)
   - Settings → Webhook Configuration
   - Register production URLs

3. **Get production credentials:**
   - Use production merchant ID, user ID, password
   - Update .env accordingly

4. **Disable development features:**
   - Simulate payment button akan auto-disable di production
   - Debug endpoint akan return 403

5. **Test in sandbox first:**
   - Use sandbox environment untuk testing
   - Pindah ke production setelah fully tested

---

## Related Documentation

- Faspay API Reference: https://docs.faspay.co.id/
- Faspay Xpress: https://docs.faspay.co.id/merchant-integration/api-reference-1/xpress
- Payment Notification: https://docs.faspay.co.id/merchant-integration/api-reference-1/debit-transaction/payment-notification
- Return URL: https://docs.faspay.co.id/merchant-integration/api-reference-1/debit-transaction/url-callback-return-url
- Faspay Simulator: https://simulator.faspay.co.id/simulator

---

## Support

Untuk bantuan dan pertanyaan:

1. Check application logs: `storage/logs/laravel.log`
2. Review Faspay documentation
3. Test di Faspay simulator
4. Check webhook delivery at Faspay dashboard

---

**Last Updated:** March 19, 2026  
**Version:** 1.0

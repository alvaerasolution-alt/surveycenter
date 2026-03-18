# Faspay Integration - Quick Start Guide

## ⚡ Setup dalam 5 Menit

### 1. Configure .env
```bash
# Copy ke .env Anda:
FASPAY_ENV=sandbox
FASPAY_MERCHANT_ID=your_merchant_id
FASPAY_USER_ID=your_user_id
FASPAY_PASSWORD=your_password
FASPAY_API_KEY=your_api_key
FASPAY_WEBHOOK_NOTIFICATION_URL=https://survey.ddev.site/api/webhook/faspay/notification
FASPAY_WEBHOOK_RETURN_URL=https://survey.ddev.site/transaction/faspay/return
```

### 2. Run Migration
```bash
php artisan migrate
```

### 3. Register Webhooks di Faspay Dashboard
- Go to: https://merchant.faspay.co.id/
- Settings → Webhook/Callback Configuration
- Add:
  - Notification: `https://survey.ddev.site/api/webhook/faspay/notification`
  - Return: `https://survey.ddev.site/transaction/faspay/return`

### 4. Test
- Go to: https://survey.ddev.site/faspay/test/transactions
- Create transaction
- Complete payment in Faspay simulator

---

## 📍 Akses URLs

### User Routes (Perlu Login)
- **List transactions:** `/faspay/test/transactions`
- **Create transaction:** `/faspay/test/transactions/create`
- **Payment page:** `/faspay/test/transactions/{id}/payment`
- **View detail:** `/faspay/test/transactions/{id}`

### Webhook URLs (Auto-called oleh Faspay)
- **Notification:** `POST /api/webhook/faspay/notification`
- **Return:** `GET /transaction/faspay/return`

### Debug (Dev Only)
- **Config check:** `/faspay/debug`
- **List transactions:** `/faspay/list-transactions` (JSON)

---

## 📊 Database

### Table: `faspay_test_transactions`
```sql
id              - Unique ID
bill_no         - Order number (unique)
amount          - Transaction amount
status          - unpaid|processing|paid|failed|expired|cancelled
trx_id          - Faspay transaction ID
payment_channel - QRIS|VA|EWALLET|etc
payment_date    - When payment completed
customer_*      - Customer info
payment_response- Full webhook response (JSON)
created_at      - Created timestamp
expires_at      - Expiration time
```

---

## 🔄 Payment Flow

```
User creates test transaction
    ↓
Redirected to payment page
    ↓
Click "Proceed to Payment"
    ↓
Redirected to Faspay payment gateway
    ↓
Customer complete payment
    ↓
Faspay send webhook notification
    ↓
Transaction status updated to 'paid'
    ↓
Customer redirected to success page
```

---

## 🔐 Webhook Security

Signature validation:
```
signature = SHA1(MD5(user_id + password + bill_no + payment_status_code))
```

- Automatically validated in `FaspayController@notification()`
- Failed validation returns response code `99`

---

## 🧪 Testing

### Manual Simulation (Dev Only)
In payment page, click "Simulate Success Payment" button to instantly mark transaction as paid.

### Faspay Simulator
Open: https://simulator.faspay.co.id/simulator
- Enter same merchant ID, order number, amount
- Select payment method
- Click "Simulate Payment"
- Webhook sent automatically

### Manual Webhook Test
```bash
curl -X POST https://survey.ddev.site/api/webhook/faspay/notification \
  -H "Content-Type: application/json" \
  -d '{
    "bill_no": "TEST-xxx",
    "payment_status_code": "2",
    "trx_id": "123",
    "payment_channel": "QRIS",
    "signature": "xxx"
  }'
```

---

## 📋 Payment Status Codes

| Code | Meaning |
|------|---------|
| 0 | Unprocessed |
| 1 | Processing |
| 2 | **Success** ✓ |
| 3 | Failed ✗ |
| 4 | Reversal |
| 5 | Bill Not Found |
| 7 | Expired |
| 8 | Cancelled |
| 9 | Unknown |

---

## 🐛 Troubleshooting

### "Faspay is not configured"
```bash
# Check .env for FASPAY_MERCHANT_ID, etc
# Run: php artisan config:cache
# Test: https://survey.ddev.site/faspay/debug
```

### "Signature validation failed"
```
1. Verify User ID & Password in .env match Faspay dashboard
2. Check server time synchronization
3. Review logs: storage/logs/laravel.log
```

### "Webhook not received"
```
1. Verify webhook URL registered in Faspay dashboard
2. Check if CSRF middleware disabled for webhook route
3. Test manually with curl
4. Check firewall/network access
```

### "Transaction not found"
```
1. Verify bill_no in webhook matches database
2. Check if transaction hasn't expired
3. Review logs for transaction creation
```

---

## 📁 Files Created

```
config/faspay.php
app/Services/FaspayService.php
app/Http/Controllers/FaspayTestTransactionController.php
app/Http/Controllers/FaspayController.php
app/Models/FaspayTestTransaction.php
database/migrations/2026_03_18_184751_create_faspay_test_transactions_table.php
resources/views/faspay/test-transactions/index.blade.php
resources/views/faspay/test-transactions/create.blade.php
resources/views/faspay/test-transactions/payment.blade.php
resources/views/faspay/test-transactions/success.blade.php
resources/views/faspay/test-transactions/show.blade.php
resources/views/faspay/return/pending.blade.php
resources/views/faspay/return/success.blade.php
resources/views/faspay/return/error.blade.php
FASPAY_INTEGRATION.md
FASPAY_QUICKSTART.md (this file)
```

---

## 📚 Full Documentation

See: `FASPAY_INTEGRATION.md` for complete guide with:
- Detailed setup instructions
- API reference
- Production deployment
- Advanced troubleshooting
- Schema documentation

---

## ✅ Checklist

- [ ] Add Faspay credentials to .env
- [ ] Run migration: `php artisan migrate`
- [ ] Register webhook URLs in Faspay dashboard
- [ ] Test payment flow
- [ ] Check logs in `storage/logs/`
- [ ] Verify webhook receiving
- [ ] Test success flow end-to-end

---

## 🚀 Next Steps

1. **For Development:**
   - Use sandbox credentials
   - Test with Faspay simulator
   - Use simulate payment button for quick testing

2. **For Production:**
   - Get production credentials from Faspay
   - Update .env with production values
   - Register production webhook URLs
   - Test thoroughly before going live

---

**Questions?** Check `FASPAY_INTEGRATION.md` or application logs!

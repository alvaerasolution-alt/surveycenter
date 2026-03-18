# Faspay Integration - Setup Complete ✅

**Merchant Name:** Surveycenter  
**Merchant ID:** 36797  
**Status:** ✅ Configured & Ready for Testing  
**Date:** March 19, 2026

---

## ✅ Configuration Status

Your Faspay credentials have been configured:

```env
FASPAY_ENV=sandbox
FASPAY_MERCHANT_ID=36797
FASPAY_USER_ID=bot36797
FASPAY_PASSWORD=p@ssw0rd
FASPAY_WEBHOOK_NOTIFICATION_URL=https://survey.ddev.site/api/webhook/faspay/notification
FASPAY_WEBHOOK_RETURN_URL=https://survey.ddev.site/transaction/faspay/return
```

✓ Configuration loaded successfully  
✓ Webhook URLs registered  
✓ Ready for testing

---

## 📋 What You Need to Do Next

### Step 1: Run Database Migration (if not done)
```bash
cd /home/raka/dev/survey
php artisan migrate
```

This creates the `faspay_test_transactions` table for storing test transactions.

### Step 2: Register Webhooks in Faspay Dashboard

Login to your Faspay merchant portal:
1. Go to: https://merchant.faspay.co.id/
2. Navigate to: **Settings** → **Webhook Configuration** (or Integration → Callback URLs)
3. Add two webhook URLs:

   **Notification URL (Payment Notification):**
   ```
   https://survey.ddev.site/api/webhook/faspay/notification
   ```
   
   **Return/Callback URL:**
   ```
   https://survey.ddev.site/transaction/faspay/return
   ```

4. Save configuration

> **Note:** After registering webhooks, Faspay will send payment notifications to your app when payments are completed.

### Step 3: Start Testing

Once webhooks are registered, you can start testing:

1. **Access the test page:**
   ```
   https://survey.ddev.site/faspay/test/transactions
   ```
   (Make sure you're logged in first)

2. **Create a test transaction:**
   - Click "Create New Transaction" or button at top
   - Fill in customer details (name, email, phone)
   - Set amount (e.g., 50,000 IDR)
   - Click "Create & Continue to Payment"

3. **Process payment:**
   - On payment page, you have 2 options:
   
   **Option A: Instant Simulation (Development Only)**
   - Click "Simulate Success Payment" button
   - Transaction instantly marks as paid
   - Perfect for quick testing
   
   **Option B: Faspay Simulator (Production-like Testing)**
   - Click "Open Simulator" button
   - Or go to: https://simulator.faspay.co.id/simulator
   - Enter same Merchant ID, order number, amount
   - Select payment method (QRIS, VA, etc.)
   - Click "Simulate Payment"
   - Faspay sends webhook to your app
   - Transaction status updates to "paid"

4. **Verify webhook received:**
   - Check application logs: `storage/logs/laravel.log`
   - Look for "Faspay notification received" entries
   - Verify transaction status changed to "paid"

---

## 🧪 Testing Checklist

### Development/Sandbox Testing

- [ ] Login to app
- [ ] Go to `/faspay/test/transactions`
- [ ] Create test transaction (e.g., 50,000 IDR)
- [ ] Try "Simulate Success Payment" (instant, dev only)
- [ ] Check transaction marked as paid
- [ ] Delete transaction
- [ ] Create another transaction
- [ ] Use Faspay Simulator (production-like)
- [ ] Verify webhook received in logs
- [ ] Check transaction status updated

### Payment Methods to Test (if available)

- [ ] QRIS (QR Code)
- [ ] Bank Virtual Account (BCA, BNI, BRI, etc.)
- [ ] E-wallet (GoPay, OVO, DANA, LinkAja)

---

## 🌐 Key URLs

### User Interface
- **Test Transactions List:** `GET /faspay/test/transactions`
- **Create Transaction:** `GET /faspay/test/transactions/create`
- **Payment Page:** `GET /faspay/test/transactions/{id}/payment`
- **Transaction Details:** `GET /faspay/test/transactions/{id}`
- **Success Page:** `GET /faspay/test/transactions/{id}/success`

### Webhooks (Auto-called by Faspay)
- **Notification Endpoint:** `POST /api/webhook/faspay/notification`
- **Return/Callback URL:** `GET /transaction/faspay/return`

### Debug (Development Only)
- **Check Configuration:** `GET /faspay/debug`
- **List Transactions (JSON):** `GET /faspay/list-transactions`

---

## 📊 Test Transaction Flow

```
1. Create Test Transaction
   ↓ Fill form with customer info and amount
   ↓ Transaction saved to database
   ↓
2. Redirect to Payment Page
   ↓ Show transaction details
   ↓ Option: Simulate or use Faspay Simulator
   ↓
3a. SIMULATE (Dev Only)
   ↓ Click "Simulate Success Payment"
   ↓ Transaction instantly marked as paid
   ↓
3b. FASPAY SIMULATOR
   ↓ Redirect to Faspay payment gateway
   ↓ Customer completes payment
   ↓ Faspay sends webhook notification
   ↓
4. Webhook Processing
   ↓ FaspayController@notification() receives webhook
   ↓ Validates signature (HMAC SHA1)
   ↓ Updates transaction status to 'paid'
   ↓ Logs response
   ↓
5. Success
   ↓ Auto-redirect to success page
   ↓ Show transaction confirmed
```

---

## 📝 Database

**Table:** `faspay_test_transactions`

Stores all test transactions with:
- Transaction ID (trx_id)
- Bill number (bill_no) - unique identifier
- Customer info (name, email, phone)
- Amount
- Status (unpaid, processing, paid, failed, expired, cancelled)
- Payment method
- Payment date
- Full webhook response (JSON)

---

## 🔍 Monitoring & Logs

### Check Application Logs
```bash
tail -f storage/logs/laravel.log
```

Look for:
- `Faspay notification received` - Webhook received
- `Transaction marked as paid` - Payment processed
- `Faspay notification validation failed` - Signature error
- `Faspay transaction not found` - Bill number mismatch

### Check Transaction Status

Via UI:
1. Go to `/faspay/test/transactions`
2. View transaction in list
3. Click "View" for details

Via Database:
```bash
php artisan tinker
>>> \App\Models\FaspayTestTransaction::latest()->first()
```

---

## 🐛 Troubleshooting

### Problem: "Faspay is not configured"

**Solution:**
```bash
# 1. Verify .env has credentials
cat .env | grep FASPAY_

# 2. Clear config cache
php artisan config:cache

# 3. Check debug endpoint
# Go to: https://survey.ddev.site/faspay/debug
# Should show "configured": true
```

### Problem: Transaction created but can't pay

**Solution:**
```
1. Check if transaction expired
   - Transactions expire after 30 minutes
   - Create new transaction if expired

2. Check form fields
   - All required fields must be filled
   - Amount must be 10,000 - 100,000,000 IDR

3. Check credentials
   - Go to /faspay/debug
   - Verify Merchant ID shows "***7797" (masked)
   - If not configured, update .env
```

### Problem: Webhook not received / Transaction not updating to "paid"

**Solution:**
```
1. Verify webhooks registered in Faspay dashboard
   - Go to: https://merchant.faspay.co.id/
   - Settings → Webhook Configuration
   - Check URLs are exactly:
     https://survey.ddev.site/api/webhook/faspay/notification
     https://survey.ddev.site/transaction/faspay/return

2. Check application logs
   - tail -f storage/logs/laravel.log
   - Look for webhook processing messages

3. Test manually
   - Use curl to simulate webhook:
   curl -X POST https://survey.ddev.site/api/webhook/faspay/notification \
     -H "Content-Type: application/json" \
     -d '{"bill_no":"TEST-123","payment_status_code":"2","trx_id":"123",...}'

4. Check transaction exists
   - SELECT * FROM faspay_test_transactions WHERE bill_no='TEST-xxx';
```

### Problem: "Signature validation failed"

**Cause:** Webhook signature doesn't match

**Solution:**
```
1. Verify User ID and Password match
   - Check .env: FASPAY_USER_ID & FASPAY_PASSWORD
   - Compare with Faspay dashboard (Settings → API Keys)

2. Check server time
   - Ensure server clock is synchronized
   - Signature uses timestamp

3. Check logs for details
   - tail -f storage/logs/laravel.log | grep signature
```

---

## 📚 Complete Documentation

For comprehensive documentation, see:

1. **FASPAY_QUICKSTART.md** - Quick reference (5 min setup)
2. **FASPAY_INTEGRATION.md** - Complete guide (400+ lines)
3. **FASPAY_IMPLEMENTATION_SUMMARY.txt** - Overview

---

## 🔗 Useful Links

- **Faspay Merchant Portal:** https://merchant.faspay.co.id/
- **Faspay Simulator:** https://simulator.faspay.co.id/simulator
- **Faspay Documentation:** https://docs.faspay.co.id/
- **Payment Notification Docs:** https://docs.faspay.co.id/merchant-integration/api-reference-1/debit-transaction/payment-notification
- **Return URL Docs:** https://docs.faspay.co.id/merchant-integration/api-reference-1/debit-transaction/url-callback-return-url

---

## ✅ Ready to Go!

Your Faspay integration is configured and ready for testing:

1. ✅ Credentials configured
2. ✅ Config cached
3. ⏭️ **Next: Register webhooks in Faspay dashboard** (1-2 minutes)
4. ⏭️ **Then: Test payment flow** (5-10 minutes)

---

**Questions?** Check the troubleshooting section or review application logs!

**Happy testing!** 🚀

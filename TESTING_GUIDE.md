# User Dashboard - Testing Guide

## Overview
User dashboard untuk SurveyCenter sudah **fully implemented** dengan 9 pages dan semua routes.

## Pages & Routes

### 1. Dashboard Utama
- **URL**: https://survey.ddev.site/dashboard
- **Route**: `user.dashboard`
- **Features**:
  - Welcome banner dengan username
  - Stat cards: Total Surveys, Pertanyaan, Responden, Spent, Pending
  - List survey terbaru (5 item)
  - List transaksi terbaru (5 item)

### 2. Daftar Survey
- **URL**: https://survey.ddev.site/my-surveys
- **Route**: `user.surveys.index`
- **Features**:
  - Table view dengan kolom: Survey, Pertanyaan, Responden, Progress, Status, Aksi
  - Filter by status (All/Completed/In Progress/Pending)
  - Search by survey title
  - Pagination (10 per halaman)
  - Action buttons: View (eye), Delete (trash)
  - Empty state dengan CTA ke buat survey

### 3. Buat Survey Baru ⭐ [NEWLY FIXED]
- **URL**: https://survey.ddev.site/my-surveys/create
- **Route**: `user.surveys.create`
- **Features**:
  - Form dengan validasi
  - Fields: Judul, Deskripsi, Pertanyaan (1-100), Responden (1-10000)
  - Real-time cost calculator
    - Biaya per pertanyaan: Rp 1.000
    - Biaya per responden: Rp 1.000
    - Total otomatis terhitung
  - Info box tentang langkah selanjutnya
  - Submit button "Buat Survey" → membuat transaction dengan status pending

### 4. Detail Survey
- **URL**: https://survey.ddev.site/my-surveys/{id}
- **Route**: `user.surveys.show`
- **Features**:
  - Survey info header
  - Progress tracking dengan visual bar
  - Response count
  - Transaction history
  - Edit/Delete buttons
  - Authorization check (user hanya akses miliknya)

### 5. Riwayat Transaksi
- **URL**: https://survey.ddev.site/my-transactions
- **Route**: `user.transactions.index`
- **Features**:
  - Table dengan kolom: Survey, Biaya, Status, Progress, Tanggal, Aksi
  - Filter: Status, Tanggal (From-To), Sort (Terbaru/Terlama)
  - Summary cards: Total Pembayaran, Pending, Total Pengeluaran
  - View detail button
  - Empty state dengan CTA

### 6. Detail Transaksi
- **URL**: https://survey.ddev.site/my-transactions/{id}
- **Route**: `user.transactions.show`
- **Features**:
  - Transaction header dengan ID & status
  - Amount display
  - Survey info
  - Progress tracking
  - Additional transaction info
  - Status-specific info cards
  - Action buttons: Lihat Survey, Bayar Sekarang

### 7. Lihat Profil
- **URL**: https://survey.ddev.site/profile
- **Route**: `user.profile.show`
- **Features**:
  - Profile header dengan avatar
  - Info grid: Nama, Email, Status, Tanggal Bergabung
  - Stats cards: Survey Aktif, Total Transaksi, Total Pengeluaran
  - Tab navigation ke edit profile
  - Edit button

### 8. Edit Profil ⭐ [NEWLY FIXED]
- **URL**: https://survey.ddev.site/profile/edit
- **Route**: `user.profile.edit`
- **Features**:
  - Form edit: Nama, Email
  - Form ubah password (dengan konfirmasi)
  - Validation error display
  - Cancel button
  - Submit button "Simpan Perubahan"
  - Security info card
  - Tab navigation

### 9. Sidebar Navigation
Pada semua halaman user, ada sidebar dengan:
- Logo SurveyCenter
- Menu items:
  - Dashboard
  - Survey Saya
  - Transaksi
  - Profil Saya
- Topbar dengan:
  - User menu (click avatar)
  - Logout button

---

## Testing Checklist

### Pre-requisite
- [ ] User sudah login dengan akun test (test@example.com / password)
- [ ] DDEV running: `ddev status`
- [ ] Browser cache cleared (Ctrl+Shift+Delete)

### Dashboard Pages
- [ ] /dashboard loads with stats
- [ ] /my-surveys shows list (or empty state)
- [ ] /my-surveys/create form renders properly
  - [ ] Input fields visible
  - [ ] Cost calculator works (change questions/respondents)
  - [ ] Form can be submitted
- [ ] /my-surveys/{id} shows survey detail
- [ ] /my-transactions shows list or empty
- [ ] /my-transactions/{id} shows transaction detail
- [ ] /profile shows user info
- [ ] /profile/edit shows form
  - [ ] Can edit name/email
  - [ ] Can change password
  - [ ] Form submits correctly

### Functionality Tests
- [ ] Create new survey → Auto-creates transaction
- [ ] Survey list filters work (status, search)
- [ ] Transaction list filters work (status, date range, sort)
- [ ] Progress bars display correctly
- [ ] Status badges show correct color
- [ ] Empty states show helpful messages with CTAs
- [ ] Authorization works (can't access other users' surveys)
- [ ] Pagination works
- [ ] Form validation works

### UI/UX Tests
- [ ] Orange theme consistent across pages
- [ ] Responsive on mobile (sidebar collapses)
- [ ] Icons render (Lucide icons)
- [ ] Forms have proper error styling
- [ ] Success messages appear after actions
- [ ] Navigation works from sidebar & buttons

---

## Common Issues & Solutions

### Issue: Page shows "Redirect to login"
- **Cause**: Not authenticated
- **Solution**: Login first at https://survey.ddev.site/login

### Issue: Cost calculator not updating
- **Cause**: JavaScript not loaded / cached
- **Solution**:
  - Clear browser cache (Ctrl+Shift+Delete)
  - Hard refresh (Ctrl+Shift+R)
  - Check browser console for errors (F12)

### Issue: Form doesn't submit
- **Cause**: Validation error or CSRF token issue
- **Solution**:
  - Check validation error messages on form
  - Make sure all required fields filled
  - Clear session: Logout & login again

### Issue: 403 Forbidden error
- **Cause**: Trying to access survey of another user
- **Solution**: Only view your own surveys

### Issue: Icons don't show (just boxes)
- **Cause**: Lucide icons CDN not loading
- **Solution**:
  - Check internet connection
  - Clear browser cache
  - Check browser console (F12)

---

## Database Test Data

### Test User
- Email: test@example.com
- ID: 1

### Sample Data
- Surveys: ID 103 (user_id=1, title="tes"), ID 107 (user_id=1, title="Test Survey")
- Transactions: 2 records for user 1

---

## Routes Summary

All routes require authentication (`auth` middleware):

```
GET     /dashboard              → user.dashboard
GET     /history                → user.history
GET     /my-surveys             → user.surveys.index
GET     /my-surveys/create      → user.surveys.create
POST    /my-surveys             → user.surveys.store
GET     /my-surveys/{survey}    → user.surveys.show
GET     /my-surveys/{survey}/edit → user.surveys.edit
PUT     /my-surveys/{survey}    → user.surveys.update
DELETE  /my-surveys/{survey}    → user.surveys.destroy
GET     /my-transactions        → user.transactions.index
GET     /my-transactions/{transaction} → user.transactions.show
GET     /profile                → user.profile.show
GET     /profile/edit           → user.profile.edit
POST    /profile                → user.profile.update
```

---

## Implementation Details

### Controllers
- `App\Http\Controllers\User\DashboardController` - Dashboard stats
- `App\Http\Controllers\User\SurveyController` - Survey CRUD
- `App\Http\Controllers\User\TransactionController` - Transaction list & detail
- `App\Http\Controllers\ProfileController` - Profile view & edit

### Views (with user layout)
- `layouts/user.blade.php` - Main layout dengan sidebar orange
- `user/dashboard/index.blade.php`
- `user/surveys/index.blade.php`
- `user/surveys/create.blade.php` ⭐ NEW
- `user/surveys/show.blade.php`
- `user/transactions/index.blade.php`
- `user/transactions/show.blade.php`
- `user/profile/edit.blade.php` ⭐ NEW
- `user/profile/show.blade.php` (updated)

### Models & Relationships
- `User` → hasMany `Survey`, `Transaction`
- `Survey` → belongsTo `User`, hasMany `Response`, `Transaction`
- `Transaction` → belongsTo `User`, `Survey`

---

## Next Steps (Future)

- [ ] Payment gateway integration (Faspay/Singapay)
- [ ] Real-time survey progress updates
- [ ] Email notifications
- [ ] Survey export to PDF
- [ ] Analytics dashboard
- [ ] Team management
- [ ] Survey templates

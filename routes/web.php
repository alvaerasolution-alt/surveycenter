<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TabController;
use App\Http\Controllers\Admin\PartnerLogoController;
use App\Http\Controllers\Admin\CustomerStoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\DiscountBannerController;
use App\Http\Controllers\Admin\TestimoniController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SingaPayController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\TransactionProgressController as AdminTransactionProgressController;
use App\Http\Controllers\Admin\ResponseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\OrderController;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

Route::get('/', [HomeController::class, 'index'])->name('landing');

Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('user.dashboard');

    Route::get('/history', [TransactionController::class, 'history'])
        ->name('user.history');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/pricing', function () {
    $terms = \App\Models\Setting::where('key', 'terms_content')->value('value') ?? '';
    return view('pages.pricing', compact('terms'));
})->name('pricing');

Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/set-sitemap', function () {
    $urls = [
        // 'https://surveycenter.co.id/',
        'https://surveycenter.co.id/about',
        'https://surveycenter.co.id/layanan/survei-kepuasan-pelanggan',
        'https://surveycenter.co.id/layanan/survei-potensi-pasar',
        'https://surveycenter.co.id/layanan/survei-loyalitas-pelanggan',
        'https://surveycenter.co.id/layanan/survei-pengembangan-produk-jasa',
        'https://surveycenter.co.id/layanan/survei-pengukuran-indeks-kepusasan-masyarakat',
        'https://surveycenter.co.id/layanan/survei-brand-awareness',
        'https://surveycenter.co.id/layanan/survei-segmentasi-dan-positioning',
        'https://surveycenter.co.id/layanan/mysteri-shopper',
        'https://surveycenter.co.id/layanan/study-kelayakan-bisnis',
        'https://surveycenter.co.id/layanan/retail-audit',
        'https://surveycenter.co.id/pricing',
        'https://surveycenter.co.id/blog',
        'https://surveycenter.co.id/contact',
        'https://surveycenter.co.id/login',
        'https://surveycenter.co.id/register',
        'https://surveycenter.co.id/storage/assets/Client%20Brief%20Veycat.pdf',
        'https://surveycenter.co.id/mengapa-riset-pasar-itu-penting-untuk-bisnis',
    ];
    $path = public_path('sitemap.xml');
    // SitemapGenerator::create('http://localhost:8000')->writeToFile($path);
    $sitemap = SitemapGenerator::create('https://surveycenter.co.id')->getSitemap();
    $sitemap->add(
        Url::create('https://surveycenter.co.id/')
            ->setLastModificationDate(now())
            ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(1.0)
    );
    foreach ($urls as $url) {
        $sitemap->add(
            Url::create($url)
                ->setLastModificationDate(now())
                ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.7)
        );
    }
    $articles = \App\Models\Article::all();
    foreach ($articles as $article) {
        $sitemap->add(
            Url::create('https://surveycenter.co.id/' . $article->slug)
                ->setLastModificationDate($article->updated_at)
                ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_NEVER)
                ->setPriority(0.8)
        );
    }
    $sitemap->writeToFile($path);
    // SitemapGenerator::create('https://surveycenter.co.id')->writeToFile($path);
});



Route::post('/crm/customers/store', [HomeController::class, 'storeCustomer'])->name('crm.customers.store.user');
Route::post('/crm/customers', [CustomerController::class, 'store'])->name('crm.customers.store');
Route::get('/customer-form', [CustomerController::class, 'create'])->name('customers.create');
Route::post('/customer-form', [CustomerController::class, 'store'])->name('customers.store');

Route::middleware(['auth'])->group(function () {
    Route::resource('customers', CustomerController::class)->except(['create', 'store']);
});



Route::get('/cart', [TransactionController::class, 'cart'])
    ->name('cart.index')
    ->middleware('auth');

Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category?}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index'); // list semua artikel
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show'); // detail artikel

// route dinamis halaman layanan
Route::get('/layanan/{slug}', [App\Http\Controllers\LayananController::class, 'show'])
    ->name('layanan.show');


Route::middleware(['auth'])->group(function () {

    Route::resource('surveys', SurveyController::class);
    Route::post('surveys/transaction', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('surveys/{survey}/transaction', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('surveys/{survey}/transaction', [TransactionController::class, 'store'])->name('transactionss.store');

    Route::get('transactions/{transaction}/payment', [TransactionController::class, 'payment'])->name('transactions.payment');
    Route::post('transactions/{transaction}/payment', [TransactionController::class, 'processPayment'])->name('transactions.processPayment');
    Route::get('transactions/{transaction}/transfer', [TransactionController::class, 'showTransfer'])->name('transactions.showTransfer');
    Route::get('transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])
        ->name('transactions.invoice');
    Route::get('transactions/{transaction}/download', [TransactionController::class, 'download'])
        ->name('transactions.download');
    Route::get('/transactions/{transaction}/progress', [App\Http\Controllers\TransactionProgressController::class, 'show'])->middleware('auth')->name('transactions.progress');
    // Halaman form upload
    Route::get('/transactions/{transaction}/payment-proof', [PaymentProofController::class, 'create'])
        ->name('payment-proofs.create');

    // Proses upload
    Route::post('/transactions/{transaction}/payment-proof', [PaymentProofController::class, 'store'])
        ->name('payment-proofs.store');

    Route::get('/transactions/{transaction}/qris-debug', [SingaPayController::class, 'generateQrisDebug']);


    Route::get('singapay/pay/{transaction}', [SingaPayController::class, 'pay'])->name('singapay.pay');
    Route::post('singapay/callback', [SingaPayController::class, 'callback'])->name('singapay.callback');
});


// Login Admin
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Dashboard (hanya admin)
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/pilih-dashboard', function () {
        return view('admin.auth.pilih-dashboard');
    })->name('pilih-dashboard');

    Route::get('/pilih-client', [CRMController::class, 'clientMenu'])->name('pilih-client');

    Route::get('/crm/dashboard', [CRMController::class, 'index'])->name('crm.dashboard');
    Route::get('/crm/customer-already', [CRMController::class, 'customerAlready'])->name('crm.customer-already');

    // Follow Up khusus status closed
    Route::get('/followups/closed', [FollowUpController::class, 'closed'])->name('followups.closed');

    // CRUD utama
    Route::resource('followups', FollowUpController::class);


    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // SEO Management
    Route::get('/seo', [\App\Http\Controllers\Admin\SeoController::class, 'index'])->name('admin.seo.index');
    Route::put('/seo', [\App\Http\Controllers\Admin\SeoController::class, 'update'])->name('admin.seo.update');

    // Syarat & Ketentuan
    Route::get('/terms', [App\Http\Controllers\Admin\SettingController::class, 'terms'])->name('admin.terms.edit');
    Route::post('/terms', [App\Http\Controllers\Admin\SettingController::class, 'updateTerms'])->name('admin.terms.update');

    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Tab Management (CRUD)
    Route::get('tabs', [TabController::class, 'index'])->name('tabs.index');
    Route::get('tabs/create', [TabController::class, 'create'])->name('tabs.create');
    Route::post('tabs', [TabController::class, 'store'])->name('tabs.store');
    Route::get('tabs/{tab}/edit', [TabController::class, 'edit'])->name('tabs.edit');
    Route::put('tabs/{tab}', [TabController::class, 'update'])->name('tabs.update');
    Route::delete('tabs/{tab}', [TabController::class, 'destroy'])->name('tabs.destroy');

    // Partner Logos (CRUD)
    Route::get('partner-logos', [PartnerLogoController::class, 'index'])->name('partner-logos.index');
    Route::get('partner-logos/create', [PartnerLogoController::class, 'create'])->name('partner-logos.create');
    Route::post('partner-logos', [PartnerLogoController::class, 'store'])->name('partner-logos.store');
    Route::get('partner-logos/{partnerLogo}/edit', [PartnerLogoController::class, 'edit'])->name('partner-logos.edit');
    Route::put('partner-logos/{partnerLogo}', [PartnerLogoController::class, 'update'])->name('partner-logos.update');
    Route::delete('partner-logos/{partnerLogo}', [PartnerLogoController::class, 'destroy'])->name('partner-logos.destroy');

    // Testimoni Images
    Route::get('testimoni', [TestimoniController::class, 'index'])->name('admin.testimoni.index');
    Route::post('testimoni', [TestimoniController::class, 'store'])->name('admin.testimoni.store');
    Route::delete('testimoni/{testimoni}', [TestimoniController::class, 'destroy'])->name('admin.testimoni.destroy');
    Route::post('testimoni/{testimoni}/toggle', [TestimoniController::class, 'toggleActive'])->name('admin.testimoni.toggle');

    // Customer Stories CRUD
    Route::get('customer-stories', [CustomerStoryController::class, 'index'])->name('customer-stories.index');
    Route::get('customer-stories/create', [CustomerStoryController::class, 'create'])->name('customer-stories.create');
    Route::post('customer-stories', [CustomerStoryController::class, 'store'])->name('customer-stories.store');
    Route::get('customer-stories/{customerStory}/edit', [CustomerStoryController::class, 'edit'])->name('customer-stories.edit');
    Route::put('customer-stories/{customerStory}', [CustomerStoryController::class, 'update'])->name('customer-stories.update');
    Route::delete('customer-stories/{customerStory}', [CustomerStoryController::class, 'destroy'])->name('customer-stories.destroy');

    // Article CRUD
    // ✅ Semua route berada di bawah /admin/...
    Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('layanan', AdminLayananController::class);

        Route::resource('discount-banners', DiscountBannerController::class);
    });

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('transactions', AdminTransactionController::class);
    });

    // Halaman daftar transaksi paid
    Route::get('/transactions/progress', [AdminTransactionProgressController::class, 'index'])
        ->name('admin.transactions.progress.index');

    // Form update progress
    Route::get('/transactions/{transaction}/progress', [AdminTransactionProgressController::class, 'edit'])
        ->name('admin.transactions.progress.edit');

    // Aksi update progress
    Route::put('/transactions/{transaction}/progress', [AdminTransactionProgressController::class, 'update'])
        ->name('admin.transactions.progress.update');

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('responses', ResponseController::class);
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('payment-proofs', [\App\Http\Controllers\Admin\PaymentProofController::class, 'index'])->name('payment-proofs.index');
    });
});

<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CounterController as AdminCounterController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\HomepageCarouselController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\ProductCategoryController as AdminProductCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductSubcategoryController as AdminProductSubcategoryController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\UserAccountController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\TeamController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place')->middleware('auth');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog:slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/gallery/photos', [GalleryController::class, 'photos'])->name('gallery.photos');
Route::get('/gallery/videos', [GalleryController::class, 'videos'])->name('gallery.videos');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.submit');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/profile', [UserAccountController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::post('/dashboard/password', [UserAccountController::class, 'updatePassword'])->name('dashboard.password.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [AdminAuthController::class, 'showProfile'])->name('profile');
        Route::get('/password', [AdminAuthController::class, 'showPasswordForm'])->name('password.edit');
        Route::post('/password', [AdminAuthController::class, 'updatePassword'])->name('password.update');

        Route::get('/page-content', [PageContentController::class, 'edit'])->name('page-content.edit')->middleware('permission:Web_Settings');
        Route::post('/page-content', [PageContentController::class, 'update'])->name('page-content.update')->middleware('permission:Web_Settings');

        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->middleware('permission:manage users');
        Route::resource('roles', RoleController::class)->except(['show'])->middleware('permission:manage roles');
        Route::resource('permissions', PermissionController::class)->except(['show'])->middleware('permission:manage permissions');

        Route::resource('homepage-carousel-images', HomepageCarouselController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');
        Route::resource('counters', AdminCounterController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');
        Route::resource('testimonials', AdminTestimonialController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');
        Route::resource('faqs', AdminFaqController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');

        Route::resource('team-members', AdminTeamMemberController::class)->except(['show'])->middleware('permission:Web_Settings');
        Route::resource('blogs', AdminBlogController::class)->except(['show'])->middleware('permission:Web_Settings');
        Route::resource('galleries', AdminGalleryController::class)->except(['show'])->middleware('permission:Web_Settings');
        Route::get('/about', [AdminAboutController::class, 'edit'])->name('about.edit')->middleware('permission:Web_Settings');
        Route::post('/about', [AdminAboutController::class, 'update'])->name('about.update')->middleware('permission:Web_Settings');
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit')->middleware('permission:Web_Settings');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:Web_Settings');


        Route::resource('product-categories', AdminProductCategoryController::class)->except(['show'])->middleware('permission:manage products');
        Route::resource('product-subcategories', AdminProductSubcategoryController::class)->except(['show'])->middleware('permission:manage products');
        Route::get('products/export/excel', [AdminProductController::class, 'exportExcel'])->name('products.export.excel')->middleware('permission:manage products');
        Route::get('products/export/pdf', [AdminProductController::class, 'exportPdf'])->name('products.export.pdf')->middleware('permission:manage products');
        Route::resource('products', AdminProductController::class)->except(['show'])->middleware('permission:manage products');
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'edit', 'update'])->middleware('permission:manage products');
        Route::resource('services', AdminServiceController::class)->except(['show'])->middleware('permission:manage services');
    });
});

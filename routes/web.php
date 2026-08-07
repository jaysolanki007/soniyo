<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

/*
|--------------------------------------------------------------------------
| Admin authentication
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [LoginController::class, 'show'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.attempt');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin panel (protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin', 'module'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // My profile (available to every admin user)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // Users & Access (super admin only — enforced by module middleware)
    Route::resource('users', UserController::class)->except(['show'])->names('users');
    Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    Route::resource('customers', CustomerController::class)->except(['show'])->names('customers');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    Route::resource('appointments', AppointmentController::class)->except(['show'])->names('appointments');
    Route::resource('services', ServiceController::class)->except(['show'])->names('services');
    Route::resource('staff', StaffController::class)->except(['show'])->names('staff')->parameters(['staff' => 'staff']);
    Route::resource('gallery', GalleryController::class)->except(['show'])->names('gallery');
    Route::resource('offers', OfferController::class)->except(['show'])->names('offers');
    Route::resource('testimonials', TestimonialController::class)->except(['show'])->names('testimonials');

    Route::get('settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SiteSettingController::class, 'update'])->name('settings.update');

    // ---- Phase 2: Inventory, Suppliers, POS, Invoices, Reports ----
    Route::resource('suppliers', SupplierController::class)->except(['show'])->names('suppliers');
    Route::resource('products', ProductController::class)->except(['show'])->names('products');
    Route::post('products/{product}/stock', [ProductController::class, 'stock'])->name('products.stock');

    Route::get('pos', [PosController::class, 'create'])->name('pos.create');
    Route::post('pos', [PosController::class, 'store'])->name('pos.store');

    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // ---- Phase 4: Commissions & Payroll ----
    Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');

    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
    Route::get('payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::get('payroll/{payroll}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');
    Route::put('payroll/{payroll}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::patch('payroll/{payroll}/paid', [PayrollController::class, 'markPaid'])->name('payroll.paid');
    Route::delete('payroll/{payroll}', [PayrollController::class, 'destroy'])->name('payroll.destroy');

    // Placeholder for modules coming in later phases
    Route::get('coming-soon', fn () => view('admin.soon'))->name('soon');
});

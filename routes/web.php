<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// GUEST
use App\Http\Controllers\WelcomeController;


// ADMIN
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\RentalAdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CustomerAdminController;
use App\Http\Controllers\Admin\ScheduleAdminController;
use App\Http\Controllers\Admin\OvertimeAdminController;



// CUSTOMERS
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\CompareController;
use App\Http\Controllers\Customer\RentalHistoryController;
use App\Http\Controllers\Customer\TestimoniController;
use App\Models\Testimoni;
use App\Http\Controllers\Customer\OvertimeController;
use App\Http\Controllers\Customer\PaymentController;



// ROUTE GUEST
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    });
});

// ROUTE ADMIN

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
         ->name('admin.dashboard.index');
});


Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

    Route::get('/admin/equipment', [EquipmentController::class, 'index'])->name('admin.equipment.index');
    Route::get('/admin/equipment/create', [EquipmentController::class, 'create'])->name('admin.equipment.create');
    Route::post('/admin/equipment/store', [EquipmentController::class, 'store'])->name('admin.equipment.store');
    Route::get('/admin/equipment/{id}/edit', [EquipmentController::class, 'edit'])->name('admin.equipment.edit');
    Route::post('/admin/equipment/{id}/update', [EquipmentController::class, 'update'])->name('admin.equipment.update');
    Route::delete('/admin/equipment/{id}/delete', [EquipmentController::class, 'destroy'])->name('admin.equipment.delete');

});


Route::patch(
    '/admin/equipment/{id}/status',
    [EquipmentController::class, 'updateStatus']
)->name('admin.equipment.updateStatus');



Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

    Route::get('/admin/customer', [CustomerAdminController::class, 'index'])->name('admin.customer.index');
    Route::get('/admin/customer/create', [CustomerAdminController::class, 'create'])->name('admin.customer.create');
    Route::post('/admin/customer/store', [CustomerAdminController::class, 'store'])->name('admin.customer.store');
    Route::get('/admin/customer/{id}/edit', [CustomerAdminController::class, 'edit'])->name('admin.customer.edit');
    Route::put('/admin/customer/{id}/update', [CustomerAdminController::class, 'update'])->name('admin.customer.update');
    Route::delete('/admin/customer/{id}/delete', [CustomerAdminController::class, 'destroy'])->name('admin.customer.delete');
});


Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/schedule', [ScheduleAdminController::class, 'index'])
        ->name('admin.schedule.index');

    Route::get('/admin/schedule/events', [ScheduleAdminController::class, 'events'])
        ->name('admin.schedule.events');

    Route::post('/admin/schedule/update', [ScheduleAdminController::class, 'update'])
        ->name('admin.schedule.update');

    Route::get('/admin/schedule/heatmap', [ScheduleAdminController::class, 'heatmap'])
        ->name('admin.schedule.heatmap');

});





Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/rentals', [RentalAdminController::class, 'index'])->name('admin.rentals.index');
    Route::post('/admin/rentals/{id}/status', [RentalAdminController::class, 'updateStatus'])->name('admin.rentals.status');
    Route::get('/admin/rentals/create', [RentalAdminController::class, 'create'])->name('admin.rentals.create');
    Route::post('/admin/rentals/store', [RentalAdminController::class, 'storeAdmin'])->name('admin.rentals.store');
    Route::get('/admin/rentals/{id}/edit', [RentalAdminController::class, 'edit'])->name('admin.rentals.edit');
    Route::put('/admin/rentals/{id}/update', [RentalAdminController::class, 'updateAdmin'])->name('admin.rentals.update');
    Route::delete('/admin/rentals/{id}', [RentalAdminController::class, 'destroy'])->name('admin.rentals.destroy');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/overtime', [OvertimeAdminController::class, 'index'])->name('admin.overtime.index');
    Route::post('/admin/overtime/{id}/approve', [OvertimeAdminController::class, 'approve'])->name('admin.overtime.approve');
    Route::post('/admin/overtime/{id}/reject', [OvertimeAdminController::class, 'reject'])->name('admin.overtime.reject');
    Route::post('/admin/overtime/{id}/stop', [OvertimeAdminController::class, 'stop'])->name('admin.overtime.stop');
    Route::delete('/admin/overtime/{id}/delete', [OvertimeAdminController::class, 'destroy'])->name('admin.overtime.delete');
    Route::post('/admin/overtime/{id}/verify', [OvertimeAdminController::class, 'verifyPayment'])->name('admin.overtime.verify_payment');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
// Testimoni Admin
    Route::get('/admin/testimonis', [App\Http\Controllers\Admin\TestimoniAdminController::class, 'index'])->name('admin.testimonis.index');
    Route::delete('/admin/testimonis/{id}', [App\Http\Controllers\Admin\TestimoniAdminController::class, 'destroy'])->name('admin.testimonis.destroy');
});

// Finance Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/finance', [App\Http\Controllers\Admin\FinanceAdminController::class, 'index'])->name('admin.finance.index');
    Route::post('/admin/finance', [App\Http\Controllers\Admin\FinanceAdminController::class, 'store'])->name('admin.finance.store');
    Route::delete('/admin/finance/{id}', [App\Http\Controllers\Admin\FinanceAdminController::class, 'destroy'])->name('admin.finance.destroy');
});






// ROUTE CUSTOMERS

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/home', function () {
        return view('customer.home', [
            'testimonis' => Testimoni::with('user')
                ->latest()
                ->get()
        ]);
    })->name('customer.home');
});

// KIRIM TESTIMONI
        Route::post('/testimoni', [TestimoniController::class, 'store'])
    ->name('testimoni.store')
    ->middleware(['auth']);

    
Route::middleware(['auth'])->group(function () {
    Route::get('/testimoni/{id}/edit', [TestimoniController::class, 'edit'])
        ->name('testimoni.edit');

    Route::put('/testimoni/{id}', [TestimoniController::class, 'update'])
        ->name('testimoni.update');

    Route::delete('/testimoni/{id}', [TestimoniController::class, 'destroy'])
        ->name('testimoni.destroy');
});



Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/rent/{id}', [RentalController::class, 'create'])->name('rent.create');
    Route::post('/rent/{id}', [RentalController::class, 'store'])->name('rent.store');
});


Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/catalog', [CatalogController::class, 'index'])
        ->name('customer.catalog');

    Route::get('/catalog/{id}', [CatalogController::class, 'show'])
        ->name('customer.catalog.show');

    Route::get('/catalog/{id}/status', [CatalogController::class, 'status'])
        ->name('customer.catalog.status');

});


Route::prefix('customer')->group(function () {
    Route::get('/compare/select', [CompareController::class, 'select'])
        ->name('customer.compare.select');

    Route::post('/compare/result', [CompareController::class, 'result'])
        ->name('customer.compare.result');
});


Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/my-rentals', [RentalHistoryController::class, 'index'])->name('customer.rentals');
    Route::get('/my-rentals/{id}', [RentalHistoryController::class, 'show'])->name('customer.rentals.show');
});

    /* PAYMENT */
Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/customer/payment/{rental}', [PaymentController::class, 'show'])
        ->name('payment.show');

    Route::post('/customer/payment/{rental}', [PaymentController::class, 'process'])
        ->name('payment.process');

    Route::get('/customer/payment/{rental}/transfer', [PaymentController::class, 'transfer'])
        ->name('payment.transfer');

    Route::post('/customer/payment/{rental}/upload', [PaymentController::class, 'uploadProof'])
        ->name('payment.upload');

    Route::post('/customer/payment/{rental}/cancel', [PaymentController::class, 'cancel'])
        ->name('payment.cancel');

});


// OVERTIME
Route::middleware(['auth', 'role:customer'])->group(function () {
        Route::post('/rentals/{id}/overtime', [OvertimeController::class, 'store'])->name('customer.overtime.store');
        Route::get('/customer/overtime/{id}/status', [OvertimeController::class, 'getStatus'])->name('customer.overtime.status');
        Route::post('/customer/overtime/{id}/stop', [OvertimeController::class, 'stop'])->name('customer.overtime.stop');
        Route::delete('/customer/overtime/{id}/cancel', [OvertimeController::class, 'cancel'])->name('customer.overtime.cancel');
});


Route::middleware(['auth', 'role:customer'])->group(function () {
    // Route Pembayaran Overtime Baru
    Route::get('/customer/overtime-payment/{id}', [PaymentController::class, 'showOvertime'])->name('payment.overtime.show');
    Route::post('/customer/overtime-payment/{id}', [PaymentController::class, 'processOvertime'])->name('payment.overtime.process');
    Route::get('/customer/overtime-payment/{id}/transfer', [PaymentController::class, 'transferOvertime'])->name('payment.overtime.transfer');
    Route::post('/customer/overtime-payment/{id}/upload', [PaymentController::class, 'uploadProofOvertime'])->name('payment.overtime.upload');
});


Route::post('/my-rentals/{id}/cancel',
    [RentalController::class, 'cancel']
)->name('customer.rentals.cancel');



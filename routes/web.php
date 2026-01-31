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


Route::get('/dashboard', function () {
    abort(403);
});


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


Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/payment/{rental}', [PaymentController::class, 'show'])
        ->name('payment.show');

    Route::post('/payment/{rental}', [PaymentController::class, 'process'])
        ->name('payment.process');

});


Route::post('/rentals/{id}/overtime',
    [OvertimeController::class, 'store']
)->name('customer.overtime.store');

Route::post('/overtime/{id}/transfer',
    [PaymentController::class, 'transfer']
)->name('payment.transfer');

Route::post('/overtime/{id}/cash',
    [PaymentController::class, 'cash']
)->name('payment.cash');

Route::delete('/overtime/{id}/cancel',
    [OvertimeController::class,'cancel']
)->name('customer.overtime.cancel');



Route::post('/my-rentals/{id}/cancel',
    [RentalController::class, 'cancel']
)->name('customer.rentals.cancel');



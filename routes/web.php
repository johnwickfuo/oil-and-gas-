<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services.index');
Route::get('/services/{service:slug}', [PageController::class, 'serviceShow'])->name('services.show');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])
    ->middleware('throttle:contact')
    ->name('contact.submit');

Route::get('/pricing', [BookingController::class, 'calculator'])->name('pricing');
Route::get('/book', [BookingController::class, 'create'])->name('booking.create');
Route::post('/book', [BookingController::class, 'store'])
    ->middleware('throttle:bookings')
    ->name('booking.store');
Route::get('/bookings/{reference}/confirmation', [BookingController::class, 'confirmation'])
    ->name('booking.confirmation');
Route::get('/payment/{reference}', [BookingController::class, 'paymentStub'])
    ->name('payment.show');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

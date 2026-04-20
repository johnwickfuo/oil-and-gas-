<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ResourceController;
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
Route::get('/payment/{reference}', [PaymentController::class, 'choose'])->name('payment.show');
Route::post('/payment/{reference}/initialize', [PaymentController::class, 'initialize'])
    ->middleware('throttle:bookings')
    ->name('payment.initialize');
Route::get('/payment/callback/{gateway}', [PaymentController::class, 'callback'])
    ->whereIn('gateway', ['paystack', 'flutterwave'])
    ->name('payment.callback');
Route::get('/payment/{gateway}/success/{reference}', [PaymentController::class, 'success'])
    ->whereIn('gateway', ['paystack', 'flutterwave'])
    ->name('payment.success');
Route::get('/payment/{gateway}/failed', [PaymentController::class, 'failed'])
    ->whereIn('gateway', ['paystack', 'flutterwave'])
    ->name('payment.failed');

Route::post('/webhooks/{gateway}', [PaymentController::class, 'webhook'])
    ->whereIn('gateway', ['paystack', 'flutterwave'])
    ->name('payment.webhook');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{slug}', [RecipeController::class, 'show'])->name('recipes.show');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::post('/resources/{slug}/download', [ResourceController::class, 'download'])
    ->middleware('throttle:downloads')
    ->name('resources.download');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:newsletter')
    ->name('newsletter.subscribe');

Route::get('/academy', [AcademyController::class, 'show'])->name('academy');
Route::post('/academy/waitlist', [AcademyController::class, 'joinWaitlist'])
    ->middleware('throttle:newsletter')
    ->name('academy.join');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

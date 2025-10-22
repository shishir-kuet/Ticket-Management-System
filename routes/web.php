<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Help page route
Route::get('/help', function () {
    return view('help');
})->name('help');

// Contact Sales route
Route::get('/contact-sales', function () {
    return view('contact.sales');
})->name('contact.sales');

// Dashboard route with role-based logic
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Subscription routes (non-admin only)
Route::middleware(['auth', 'prevent.admin.subscription'])->prefix('subscription')->name('subscription.')->group(function () {
    Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans');
    Route::post('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::get('/payment', [SubscriptionController::class, 'showPayment'])->name('payment');
    Route::post('/process-payment', [SubscriptionController::class, 'processPayment'])->name('process-payment');
    Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    Route::post('/downgrade', [SubscriptionController::class, 'downgrade'])->name('downgrade');
    Route::get('/billing', [SubscriptionController::class, 'billing'])->name('billing');
    Route::get('/invoices', [SubscriptionController::class, 'invoices'])->name('invoices');
    Route::get('/invoice/{invoice}', [SubscriptionController::class, 'downloadInvoice'])->name('invoice.download');
});

// Protected routes for authenticated users
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ticket routes - accessible by all authenticated users
    Route::resource('tickets', TicketController::class);
    
    // Additional ticket actions
    Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assign'])
        ->middleware('role:admin,agent')
        ->name('tickets.assign');
    
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->middleware('role:admin,agent')
        ->name('tickets.status');

    // Ticket comments
    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store'])
        ->name('tickets.comments.store');
    
    Route::patch('/comments/{comment}', [TicketCommentController::class, 'update'])
        ->name('comments.update');
    
    Route::delete('/comments/{comment}', [TicketCommentController::class, 'destroy'])
        ->name('comments.destroy');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Category management
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::patch('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    
    // AI Integration Status
    Route::get('/ai-status', [AdminController::class, 'aiStatus'])->name('ai-status');
});

// Chatbot API routes with rate limiting
Route::middleware(['chatbot.rate.limit'])->group(function () {
    Route::post('/chatbot/message', [ChatbotController::class, 'handleMessage'])->name('chatbot.message');
});

require __DIR__.'/auth.php';

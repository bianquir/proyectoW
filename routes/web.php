<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tag', TagController::class);
    Route::resource('customer',CustomerController::class);
    Route::resource('products',ProductController::class);

    Route::get('/send-message/{type}', [MessageController::class, 'sendMessage'])->name('message.sendPlaceholder');
    Route::get('/clientes', function () {
        return view('clientesDatos');
    });
    Route::get('/search', [CustomerController::class, 'search'])->name('customer.search');
});
Route::get('/webhook', [WebhookController::class, 'verifyWebhook']);
Route::post('/webhook', [WebhookController::class, 'processWebhook']);
Route::get('/crearMensaje', [WebhookController::class, 'crearmensaje']);

require __DIR__.'/auth.php';

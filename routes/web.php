<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\userController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;

Route::get('/',[UserController::class,'loginform']); //get -> viewing
Route::get('/registerform',[UserController::class,'registerform']); //get -> viewing
Route::get('/dashboard',[UserController::class,'dashboard']); //get -> viewing
Route::get('/profile',[UserController::class,'profile']); //get -> viewing
Route::get('/orders/{id}/receipt', [OrderController::class, 'generateReceipt'])->name('orders.receipt');
Route::get('/sales/report/{filter}', [SaleController::class, 'generateReport']);
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
Route::get('inventory/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
Route::get('inventory/{id}/pdf', [InventoryController::class, 'showPDF'])->name('inventory.pdf');
Route::get('/inventory/products', [InventoryController::class, 'getProducts'])->name('inventory.products');

Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');

Route::resource('orders', OrderController::class); // Automatically creates CRUD routes
Route::resource('sales', SaleController::class,);
Route::resource('inventory', InventoryController::class);

Route::post('/submit',[UserController::class,'submit']); //post -> getting/passing values to your database
Route::post('/login',[UserController::class,'login']); //post -> getting/passing values to your database
Route::post('/logout',[UserController::class,'logout']); //post -> getting/passing values to your database
Route::post('orders/{id}/mark-complete', [OrderController::class, 'markComplete'])->name('orders.markComplete');
Route::post('orders/{id}/undo-complete', [OrderController::class, 'undoComplete'])->name('orders.undoComplete');
Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');

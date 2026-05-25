<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotesController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DataTableController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard.index');

Route::group(['prefix' => 'admin'], function () {
    Route::get('', [AdminController::class, 'index'])->name('admin.admin.index');
    Route::get('create', [AdminController::class, 'create'])->name('admin.admin.create');
    Route::post('store', [AdminController::class, 'store'])->name('admin.admin.store');
    Route::get('edit/{id}', [AdminController::class, 'edit'])->name('admin.admin.edit')->where('id', '[0-9]+');
    Route::put('update', [AdminController::class, 'update'])->name('admin.admin.update');
    Route::delete('destroy/{id}', [AdminController::class, 'destroy'])->name('admin.admin.destroy')->where('id', '[0-9]+');
});

Route::middleware(['permission:admin|moderator'])->group(function () {
    Route::group(['prefix' => 'notes'], function () {
        Route::get('', [NotesController::class, 'index'])->name('admin.notes.index');
        Route::get('edit/{id}', [NotesController::class, 'edit'])->name('admin.notes.edit')->where('id', '[0-9]+');
        Route::put('update', [NotesController::class, 'update'])->name('admin.notes.update');
        Route::delete('destroy/{id}', [NotesController::class, 'destroy'])->name('admin.notes.destroy')->where('id', '[0-9]+');
    });
});

Route::group(['prefix' => 'catalog'], function () {
    Route::get('', [CatalogController::class, 'index'])->name('admin.catalog.index');
    Route::get('create', [CatalogController::class, 'create'])->name('admin.catalog.create');
    Route::post('store', [CatalogController::class, 'store'])->name('admin.catalog.store');
    Route::get('edit/{id}', [CatalogController::class, 'edit'])->name('admin.catalog.edit')->where('id', '[0-9]+');
    Route::put('update', [CatalogController::class, 'update'])->name('admin.catalog.update');
    Route::delete('destroy/{id}', [CatalogController::class, 'destroy'])->name('admin.catalog.destroy')->where('id', '[0-9]+');
});

Route::group(['prefix' => 'datatable'], function () {
    Route::any('notes', [DataTableController::class, 'notes'])->name('admin.datatable.notes')->middleware('permission:admin|moderator');
    Route::any('admin', [DataTableController::class, 'admin'])->name('admin.datatable.admin')->middleware('permission:admin|moderator');
    Route::any('catalogs', [DataTableController::class, 'catalogs'])->name('admin.datatable.catalogs');
});

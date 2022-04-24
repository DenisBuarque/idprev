<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\HomeController;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('site');
});*/

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/franqueado/login',[HomeController::class, 'login'])->name('login.franchisee');

Route::group(['middleware' => 'advisor'], function () {
    Route::get('/franqueado/logout',[HomeController::class, 'logout'])->name('logout.franchisee');
    Route::get('/franqueado', [HomeController::class, 'franchisee'])->name('site.franchisee');
    Route::get('/cliente/{id}', [HomeController::class, 'detail'])->name('franchisee.client');
});

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/user/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/user/store', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/user/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

Route::get('/admin/services', [ServiceController::class, 'index'])->name('admin.services.index');
Route::get('/admin/service/create', [ServiceController::class, 'create'])->name('admin.services.create');
Route::post('/admin/service/store', [ServiceController::class, 'store'])->name('admin.services.store');
Route::get('/admin/service/edit/{id}', [ServiceController::class, 'edit'])->name('admin.services.edit');
Route::put('/admin/service/update/{id}', [ServiceController::class, 'update'])->name('admin.services.update');
Route::delete('/admin/service/delete/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');

Route::get('/admin/clients', [ClientController::class, 'index'])->name('admin.clients.index');
Route::get('/admin/client/create', [ClientController::class, 'create'])->name('admin.clients.create');
Route::post('/admin/client/store', [ClientController::class, 'store'])->name('admin.clients.store');
Route::get('/admin/client/edit/{id}', [ClientController::class, 'edit'])->name('admin.clients.edit');
Route::put('/admin/client/update/{id}', [ClientController::class, 'update'])->name('admin.clients.update');
Route::delete('/admin/client/delete/{id}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');
Route::delete('/admin/client/document/remove', [ClientController::class, 'remove'])->name('admin.client.document.remove');

Route::get('/admin/advisors', [AdvisorController::class, 'index'])->name('admin.advisors.index');
Route::get('/admin/advisor/create', [AdvisorController::class, 'create'])->name('admin.advisors.create');
Route::post('/admin/advisor/store', [AdvisorController::class, 'store'])->name('admin.advisors.store');
Route::get('/admin/advisor/edit/{id}', [AdvisorController::class, 'edit'])->name('admin.advisors.edit');
Route::put('/admin/advisor/update/{id}', [AdvisorController::class, 'update'])->name('admin.advisors.update');
Route::delete('/admin/advisor/delete/{id}', [AdvisorController::class, 'destroy'])->name('admin.advisors.destroy');

Route::get('/admin/processes', [ProcessController::class, 'index'])->name('admin.processes.index');
Route::get('/admin/processes/pdf/{id}', [ProcessController::class, 'pdf'])->name('admin.processes.pdf');
Route::get('/admin/process/create', [ProcessController::class, 'create'])->name('admin.processes.create');
Route::post('/admin/process/store', [ProcessController::class, 'store'])->name('admin.processes.store');
Route::get('/admin/process/edit/{id}', [ProcessController::class, 'edit'])->name('admin.processes.edit');
Route::put('/admin/process/update/{id}', [ProcessController::class, 'update'])->name('admin.processes.update');
Route::delete('/admin/process/delete/{id}', [ProcessController::class, 'destroy'])->name('admin.processes.destroy');

Route::get('/admin/contracts', [ContractController::class, 'index'])->name('admin.contracts.index');
Route::get('/admin/contract/pdf/{id}', [ContractController::class, 'pdf'])->name('admin.contracts.pdf');
Route::get('/admin/contract/create', [ContractController::class, 'create'])->name('admin.contracts.create');
Route::post('/admin/contract/store', [ContractController::class, 'store'])->name('admin.contracts.store');
Route::get('/admin/contract/edit/{id}', [ContractController::class, 'edit'])->name('admin.contracts.edit');
Route::put('/admin/contract/update/{id}', [ContractController::class, 'update'])->name('admin.contracts.update');
Route::delete('/admin/contract/delete/{id}', [ContractController::class, 'destroy'])->name('admin.contracts.destroy');

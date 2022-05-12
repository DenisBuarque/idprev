<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FranchiseeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\LawyerController;
use App\Http\Controllers\WorksheetController;
use App\Http\Controllers\TicketController;
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

/*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/franqueado/login',[HomeController::class, 'login'])->name('login.franchisee');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('dashboard');
Route::post('/dashboard/create', [DashboardController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('dashboard.store');
Route::post('/dashboard/feedback', [DashboardController::class, 'feedback'])->middleware(['auth:sanctum', 'verified'])->name('dashboard.feedback');

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

Route::get('/admin/leads', [LeadController::class, 'index'])->name('admin.leads.index');
Route::get('/admin/lead/create', [LeadController::class, 'create'])->name('admin.leads.create');
Route::post('/admin/lead/store', [LeadController::class, 'store'])->name('admin.leads.store');
Route::get('/admin/lead/edit/{id}', [LeadController::class, 'edit'])->name('admin.leads.edit');
Route::put('/admin/lead/update/{id}', [LeadController::class, 'update'])->name('admin.leads.update');
Route::delete('/admin/lead/delete/{id}', [LeadController::class, 'destroy'])->name('admin.leads.destroy');
Route::delete('/admin/lead/document/remove', [LeadController::class, 'remove'])->name('admin.lead.document.remove');
Route::get('/admin/lead/documents/{id}', [LeadController::class, 'documents'])->name('admin.lead.documents');
Route::get('/admin/lead/comments/{id}', [LeadController::class, 'comments'])->name('admin.lead.comments');
Route::post('/admin/lead/feedback', [LeadController::class, 'feedback'])->name('admin.lead.feedback');
Route::get('/admin/lead/document/download/{id}', [LeadController::class, 'download'])->name('admin.lead.document.download');

Route::get('/admin/training/files', [FileController::class, 'index'])->name('admin.training.files.index');
Route::get('/admin/training/file/create', [FileController::class, 'create'])->name('admin.training.files.create');
Route::post('/admin/training/file/store', [FileController::class, 'store'])->name('admin.training.files.store');
Route::get('/admin/training/file/edit/{id}', [FileController::class, 'edit'])->name('admin.training.files.edit');
Route::put('/admin/training/file/update/{id}', [FileController::class, 'update'])->name('admin.training.files.update');
Route::delete('/admin/training/file/delete/{id}', [FileController::class, 'destroy'])->name('admin.training.files.destroy');
Route::get('/admin/training/file/download/{id}', [FileController::class, 'download'])->name('admin.training.files.download');

Route::get('/admin/document/models', [ModelController::class, 'index'])->name('admin.document.models.index');
Route::get('/admin/document/model/create', [ModelController::class, 'create'])->name('admin.document.models.create');
Route::post('/admin/document/model/store', [ModelController::class, 'store'])->name('admin.document.models.store');
Route::get('/admin/document/model/edit/{id}', [ModelController::class, 'edit'])->name('admin.document.models.edit');
Route::put('/admin/document/model/update/{id}', [ModelController::class, 'update'])->name('admin.document.models.update');
Route::delete('/admin/document/model/delete/{id}', [ModelController::class, 'destroy'])->name('admin.document.models.destroy');
Route::get('/admin/document/model/download/{id}', [ModelController::class, 'download'])->name('admin.document.models.download');

Route::get('/admin/worksheets', [WorksheetController::class, 'index'])->name('admin.worksheets.index');
Route::get('/admin/worksheet/create', [WorksheetController::class, 'create'])->name('admin.worksheets.create');
Route::post('/admin/worksheet/store', [WorksheetController::class, 'store'])->name('admin.worksheets.store');
Route::get('/admin/worksheet/edit/{id}', [WorksheetController::class, 'edit'])->name('admin.worksheets.edit');
Route::put('/admin/worksheet/update/{id}', [WorksheetController::class, 'update'])->name('admin.worksheets.update');
Route::delete('/admin/worksheet/delete/{id}', [WorksheetController::class, 'destroy'])->name('admin.worksheets.destroy');
Route::get('/admin/worksheet/download/{id}', [WorksheetController::class, 'download'])->name('admin.worksheets.download');

Route::get('/admin/training/events', [EventController::class, 'index'])->name('admin.training.events.index');
Route::get('/admin/training/event/create', [EventController::class, 'create'])->name('admin.training.events.create');
Route::post('/admin/training/event/store', [EventController::class, 'store'])->name('admin.training.events.store');
Route::get('/admin/training/event/edit/{id}', [EventController::class, 'edit'])->name('admin.training.events.edit');
Route::put('/admin/training/event/update/{id}', [EventController::class, 'update'])->name('admin.training.events.update');
Route::delete('/admin/training/event/delete/{id}', [EventController::class, 'destroy'])->name('admin.training.events.destroy');

Route::get('/admin/document/actions', [ActionController::class, 'index'])->name('admin.document.actions.index');
Route::get('/admin/document/action/create', [ActionController::class, 'create'])->name('admin.document.actions.create');
Route::post('/admin/document/action/store', [ActionController::class, 'store'])->name('admin.document.actions.store');
Route::get('/admin/document/action/edit/{id}', [ActionController::class, 'edit'])->name('admin.document.actions.edit');
Route::put('/admin/document/action/update/{id}', [ActionController::class, 'update'])->name('admin.document.actions.update');
Route::delete('/admin/document/action/delete/{id}', [ActionController::class, 'destroy'])->name('admin.document.actions.destroy');

Route::get('/admin/clients', [ClientController::class, 'index'])->name('admin.clients.index');
Route::get('/admin/clients/converted', [ClientController::class, 'converted'])->name('admin.clients.converted');
Route::get('/admin/clients/unconverted', [ClientController::class, 'unconverted'])->name('admin.clients.unconverted');
Route::get('/admin/clients/term', [ClientController::class, 'term'])->name('admin.clients.term');
Route::get('/admin/client/term/edit/{id}', [ClientController::class, 'edit_term'])->name('admin.clients.edit_term');
Route::get('/admin/client/create', [ClientController::class, 'create'])->name('admin.clients.create');
Route::post('/admin/client/store', [ClientController::class, 'store'])->name('admin.clients.store');
Route::get('/admin/client/edit/{id}', [ClientController::class, 'edit'])->name('admin.clients.edit');
Route::put('/admin/client/update/{id}', [ClientController::class, 'update'])->name('admin.clients.update');
Route::put('/admin/client/update/term', [ClientController::class, 'update_term'])->name('admin.clients.update_term');
Route::delete('/admin/client/delete/{id}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');
Route::delete('/admin/client/document/remove', [ClientController::class, 'remove'])->name('admin.client.document.remove');
Route::get('/admin/client/documents/{id}', [ClientController::class, 'documents'])->name('admin.client.documents');
Route::get('/admin/client/document/download/{id}', [ClientController::class, 'download'])->name('admin.client.document.download');

Route::get('/admin/franchisees', [FranchiseeController::class, 'index'])->name('admin.franchisees.index');
Route::get('/admin/franchisee/create', [FranchiseeController::class, 'create'])->name('admin.franchisees.create');
Route::post('/admin/franchisee/store', [FranchiseeController::class, 'store'])->name('admin.franchisees.store');
Route::get('/admin/franchisee/edit/{id}', [FranchiseeController::class, 'edit'])->name('admin.franchisees.edit');
Route::put('/admin/franchisee/update/{id}', [FranchiseeController::class, 'update'])->name('admin.franchisees.update');
Route::delete('/admin/franchisee/delete/{id}', [FranchiseeController::class, 'destroy'])->name('admin.franchisees.destroy');

Route::get('/admin/lawyers', [LawyerController::class, 'index'])->name('admin.lawyers.index');
Route::get('/admin/lawyer/create', [LawyerController::class, 'create'])->name('admin.lawyers.create');
Route::post('/admin/lawyer/store', [LawyerController::class, 'store'])->name('admin.lawyers.store');
Route::get('/admin/lawyer/edit/{id}', [LawyerController::class, 'edit'])->name('admin.lawyers.edit');
Route::put('/admin/lawyer/update/{id}', [LawyerController::class, 'update'])->name('admin.lawyers.update');
Route::delete('/admin/lawyer/delete/{id}', [LawyerController::class, 'destroy'])->name('admin.lawyers.destroy');

Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
Route::get('/admin/ticket/create', [TicketController::class, 'create'])->name('admin.tickets.create');
Route::post('/admin/ticket/store', [TicketController::class, 'store'])->name('admin.tickets.store');
Route::get('/admin/ticket/edit/{id}', [TicketController::class, 'edit'])->name('admin.tickets.edit');
Route::put('/admin/ticket/update/{id}', [TicketController::class, 'update'])->name('admin.tickets.update');
Route::delete('/admin/ticket/delete/{id}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');
Route::get('/admin/ticket/response/{id}', [TicketController::class, 'response'])->name('admin.tickets.response');
Route::post('/admin/ticket/feedback', [TicketController::class, 'feedback'])->name('admin.tickets.feedback');


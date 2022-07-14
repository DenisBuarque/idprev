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
use App\Http\Controllers\TermController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\AdministrativeController;
use App\Http\Controllers\TestimonieController;
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

Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/admin/user/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/user/store', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/user/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

Route::get('/admin/leads', [LeadController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.index');
Route::get('/admin/lead/create', [LeadController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.create');
Route::get('/admin/leads/{tag}', [LeadController::class, 'leads'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.tag');
Route::post('/admin/lead/store', [LeadController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.store');
Route::get('/admin/lead/edit/{id}', [LeadController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.edit');
Route::get('/admin/lead/show/{id}', [LeadController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.show');
Route::put('/admin/lead/update/{id}', [LeadController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.update');
Route::delete('/admin/lead/delete/{id}', [LeadController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.leads.destroy');
Route::delete('/admin/lead/document/remove', [LeadController::class, 'remove'])->middleware(['auth:sanctum', 'verified'])->name('admin.lead.document.remove');
Route::post('/admin/lead/feedback', [LeadController::class, 'feedback'])->middleware(['auth:sanctum', 'verified'])->name('admin.lead.feedback');
Route::get('/admin/lead/document/download/{id}', [LeadController::class, 'download'])->middleware(['auth:sanctum', 'verified'])->name('admin.lead.document.download');

Route::get('/admin/clients', [ClientController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.index');
Route::get('/admin/client/create', [ClientController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.create');
Route::post('/admin/client/store', [ClientController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.store');
Route::get('/admin/client/edit/{id}', [ClientController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.edit');
Route::get('/admin/client/tag/{tag}', [ClientController::class, 'tag'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.tag');
Route::get('/admin/client/show/{id}', [ClientController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.show');
Route::put('/admin/client/update/{id}', [ClientController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.update');
Route::delete('/admin/client/delete/{id}', [ClientController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.delete');
Route::delete('/admin/client/delete/file', [ClientController::class, 'remove'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.delete.file');
Route::get('/admin/client/lawyers/{id}', [ClientController::class, 'lawyers'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.lawyers');
Route::get('/admin/client/documents/{id}', [ClientController::class, 'documents'])->middleware(['auth:sanctum', 'verified'])->name('admin.clients.documents');
Route::delete('/admin/client/delete/{id}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');

Route::get('/admin/term/create/{id}', [TermController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.create');
Route::post('/admin/term/store', [TermController::class, 'store'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.store');
Route::get('/admin/term/edit/{id}', [TermController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.edit');
Route::put('/admin/term/update/{id}', [TermController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.update');
Route::delete('/admin/term/delete/{id}', [TermController::class, 'destroy'])->middleware(['auth:sanctum', 'verified'])->name('admin.terms.delete');

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

Route::get('/admin/actions', [ActionController::class, 'index'])->name('admin.actions.index');
Route::get('/admin/action/create', [ActionController::class, 'create'])->name('admin.actions.create');
Route::post('/admin/action/store', [ActionController::class, 'store'])->name('admin.actions.store');
Route::get('/admin/action/edit/{id}', [ActionController::class, 'edit'])->name('admin.actions.edit');
Route::put('/admin/action/update/{id}', [ActionController::class, 'update'])->name('admin.actions.update');
Route::delete('/admin/action/delete/{id}', [ActionController::class, 'destroy'])->name('admin.actions.destroy');

Route::get('/admin/models', [ModelController::class, 'index'])->name('admin.models.index');
Route::get('/admin/model/create', [ModelController::class, 'create'])->name('admin.models.create');
Route::post('/admin/model/store', [ModelController::class, 'store'])->name('admin.models.store');
Route::get('/admin/model/edit/{id}', [ModelController::class, 'edit'])->name('admin.models.edit');
Route::put('/admin/model/update/{id}', [ModelController::class, 'update'])->name('admin.models.update');
Route::delete('/admin/model/delete/{id}', [ModelController::class, 'destroy'])->name('admin.models.destroy');
Route::get('/admin/model/download/{slug}', [ModelController::class, 'download'])->name('admin.models.download');

Route::get('/admin/worksheets', [WorksheetController::class, 'index'])->name('admin.worksheets.index');
Route::get('/admin/worksheet/create', [WorksheetController::class, 'create'])->name('admin.worksheets.create');
Route::post('/admin/worksheet/store', [WorksheetController::class, 'store'])->name('admin.worksheets.store');
Route::get('/admin/worksheet/edit/{id}', [WorksheetController::class, 'edit'])->name('admin.worksheets.edit');
Route::put('/admin/worksheet/update/{id}', [WorksheetController::class, 'update'])->name('admin.worksheets.update');
Route::delete('/admin/worksheet/delete/{id}', [WorksheetController::class, 'destroy'])->name('admin.worksheets.destroy');
Route::get('/admin/worksheet/download/{id}', [WorksheetController::class, 'download'])->name('admin.worksheets.download');

Route::get('/admin/files', [FileController::class, 'index'])->name('admin.files.index');
Route::get('/admin/file/create', [FileController::class, 'create'])->name('admin.files.create');
Route::post('/admin/file/store', [FileController::class, 'store'])->name('admin.files.store');
Route::get('/admin/file/edit/{id}', [FileController::class, 'edit'])->name('admin.files.edit');
Route::put('/admin/file/update/{id}', [FileController::class, 'update'])->name('admin.files.update');
Route::delete('/admin/file/delete/{id}', [FileController::class, 'destroy'])->name('admin.files.destroy');
Route::get('/admin/file/download/{id}', [FileController::class, 'download'])->name('admin.files.download');

Route::get('/admin/events', [EventController::class, 'index'])->name('admin.events.index');
Route::get('/admin/event/create', [EventController::class, 'create'])->name('admin.events.create');
Route::post('/admin/event/store', [EventController::class, 'store'])->name('admin.events.store');
Route::get('/admin/event/edit/{id}', [EventController::class, 'edit'])->name('admin.events.edit');
Route::put('/admin/event/update/{id}', [EventController::class, 'update'])->name('admin.events.update');
Route::delete('/admin/event/delete/{id}', [EventController::class, 'destroy'])->name('admin.events.destroy');

Route::get('/admin/tickets', [TicketController::class, 'index'])->name('admin.tickets.index');
Route::get('/admin/ticket/create', [TicketController::class, 'create'])->name('admin.tickets.create');
Route::post('/admin/ticket/store', [TicketController::class, 'store'])->name('admin.tickets.store');
Route::get('/admin/ticket/edit/{id}', [TicketController::class, 'edit'])->name('admin.tickets.edit');
Route::put('/admin/ticket/update/{id}', [TicketController::class, 'update'])->name('admin.tickets.update');
Route::delete('/admin/ticket/delete/{id}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');
Route::get('/admin/ticket/response/{id}', [TicketController::class, 'response'])->name('admin.tickets.response');
Route::post('/admin/ticket/feedback', [TicketController::class, 'feedback'])->name('admin.tickets.feedback');

Route::get('/admin/financial', [FinancialController::class, 'index'])->name('admin.financial.index');
Route::get('/admin/financial/create/{id}', [FinancialController::class, 'create'])->name('admin.financial.create');
Route::get('/admin/financial/autofindos', [FinancialController::class, 'autofindos'])->name('admin.financial.autofindos');
Route::post('/admin/financial/store', [FinancialController::class, 'store'])->name('admin.financial.store');
Route::get('/admin/financial/edit/{id}', [FinancialController::class, 'edit'])->name('admin.financial.edit');
Route::put('/admin/financial/update/{id}', [FinancialController::class, 'update'])->name('admin.financial.update');
Route::delete('/admin/financial/delete/{id}', [FinancialController::class, 'destroy'])->name('admin.financial.destroy');

Route::get('/admin/services', [ServiceController::class, 'index'])->name('admin.services.index');
Route::get('/admin/service/create', [ServiceController::class, 'create'])->name('admin.services.create');
Route::post('/admin/service/store', [ServiceController::class, 'store'])->name('admin.services.store');
Route::get('/admin/service/edit/{id}', [ServiceController::class, 'edit'])->name('admin.services.edit');
Route::put('/admin/service/update/{id}', [ServiceController::class, 'update'])->name('admin.services.update');
Route::delete('/admin/service/delete/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');

Route::get('/admin/administrative', [AdministrativeController::class, 'index'])->name('admin.administratives.index');
Route::get('/admin/administrative/create', [AdministrativeController ::class, 'create'])->name('admin.administratives.create');
Route::post('/admin/administrative/store', [AdministrativeController::class, 'store'])->name('admin.administratives.store');
Route::get('/admin/administrative/edit/{id}', [AdministrativeController::class, 'edit'])->name('admin.administratives.edit');
Route::put('/admin/administrative/update/{id}', [AdministrativeController::class, 'update'])->name('admin.administratives.update');
Route::delete('/admin/administrative/delete/{id}', [AdministrativeController::class, 'destroy'])->name('admin.administratives.destroy');

Route::get('/admin/testimonies', [TestimonieController::class, 'index'])->name('admin.testimonies.index');
Route::get('/admin/testimonie/create', [TestimonieController ::class, 'create'])->name('admin.testimonies.create');
Route::post('/admin/testimonie/store', [TestimonieController::class, 'store'])->name('admin.testimonies.store');
Route::get('/admin/testimonie/edit/{id}', [TestimonieController::class, 'edit'])->name('admin.testimonies.edit');
Route::put('/admin/testimonie/update/{id}', [TestimonieController::class, 'update'])->name('admin.testimonies.update');
Route::delete('/admin/testimonie/delete/{id}', [TestimonieController::class, 'destroy'])->name('admin.testimonies.destroy');
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TypeticketsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReleasesController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\LogticketsController;
use App\Http\Controllers\MailController;

use Illuminate\Support\Facades\Route;

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

Route::middleware('splade')->group(function () {
    // Registers routes to support the interactive components...
    Route::spladeWithVueBridge();

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();

    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware('auth')->group(function () {

        // Route::get('/dashboard', function () {
        //     return view('dashboard');
        // })->middleware(['verified'])->name('dashboard');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard', [DashboardController::class, 'index'])->name('dashboard.project');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Projects
        Route::get('/projects', [ProjectsController::class, 'index'])->middleware(['adminAccess'])->name('projects.index');
        Route::get('/projects/{id}', [ProjectsController::class, 'show'])->middleware(['adminAccess'])->name('projects.show');
        Route::post('/projects', [ProjectsController::class, 'create'])->middleware(['adminAccess'])->name('projects.create');
        Route::patch('/projects/{id}', [ProjectsController::class, 'update'])->middleware(['adminAccess'])->name('projects.update');
        Route::get('/projects-delete/{id}', [ProjectsController::class, 'delete'])->middleware(['adminAccess'])->name('projects.delete');
        Route::delete('/projects/{id}', [ProjectsController::class, 'destroy'])->middleware(['adminAccess'])->name('projects.destroy');

        // Type of Tickets
        Route::get('/typetickets', [TypeticketsController::class, 'index'])->middleware(['adminAccess'])->name('typetickets.index');
        Route::get('/typetickets/{id}', [TypeticketsController::class, 'show'])->middleware(['adminAccess'])->name('typetickets.show');
        Route::post('/typetickets', [TypeticketsController::class, 'create'])->middleware(['adminAccess'])->name('typetickets.create');
        Route::patch('/typetickets/{id}', [TypeticketsController::class, 'update'])->middleware(['adminAccess'])->name('typetickets.update');
        Route::get('/typetickets-delete/{id}', [TypeticketsController::class, 'delete'])->middleware(['adminAccess'])->name('typetickets.delete');
        Route::delete('/typetickets/{id}', [TypeticketsController::class, 'destroy'])->middleware(['adminAccess'])->name('typetickets.destroy');

        // Users
        Route::get('/users', [UsersController::class, 'index'])->middleware(['adminAccess'])->name('users.index');
        Route::get('/users-projects/{userId}', [UsersController::class, 'projects'])->middleware(['adminAccess'])->name('users.projects');
        Route::get('/users-new-projects/{id}', [UsersController::class, 'newprojects'])->middleware(['adminAccess'])->name('users.newprojects');
        Route::get('/users-delete/{id}', [ProfileController::class, 'delete'])->middleware(['adminAccess'])->name('users.delete');
        Route::post('/associate/{id}', [UsersController::class, 'associate'])->middleware(['adminAccess'])->name('users.associate');
        Route::get('/users-project-delete/{userId}/{id}', [UsersController::class, 'deleteproj'])->middleware(['adminAccess'])->name('users.project-delete');
        Route::get('/users-delete/{id}', [UsersController::class, 'delete'])->middleware(['adminAccess'])->name('users.delete');
        Route::delete('/users/{id}', [UsersController::class, 'destroy'])->middleware(['adminAccess'])->name('users.destroy');
        Route::get('/users/{id}', [UsersController::class, 'show'])->middleware(['adminAccess'])->name('users.show');
        Route::patch('/users-update/{id}', [UsersController::class, 'update'])->middleware(['adminAccess'])->name('users.update');

        // Releases
        Route::get('/releases', [ReleasesController::class, 'index'])->name('releases.index');
        Route::get('/releases/{id}', [ReleasesController::class, 'show'])->middleware(['gpAccess'])->name('releases.show');
        Route::post('/releases', [ReleasesController::class, 'create'])->middleware(['gpAccess'])->name('releases.create');
        Route::patch('/releases/{id}', [ReleasesController::class, 'update'])->middleware(['gpAccess'])->name('releases.update');
        Route::get('/releases-delete/{id}', [ReleasesController::class, 'delete'])->middleware(['gpAccess'])->name('releases.delete');
        Route::delete('/releases/{id}', [ReleasesController::class, 'destroy'])->middleware(['gpAccess'])->name('releases.destroy');
        Route::get('/export-tickets/{id}', [ReleasesController::class, 'exports'])->middleware(['gpAccess'])->name('releases.exports');

        // Tickets
        Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
        Route::get('/mytickets', [TicketsController::class, 'mytickets'])->name('tickets.mytickets');
        Route::get('/testing', [TicketsController::class, 'testing'])->name('tickets.testing');
        Route::get('/tickets/{id}', [TicketsController::class, 'show'])->name('tickets.show');
        Route::get('/tickets-edit/{id}', [TicketsController::class, 'edit'])->name('tickets.edit');
        Route::post('/tickets', [TicketsController::class, 'create'])->name('tickets.create');
        Route::patch('/tickets/{id}', [TicketsController::class, 'update'])->name('tickets.update');
        Route::get('/tickets-delete/{id}', [TicketsController::class, 'delete'])->name('tickets.delete');
        Route::delete('/tickets/{id}', [TicketsController::class, 'destroy'])->name('tickets.destroy');


        // Logtickets
        Route::post('/logtickets/{id}', [LogticketsController::class, 'create'])->name('logtickets.create');
        Route::get('/logtickets/{id}/{status}', [LogticketsController::class, 'update'])->name('logtickets.update');

        // Files
        Route::get('/files/{id}', [FilesController::class, 'show'])->name('files.show');
        Route::post('/files/{id}', [FilesController::class, 'upload'])->name('files.upload');
        Route::get('/download/{id}', [FilesController::class, 'download'])->name('files.download');
        Route::get('/delete-file/{id}/{nomearq}', [FilesController::class, 'deleteFile'])->name('files.delete');


        Route::get('send-mail', [MailController::class, 'index']);

    });

    require __DIR__.'/auth.php';
});

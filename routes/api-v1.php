<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\SkillController;
use App\Http\Controllers\Api\V1\StackController;
use App\Http\Controllers\Api\V1\AboutMeController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\HoneypotController;
use App\Http\Controllers\Api\V1\ContactMeController;
use App\Http\Controllers\Api\V1\NavbarItemController;
use App\Http\Controllers\Api\V1\ProjectImageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/register', [AuthController::class, 'register'])->name('register')->middleware('throttle:5,1');
Route::post('/auth/login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1');
Route::middleware('auth:api')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me'])->name('me');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::apiResource('/users', UserController::class);
    Route::post('/about-me', [AboutMeController::class, 'store'])->name('about-me.store');
    Route::patch('/about-me', [AboutMeController::class, 'update'])->name('about-me.update');
    Route::delete('/about-me', [AboutMeController::class, 'destroy'])->name('about-me.destroy');
    Route::post('/navbar-items', [NavbarItemController::class, 'store'])->name('navbar-items.store');
    Route::patch('/navbar-items/{navbarItem}', [NavbarItemController::class, 'update'])->name('navbar-items.update');
    Route::delete('/navbar-items/{navbarItem}', [NavbarItemController::class, 'destroy'])->name('navbar-items.destroy');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::patch('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');
    Route::post('/stacks', [StackController::class, 'store'])->name('stacks.store');
    Route::patch('/stacks/{stack}', [StackController::class, 'update'])->name('stacks.update');
    Route::delete('/stacks/{stack}', [StackController::class, 'destroy'])->name('stacks.destroy');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::apiResource('projects.images', ProjectImageController::class);
    Route::get('/contact-me', [ContactMeController::class, 'index'])->name('contact-me.index');
    Route::get('/contact-me/{id}', [ContactMeController::class, 'show'])->name('contact-me.show');
    Route::delete('/contact-me/{id}', [ContactMeController::class, 'destroy'])->name('contact-me.destroy');
});
Route::get('/about-me', [AboutMeController::class, 'index'])->name('about-me.index');
Route::get('/navbar-items', [NavbarItemController::class, 'index'])->name('navbar-items.index');
Route::get('/navbar-items/{id}', [NavbarItemController::class, 'show'])->name('navbar-items.show');
Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
Route::get('/skills/{id}', [SkillController::class, 'show'])->name('skills.show');
Route::get('/stacks', [StackController::class, 'index'])->name('stacks.index');
Route::get('/stacks/{id}', [StackController::class, 'show'])->name('stacks.show');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/project-images', [ProjectImageController::class, 'index'])->name('projectImages.index');
Route::get('/project-images/{id}', [ProjectImageController::class, 'show'])->name('projectImages.show');
Route::post('/contact-me', [ContactMeController::class, 'store'])->name('contact-me.store')->middleware('throttle:5,1');

// Honeypot Token Endpoint
Route::get('/security/honeypot-token', HoneypotController::class)->middleware(['throttle:honeypot', 'cache.headers:private;no_cache;no_store;must_revalidate']);

<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Course\CheckoutController;
use App\Http\Controllers\Course\GetOneCourseController;
use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', LandingController::class)->name('landing');
Route::get('/courses/{course}', GetOneCourseController::class)->name('courses.show');
Route::get('/courses/{course}/checkout', CheckoutController::class)->name('courses.checkout');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', CourseController::class)->except('show');

    Route::get('courses/{course}/modules', [CourseController::class, 'modules'])->name('courses.modules');
    Route::post('courses/{course}/modules/create-attach', [CourseController::class, 'createAndAttach'])->name('courses.modules.createAndAttach');
    Route::post('courses/{course}/modules', [CourseController::class, 'attachModule'])->name('courses.modules.attach');
    Route::delete('courses/{course}/modules/{module}', [CourseController::class, 'detachModule'])->name('courses.modules.detach');

    Route::resource('modules', ModuleController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

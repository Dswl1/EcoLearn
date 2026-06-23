<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserContentController;
use App\Models\Content;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/demo/explore', function () {
    $materials = Content::with('user')
        ->where('status', 'published')
        ->latest('published_at')
        ->take(3)
        ->get();

    $totalMaterials = Content::where('status', 'published')->count();
    $totalUsers = User::count();

    return view('dashboard.guest.index', compact('materials', 'totalMaterials', 'totalUsers'));
})->name('demo');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);
    }

    return redirect()->back();
})->name('lang.switch');

Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
Route::get('/materials/{content:slug}', [MaterialController::class, 'show'])->name('materials.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/progress', [MaterialController::class, 'saveProgress'])->name('progress.save');
    Route::post('/progress/quiz/batch', [MaterialController::class, 'saveQuizBatch'])->name('progress.quiz.batch');

    Route::post('/notifications/{id}/read', function (string $id) {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        return back();
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.read-all');

    Route::prefix('my-content')->name('user.content.')->controller(UserContentController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{content}/edit', 'edit')->name('edit');
        Route::put('/{content}', 'update')->name('update');
        Route::post('/{content}/submit', 'submit')->name('submit');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('content', ContentController::class);
        Route::post('content/{content}/approve', [ContentController::class, 'approve'])->name('content.approve');
        Route::post('content/{content}/reject', [ContentController::class, 'reject'])->name('content.reject');
        Route::resource('user', UserController::class);
    });
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Masters\AchievementController;
use App\Http\Controllers\Masters\ConfigController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::group(['prefix' => 'auth'], function() {

    Route::group(['middleware' => 'guest'], function() {
        Route::get('login', [AuthController::class, 'login'])->name(DBRoutes::authLogin);
        Route::post('login', [AuthController::class, 'processLogin']);
        Route::post('google-login', [AuthController::class, 'processGoogleLogin'])->name(DBRoutes::authGoogleLogin);
        Route::get('complete-signup', [AuthController::class, 'completeSignUp'])->name(DBRoutes::authCompleteSignUp);
        Route::post('complete-signup', [AuthController::class, 'processCompleteSignUp']);
    });

    Route::get('logout', function(Request $req) {
        Auth::logout();

        session()->flush();

        $req->session()->invalidate();

        $req->session()->regenerateToken();

        return redirect(\route(DBRoutes::authLogin));
    })->name(DBRoutes::authLogout);
});

Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'config'], function($query) {
        Route::get('select', [ConfigController::class, 'select'])->name(DBRoutes::configSelect);

        Route::get('', [ConfigController::class, 'index'])->name(DBRoutes::config);
        Route::get('{id}', [ConfigController::class, 'show']);
    });

    Route::get('/', [AppController::class, 'index']);

    Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin'], function() {

        Route::get('', [AppController::class, 'admin'])->name(DBRoutes::administrator);

        Route::group(['prefix' => 'achievement'], function() {
            Route::post('datatables', [AchievementController::class, 'datatables']);
            Route::get('form', [AchievementController::class, 'form']);
            Route::get('detail', [AchievementController::class, 'detail']);

            Route::get('', [AchievementController::class, 'index'])->name(DBRoutes::achievement);
            Route::post('', [AchievementController::class, 'store']);
            Route::get('{id}', [AchievementController::class, 'show']);
            Route::post('{id}', [AchievementController::class, 'update']);
            Route::delete('{id}', [AchievementController::class, 'destroy']);
        });
    });
});

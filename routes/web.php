<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Masters\AchievementController;
use App\Http\Controllers\Masters\ConfigController;
use App\Http\Controllers\Masters\UserController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Profiles\ProfileController;
use App\Http\Controllers\Users\RequestPickUpController;
use App\Http\Controllers\Users\UserAchievementController;
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

Route::group(['middleware' => 'guest'], function() {
    Route::get('sign-in', [AuthController::class, 'signIn'])->name(DBRoutes::authSignIn);
    Route::post('sign-in', [AuthController::class, 'processSignIn']);
    Route::post('google-login', [AuthController::class, 'processGoogleSignIn'])->name(DBRoutes::authGoogleSignIn);
    Route::get('complete-signup', [AuthController::class, 'completeSignUp'])->name(DBRoutes::authCompleteSignUp);
    Route::post('complete-signup', [AuthController::class, 'processCompleteSignUp']);
    Route::post('complete-signup/skip', [AuthController::class, 'skipSignUp']);
    Route::get('sign-up', [AuthController::class, 'signUp'])->name(DBRoutes::authSignUp);
    Route::get('check', [UserController::class, 'check'])->name(DBRoutes::authCheck);
});

Route::get('logout', function(Request $req) {
    Auth::logout();

    session()->flush();

    $req->session()->invalidate();

    $req->session()->regenerateToken();

    return redirect(\route(DBRoutes::authSignIn));
})->name(DBRoutes::authLogout);

Route::group(['middleware' => 'auth'], function() {
    Route::group(['prefix' => 'config'], function($query) {
        Route::get('select', [ConfigController::class, 'select'])->name(DBRoutes::configSelect);

        Route::get('', [ConfigController::class, 'index'])->name(DBRoutes::config);
        Route::get('{id}', [ConfigController::class, 'show']);
    });

    Route::get('/', [AppController::class, 'index']);
    Route::post('/datatables-order', [AppController::class, 'datatablesOrder']);

    Route::group(['prefix' => 'profile'], function() {

        Route::get('', [ProfileController::class, 'index'])->name(DBRoutes::profile);
    });

    Route::group(['prefix' => 'achievement'], function() {

        Route::get('', [UserAchievementController::class, 'index'])->name(DBRoutes::pageAchievement);
    });

    Route::group(['prefix' => 'pickup'], function() {
        Route::get('maps', [RequestPickUpController::class, 'maps']);
        Route::get('add', [RequestPickUpController::class, 'formAdd']);

        Route::get('', [RequestPickUpController::class, 'index'])->name(DBRoutes::pagePickUp);
        Route::post('', [RequestPickUpController::class, 'store']);
    });

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

        Route::group(['prefix' => 'users'], function() {
            Route::post('datatables', [UserController::class, 'datatables']);
            Route::get('form', [UserController::class, 'form']);
            Route::get('detail', [UserController::class, 'detail']);
            Route::get('check', [UserController::class, 'check'])->name(DBRoutes::userCheck);

            Route::get('', [UserController::class, 'index'])->name(DBRoutes::user);
            Route::post('', [UserController::class, 'store']);
            Route::get('{id}', [UserController::class, 'show']);
            Route::post('{id}', [UserController::class, 'update']);
            Route::delete('{id}', [UserController::class, 'destroy']);
        });

        Route::group(['prefix' => 'order'], function() {
            Route::post('datatables', [OrderController::class, 'datatables']);
            Route::get('schedule', [OrderController::class, 'schedule']);
            Route::get('detail', [OrderController::class, 'detail']);

            Route::get('', [OrderController::class, 'index'])->name(DBRoutes::order);
        });
    });
});

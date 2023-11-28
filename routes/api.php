<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Helpers\AccountHelper;

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
Route::prefix('auth')->group(function(){
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', function(Request $user_data){
            $auth_controller = new AuthController;
            
            if($user_data->user_type === "admin"){
                //check if logged in user is an admin
                if(AccountHelper::isLoggedUser('admin')){
                    return $auth_controller->register($user_data);
                }else{
                    return response()->json(['message' => 'Unauthorized user to create an admin account.'],401);
                }
            }else{
                return $auth_controller->register($user_data);
            }
        });
    });
});
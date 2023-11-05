<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AccountHelper;

//models
use App\Models\Account;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
        *Login 
        *@OA\POST(
        *   path="/api/auth/login",
        *   tags={"Auth"},
        *   summary="Authenticate user and generate JWT token",
        *   @OA\Parameter(
        *       name="email",
        *       in="query",
        *       description="User Email Address",
        *   ),
        *   @OA\Parameter(
        *       name="password",
        *       in="query",
        *       description="User Password",
        *   ),
        *   @OA\Response(response="200", description="Login successful", 
        *       @OA\JsonContent(
        *           type="object",
        *           @OA\Property(
        *               type="string",
        *               description="token",
        *               property="token",
        *           )
        *       )
        *   ),
        *   @OA\Response(response="401", description="Invalid credentials"),
        *   @OA\Response(response="422", description="Required Fields are Empty")
        *)
    */
    public function login(Request $request)
    {
        $valid_data = $request->validate([
            'email' => ['required','string','email'],
            'password' => ['required','string']
        ]); //returns errors if not valid

        $credentials = $request->only('email', 'password');
        $logged_in = Auth::attempt($credentials);
        
        if($logged_in){
            //create token for logged in user
            $user_data = Auth::user();
            $user_abilities = AccountHelper::setUserAbilities($user_data->type);
            $token = $request->user()->createToken('api_token',[$user_abilities])->plainTextToken; //get token only for response
            return response()->json(['message' => 'Login successful' , 'token'=> $token, 'success'=> true],200);
        }else{
            return response()->json(['message' => 'Invalid Credentials', 'success'=>false], 401);
        }
    }
}

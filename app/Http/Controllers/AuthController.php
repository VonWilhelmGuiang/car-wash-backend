<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\AccountHelper;

//models
use App\Models\Account;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     *Login 
     *  @OA\POST(
     *      path="/api/auth/login",
     *      tags={"Auth"},
     *      summary="Authenticate user and generate JWT token",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="User Email Address",
     *          required = true,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="User Password",
     *          required = true,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(response="200", description="Login successful", 
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="string",
     *                  description="token",
     *                  property="token",
     *              )
     *          )
     *      ),
     *      @OA\Response(response="401", description="Invalid credentials"),
     *      @OA\Response(response="422", description="Required Fields are Empty")
     *  )
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

    /**
     * Register
     *  @OA\POST(
     *      path = "/api/auth/register",
     *      tags = {"Auth"},
     *      summary = "User Registration",
     *      security = {
     *          {"sanctum":{}}
     *      },
     *      @OA\Parameter(
     *          name = "user_type",
     *          in = "query",
     *          required = true,
     *          schema={
     *              "type" : "string", 
     *              "enum" : {
     *                  "admin",
     *                  "shop_owner",
     *                  "vehicle_owner"
     *              }, 
     *              "default": "vehicle_owner"
     *          }
     *      ),
     *      @OA\Parameter(
     *          name = "first_name",
     *          in = "query",
     *          required = true,
     *          description = "User's first name"
     *      ),
     *      @OA\Parameter(
     *          name = "last_name",
     *          in = "query",
     *          required = true,
     *          description = "User's last name"
     *      ),
     *      @OA\Parameter(
     *          name = "phone_number",
     *          in = "query",
     *          required = true,
     *          description = "User's phone number"
     *      ),
     *      @OA\Parameter(
     *          name = "email",
     *          in = "query",
     *          required = true,
     *          description = "User's email address"
     *      ),
     *      @OA\Parameter(
     *          name = "password",
     *          in = "query",
     *          required = true,
     *          description = "User's password"
     *      ),
     *      @OA\Parameter(
     *          name = "password_confirmation",
     *          in = "query",
     *          required = true,
     *          description = "User's confirmation password"
     *      ),
     *      @OA\Parameter(
     *          name = "admin_password",
     *          in = "query",
     *          required = false,
     *          description = "Admin creator's password"
     *      ),
     *      @OA\Parameter(
     *          name = "user_info",
     *          in = "query",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property = "shop_owner_info",
     *                  type="object",
     *                  @OA\Property(
     *                      type = "string",
     *                      property = "shop_name"
     *                  ),
     *                  @OA\Property(
     *                      type = "string",
     *                      property = "location"
     *                  ),
     *                  @OA\Property(
     *                      type = "string",
     *                      property = "operating_hour_from"
     *                  ),
     *                  @OA\Property(
     *                      type = "string",
     *                      property = "operating_hour_to"
     *                  )
     *              ),
     *              @OA\Property(
     *                  property = "vehicle_owner_info",
     *                  type = "object",
     *                  @OA\Property(
     *                      type = "string",
     *                      property = "vehicle_model"
     *                  ),
     *                  @OA\Property(
     *                      type = "string",
     *                      property = "license_plate"
     *                  )
     *              )
     *          ),
     *      ),
     *      @OA\Response(response="200", description="User Created", 
     *          @OA\JsonContent(
     *              type = "object",
     *                  @OA\Property(
     *                      type = "boolean",
     *                      property = "success",
     *                  ),
     *                  @OA\Property(
     *                      type = "integer",
     *                      property = "user_account",
     *                      description = "User Account Type"
     *                  )
     *              )
     *          ),
     *      @OA\Response(response="401", description="Unauthorized",
     *          @OA\JsonContent(
     *              type = "object",
     *              @OA\Property(
     *                  type = "integer",
     *                  property = "success"
     *              ),
     *              @OA\Property(
     *                  type = "string",
     *                  property = "message"
     *              )
     *          )
     *      ),
     *      @OA\Response(response="422", description="Required Fields are Empty")
     *  )
    */
    public function register($user_data){
        $user_type = $user_data->user_type;

        switch($user_type){
            case "admin":
                //check password creator matches password
                if(Hash::check( $user_data->admin_password ,auth('sanctum')->user()->password )){
                    $create_admin = $this->createAdmin($user_data);
                    return response()->json($create_admin,  200);
                }else{
                    return response()->json([
                        'success'=> 0 ,
                        'message'=>'Admin Password is incorrect'
                    ],  401);
                }
            case "shop_owner":
                $create_shop_owner = $this->createShopOwner($user_data);
                break;
            case "vehicle_owner":
                break;
            default:
                return response()->json(['message' => "Required Fields are Empty"] , 422);
        }
    }

    private function createAdmin($user_data) : array {
        $valid_data = $user_data->validate([
            'email' => ['required','string','email','unique:accounts'],
            'password' => ['required','confirmed','min:6'],
            'admin_password' => ['required','string']
        ]); //returns errors if not valid
        
        // saving account data
        $account = new Account;
        $account->email = $user_data->email;
        $account->password = $user_data->password;
        $account->type = 0;
        $account->status = 1;
        $created = $account->save();
        return [
            'success' => $created,
            'user_account' => 0
        ];
    }

    private function createShopOwner($user_data) : array{
         $valid_data = $user_data->validate([
            'email' => ['required','string','email','unique:accounts'],
            'password' => ['required','confirmed','min:6'],
            'first_name' => ['required','string'], 
            'last_name' => ['required','string'], 
            'phone_number' => ['required','string'], 
            'shop_name' => ['required','string'],
            'location' => ['required','string'],
            'operating_hour_from' => ['required','string'],
            'operating_hour_to' => ['required','string']
        ]); //returns errors if not valid
        
    }
}

<?php
namespace App\Helpers;

class AccountHelper{
    public static function setUserAbilities ($user_type = null) : string
    {
        $abilities = '';
        switch($user_type)
        {
            case 0: //admin user
                $abilities = "*";
                break;
            case 1: //shop owner user
                $abilities = "show-orders";
                break;
            case 2: //vehicle owner user
                $abilities = "make-order";
                break;
        }
        return 'abilities:'.$abilities;
    }

    public static function isLoggedUser($user_type = null) :bool {
        $convert_user_type = false;

        switch($user_type)
        {
            case "admin": //admin user
                $convert_user_type = 0;
                break;
            case "shop_owner": //shop owner user
                $convert_user_type = 1;
                break;
            case "vehicle_user": //vehicle owner user
                $convert_user_type = 2;
                break;
            default:
                return false;
        }

        if(auth('sanctum')->check()){
            return auth('sanctum')->user()->type === $convert_user_type? true : false;
        }else{
            return false;
        }
    }
}
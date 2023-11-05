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
}
<?php
/**
 * Created by PhpStorm.
 * User: GJY
 * Date: 2018/11/4
 * Time: 13:33
 */

namespace App\ObServer;


use App\User;

class UserObServer {

    public function creating(User $user){
        $user -> email_token = str_random(10);
        $user -> email_active = false;
    }
}
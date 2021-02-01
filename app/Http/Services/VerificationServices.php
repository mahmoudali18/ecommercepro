<?php

namespace App\Http\Services;

use App\Models\User_verfication;


class VerificationServices
{
    /** set OTP code for mobile
     * @param $data
     *
     * @return User_verfication
     */
    public function setVerificationCode($data)
    {
        // generate new code
        $code = mt_rand(100000, 999999);
        // check if code use in db
        $data['code'] = $code;
        User_verfication::whereNotNull('user_id')->where(['user_id' => $data['user_id']])->delete();
        //save code in db
        return User_verfication::create($data);
    }


    // message
    public function getSMSVerifyMessageByAppName( $code)
    {
        $message = " is your verification code for your account";


        return $code.$message;
    }

}

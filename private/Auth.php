<?php
namespace Private;

use Exception;
use Private\Utils\Cryptor;

class Auth {
    static function user($roles){
        $rolesToId=[
            1=>'citizen',
            2=>'state_executive',
            3=>'city_executive',
            4=>'city_delegate',
            5=>'state_delegate',
            6=>'senator',
            7=>'councilor',
            8=>'congress'
        ];
        try {
            // Retrieve the "token" header
            $token = isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : '';
    
            if(empty($token)) throw new Exception("Auth error: No token in header", 401);
            
            $data = Cryptor::decrypt($token, $_ENV['CRYPTOR_SECRET']);
            if(!empty($roles)){
                $role=$rolesToId[$data['role']];
                if(!in_array($role,$roles) && $role!=='root') throw new Exception("Auth error: Unauthorized role", 401);
            }
    
            $expirationTimeInSeconds=3600;
            if((time()-$data['time'])>$expirationTimeInSeconds) throw new Exception("Auth error: Expiration", 401);
    
            if($data['ip']!==Request::ip()) throw new Exception("Auth error: IP not match with original", 401);
    
            //if($data['ua']!==Request::userAgent()) throw new Exception("Auth error: User Agent not match with original", 401);

            return array(true,'', $data);
        } catch(Exception $e) {
            if($e->getCode()==401) return array(false,$e->getMessage(),$e);
            throw $e;
        }
    }

    static function token($id, $mail, $city, $role='user') : string {
        return Cryptor::encrypt([
            'id'=>$id,
            'mail'=>$mail,
            'role'=>$role,
            'city'=>$city,
            'time'=>time(),
            'ip'=>Request::ip()
            //'ua'=>Request::userAgent()
        ],$_ENV['CRYPTOR_SECRET']);
    }

    static function middleware($roles) {
        $auth=self::user($roles);
        if($auth[0]) return $auth[2];
        else Response::json([
            'status'=>'ko',
            'error'=>$auth[1]
        ],401);
    }
}






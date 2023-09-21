<?php
require '../vendor/autoload.php';
Private\Utils\Dotenv::load('../.env');

use Private\Auth;
use Private\Request;
use Private\Response;
use Private\Data;
use Private\Utils\HashTool As HT;

try {
    Private\Cors::allow();
    //CHECK POST BODY
    Request::checkPostDataWithRegex([
        'mail'=>'/^\S+@\S+\.\S+$/',
        'pass'=>'/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
    ]);

    $userData = Data::get('User', [
        'mail'=>$_POST['mail'],
        'password'=>HT::create($_POST['pass'])
    ]);

    if(is_array($userData)) Response::json([
        "status"=>"ok",
        "token"=>Auth::token($userData[0]['id'],$userData[0]['mail'],$userData[0]['city'],$userData[0]['role']??'user')
    ]);
    else Response::json([
        "status"=>"ko"
    ]);
} catch (Exception|Error $e) {
    Response::json([
        "status"=>"ko",
        "error"=>$e->getMessage()
    ]);
}

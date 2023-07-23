<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');

use Private\Data;
use Private\Request;
use Private\Utils\Cryptor;
use Private\Utils\DatabaseTool;
use Private\Utils\HashTool As HT;
use Private\Utils\MailTool;

try {
    if($_SERVER['REQUEST_METHOD']=='POST'){
        Request::checkPostDataWithRegex([
            'mail'=>'/^\S+@\S+\.\S+$/',
            'pass'=>'/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
        ]);

        $userData = Data::get('User', [
            'mail'=>$_POST['mail']
        ]);

        Data::delete('Preuser',[
            'mail'=>$_POST['mail']
        ],1);

        if(is_array($userData)) {
            throw new Exception("The email is used.", 1);   
        }
    
        $data = [
            "mail"=>$_POST['mail'],
            "password"=>HT::create($_POST['pass'])
        ];

        $data['token'] = tokenGenerator();

        Data::insert('Preuser',$data);

        //SEND MAIL WITH TOKEN
        $mail = new MailTool();
        $mail->addAddress($data['mail']);
        $mail->setSubject("Confirm register");

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            $protocol = 'https';
        } else{
            $protocol = 'http';
        }
        $currentURL = $protocol . '://' . $_SERVER['SERVER_NAME'] . (isset($_SERVER['SERVER_PORT']) ? ':'. $_SERVER['SERVER_PORT'] : '') .$_SERVER['REQUEST_URI'];
        $baseURL = strtok($currentURL, '?');
        
        $registerURL="$baseURL/register.php?register=".$data['token'];

        $htmlBody=str_replace('{{URL}}',$registerURL,file_get_contents('../private/Templates/RegisterMail.html'));
        
        $altBody="Confirm:$registerURL";
        $mail->setBody($htmlBody,$altBody);
        $mail->send() ?? throw new Exception("Error sending the mail");
        Private\Response::json([
            "status"=>"ok"
        ],201);
    } else {
        $preuserData = Data::get('Preuser', [
            'token'=>$_GET['register']
        ]);

        //Check response data
        if(!is_array($preuserData)) throw new Exception("We dont have preuser data");
        $preuserData=$preuserData[0];

        Data::delete('Preuser', [
            'token'=>$_GET['register']
        ],1);

        unset($preuserData['token']);
        //SQL insert
        $preuserData = Data::insert('User',$preuserData);

        //Happy ending
        Private\Response::html('../private/Templates/RegisterLanding.html');
        Private\Response::json([
            "status"=>"ok"
        ],201);
    }
} catch(Exception $e){
    Private\Response::json([
        "error"=>1,
        "message"=>$e->getMessage(),
        "file"=>$e->getFile(),
        "line"=>$e->getLine(),
        "code"=>$e->getCode()
    ],400);
}


function tokenGenerator($size=255){
    // Generar un número entero aleatorio dentro del rango válido
    $randomNumber = random_int(0, $size);

    // Generar una cadena de caracteres aleatorios
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $randomNumber);
}
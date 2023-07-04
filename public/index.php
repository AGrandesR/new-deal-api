<?php

use Private\Response;

require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');

$auth=Private\Auth::user(['user','admin']);

list($auth,$err,$data)=$auth;
if(!$auth) Response::json([
    'status'=>'ko',
    'error'=>$err
],401);

Response::json([
    'status'=>'ok'
],200);

//if(!$auth) return Private\Response::redirect('/login.php');
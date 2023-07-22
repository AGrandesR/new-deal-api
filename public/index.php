<?php

use Private\Response;

require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');

$data=Private\Auth::middleware(['user','admin']);

Response::json([
    'status'=>'ok',
    'data'=>$data
],200);

//if(!$auth) return Private\Response::redirect('/login.php');
<?php
require '../vendor/autoload.php';
Private\Utils\Dotenv::load('../.env');

use Private\Response;


$auth=Private\Auth::middleware(['citizen','admin']);

Response::json([
    'status'=>'ok',
    'data'=>$auth
],200);

//if(!$auth) return Private\Response::redirect('/login.php');
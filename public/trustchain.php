<?php

use Private\Queries;
use Private\Response;
use Private\Cache;

require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');

$auth=Private\Auth::user(['user','admin']);

list($auth,$err,$data)=$auth;
if(!$auth) Response::json([
    'status'=>'ko',
    'error'=>$err
],401);

try{
    if(!isset($_GET['type']) || !in_array($_GET['type'],['land','city','state'])) throw new Exception("You need to send a type in the request Query", 400);
    $type=$_GET['type'];
    $userID=$data['id'];
    $cacheFileKey=$type."_trustChain$userID";
    Cache::show($cacheFileKey,'json');

    $trustChain=Queries::getTrustChain($userID,$type);
    $response=[
        'status'=>'ok',
        'data'=>$trustChain
    ];
    Cache::write($cacheFileKey,json_encode($response));
    Response::json($trustChain);

} catch(Exception $e) {
    if($e->getCode()==400) return Response::json(["status"=>"ko","error"=>$e->getMessage()],400);
    throw $e;
}
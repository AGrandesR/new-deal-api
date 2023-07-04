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
    $userID=$data['id'];
    $cacheFileKey="trustChain$userID";
    Cache::show($cacheFileKey,'json');
    $trustChain=Queries::getTrustChain($userID);
    Cache::write($cacheFileKey,json_encode($trustChain));
    Response::json($trustChain);

} catch(Exception $e) {
    if($e->getCode()==400) return Response::json(["status"=>"ko","error"=>$e->getMessage()],400);
    throw $e;
}
<?php

use Private\Queries;
use Private\Response;
use Private\Utils\DatabaseTool;

require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');

$auth=Private\Auth::user(['user','admin']);

list($auth,$err,$data)=$auth;
if(!$auth) Response::json([
    'status'=>'ko',
    'error'=>$err
],401);

//CHECK POST BODY
try{
    if(!isset($_POST['type']) || !in_array($_POST['type'],['land','city','state'])) throw new Exception("Incorrect type", 400);
    if(!isset($_POST['to'])) throw new Exception("Don't 'to' parameter", 1);
    //if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/',$_POST['pass'])) throw new Exception("Incorrect pass type", 400);
    
    //"UPDATE  * FROM users WHERE mail = :mail AND `password` = :pass";
    $to=$_POST['to'];
    $type= $_POST['type'];
    if($type=='land') {
        return Response::json(["status"=>"ko","error"=>'Not authorized operation'],401);
    }

    $setter=$type . '_trust';
    $sqlUpdateTrust="UPDATE newdeal.users SET `$setter` = :to WHERE `id` = :id";

    if($to==$data['id']) throw new Exception("You can't to trust to yourself", 400);
    $trustChain=Queries::getTrustChain($data['id']);
    if(in_array($to, $trustChain)) throw new Exception("You can't to trust someone in your trustchain", 400);
    $trustChain;//TODO:Call to $trusChain function
    if(in_array($to,$trustChain)) throw new Exception("You can't to trust to yourself or someone in your trustchain", 400);
    
    $work = DatabaseTool::sql('',$sqlUpdateTrust,['to'=>$to,'id'=>$data['id']]);
    if($work) Response::json([
        'status'=>'ok',
        'trust'=>$to
    ]);
} catch(Exception $e) {
    if($e->getCode()==400) return Response::json(["status"=>"ko","error"=>$e->getMessage()],400);
    throw $e;
}
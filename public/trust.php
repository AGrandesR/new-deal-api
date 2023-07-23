<?php

use Private\Data;
use Private\Queries;
use Private\Request;
use Private\Response;
use Private\Utils\DatabaseTool;

require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');

$data=Private\Auth::middleware(['citizen','admin']);

//CHECK POST BODY
try{
    list($type,$to)=Request::checkPostDataWithRegex([
        'type'=>'/^(land|city|state)$/',
        'to'=>'/^[0-9]+$/'
    ]);

    if($type=='land') {
        return Response::json(["status"=>"ko","error"=>'Not authorized operation'],401);
    }

    $setter=$type . '_trust';
    $sqlUpdateTrust="UPDATE newdeal.users SET `$setter` = :to WHERE `id` = :id";

    if($to==$data['id']) throw new Exception("You can't to trust to yourself", 400);
    // $trustChain=Queries::getTrustChain($data['id'],$type);
    // if(in_array($to, $trustChain)) throw new Exception("You can't to trust someone in your trustchain", 400);
    
    $work = Data::update('User',[
        $setter=>$to
    ],[
        'id'=>$data['id']
    ]);

    if($work) Response::json([
        'status'=>'ok',
        'trust'=>$to
    ]);
} catch(Exception $e) {
    if($e->getCode()==400) return Response::json(["status"=>"ko","error"=>$e->getMessage()],400);
    throw $e;
}
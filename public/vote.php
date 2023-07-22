<?php

use Private\Response;
use Private\Utils\DatabaseTool;

require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');

$auth=Private\Auth::user(['user','admin']);

list($auth,$err,$user)=$auth;
if(!$auth) Response::json([
    'status'=>'ko',
    'error'=>$err
],401);

try{
    if(!isset($_POST['vote']) || !in_array($_POST['vote'],['YES', 'NO', 'WHITE'])) throw new Exception("Incorrect type", 400);
    if(!isset($_POST['project'])) throw new Exception("Don't 'to' parameter", 1);
    if(!isset($_POST['secret'])) throw new Exception("Don't 'secret' parameter", 1);

    //Check if is land that you have the same land that the project
    $userData=DatabaseTool::sql('','SELECT * FROM User WHERE id=:id',[
        'id'=>$user['id']
    ]);

    


    $vote=$_POST['vote'];
    $project= $_POST['project'];

    //First check if project exist and not closed
    $projectQuery = 'SELECT * FROM Project p WHERE id=:id AND expiration_date < :actualdate ';
    $projectData = DatabaseTool::sql('',$userVote,[
        'id'=>$user['id'],
        'actualdate'=>time()
    ]);
    
    //Create hashed vote_id
    $voteid=HashTool::create($_POST['user_id'] . '_' . $_POST['secret'] . '_' . $_POST['project_id']);






    if($type=='land') {
        return Response::json(["status"=>"ko","error"=>'Not authorized operation'],401);
    }
    $setter=$type . '_trust';
    if($to==$data['id']) throw new Exception("You can't to trust to yourself", 400);
    $trustChain=Queries::getTrustChain($data['id']);
    if(in_array($to, $trustChain)) throw new Exception("You can't to trust someone in your trustchain", 400);
    
    $sqlUpdateTrust="UPDATE newdeal.users SET `$setter` = :to WHERE `id` = :id";
    $work = DatabaseTool::sql('',$sqlUpdateTrust,['to'=>$to,'id'=>$data['id']]);
    if($work) Response::json([
        'status'=>'ok'
    ]);
} catch(Exception $e) {
    if($e->getCode()==400) return Response::json(["status"=>"ko","error"=>$e->getMessage()],400);
    throw $e;
}
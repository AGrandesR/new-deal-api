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
    $auth=Private\Auth::user([]);
    list($auth,$err,$user)=$auth;
    
    if(!$auth) Response::json([
        "status"=>"ko",
        "error"=>$err
    ]);

    if(!isset($_GET['type']) || !in_array($_GET['type'],['city','state'])) return Response::json([
        "status"=>"ok",
        "error"=>"Not type",
        "message"=>"You have to indicate a type"
    ]);

    $where['role']=$_GET['type']=='city'? 4:5;
    if($_GET['type']=='city') $where['city']=$auth['city'];
    $candidates = Data::get('User',$where);
    $where['city']=$_GET['type']=='city'? 7:8;
    $candidatesElected = Data::get('User', $where);

    $response=[];
    foreach($candidatesElected as $candidate) {
        $response[]=[
            "name"=>$candidate['id'],
            "value"=>$candidate['id'],
            "elected"=>true
        ];
    }
    foreach($candidates as $candidate) {
        $response[]=[
            "name"=>$candidate['id'],
            "value"=>$candidate['id'],
            "elected"=>false
        ];
    }
    

    Response::json([
        "status"=>"ok",
        "data"=>$response
    ]);
} catch (Exception|Error $e) {
    Response::json([
        "status"=>"ko",
        "error"=>$e->getMessage()
    ]);
}

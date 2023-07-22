<?php

use Private\Response;
use Private\Cache;
use Private\Utils\DatabaseTool;

require '../vendor/autoload.php';

Private\Utils\Dotenv::load('../.env');
$auth=Private\Auth::user(['user','councilor','congress','admin']);
list($auth,$err,$user)=$auth;
if(!$auth) Response::json([
    'status'=>'ko',
    'error'=>$err
],401);

try{
    //region Page&size
    $page = $_GET['page']??1;
    $codePage=$page-1;
    $size = $_GET['size']??20;
    if($size>100) $size=100;
    if($size % 5 !== 0 ) round($numero / 5) * 5;


    //endregion
    //region Filters
    $filters=[];
    //endregion
    //Buscamos el SQL de la sentecia actual
    $sqlGetProject='SELECT * FROM Projects LIMIT :page, :limit';
    $projects=DatabaseTool::sql('',$sqlGetProject,[
        'page'=>$codePage,
        'limit'=>$size
    ]);
    if(!$auth) Response::json([
        'status'=>'ok',
        'data'=>[
            "page"=>$page,
            "size"=>$size,
            "filters"=>$filters,
            "projects"=>$projects
        ]
    ],401);   

} catch(Exception $e) {
    if($e->getCode()==400) return Response::json(["status"=>"ko","error"=>$e->getMessage()],400);
    throw $e;
}
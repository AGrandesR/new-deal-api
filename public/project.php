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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth=Private\Auth::user(['councilor','congress','admin']);
        list($auth,$err,$user)=$auth;
        if(!$auth) Response::json([
            'status'=>'ko',
            'error'=>$err
        ],401);

        $id = $_POST['id']??null;
        $title = $_POST['title'];
        $description = $_POST['description'];
        $expiration_date = $_POST['expiration_date'];

        if($id===null) {//Create SQL
            $sqlUpsert = 'INSERT INTO Project (title, description, expiration_date, vote_system, owner) VALUES (:title,:description,:expiration_date,:vote_system,:owner);';
            $keys=[
                'title'=>$title,
                'description'=>$description,
                'expiration_date'=>$expiration_date,
                'vote_system'=>0,
                'owner'=>$user['id']
            ];
        } else {//Update SQL

            foreach ($_POST as $key => $value) {
                if(!in_array($key,['title','description','expiration_date'])) continue;

            }
            $sqlUpsert = 'UPDATE Project SET  WHERE id=:id AND owner=:owner';
            $keys=['id'=>$id, 'owner'=>$user['id']];
        }
        $result=DatabaseTool::sql('',$sqlUpsert,$keys);

        list($auth,$err,$data)=$auth;
        if(!$auth) Response::json([
            'status'=>'ko',
            'error'=>$err
        ],401);
    }
    //Buscamos el SQL de la sentecia actual
    $sqlGetProject='SELECT * FROM ';
    $project=DatabaseTool::sql('',$sqlGetProject,$keys);
    if(!$auth) Response::json([
        'status'=>'ok',
        'data'=>$project
    ],401);   

} catch(Exception $e) {
    if($e->getCode()==400) return Response::json(["status"=>"ko","error"=>$e->getMessage()],400);
    throw $e;
}
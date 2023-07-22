<?php
require '../vendor/autoload.php';
use Private\Utils\HashTool;
use Private\Response;

if(!isset($_POST['user_id'])) throw new Exception("Don't 'user_id' parameter", 1);
if(!isset($_POST['project_id'])) throw new Exception("Don't 'project_id' parameter", 1);
if(!isset($_POST['secret'])) throw new Exception("Don't 'secret' parameter", 1);

Response::json([
    "status"=>"ok",
    "vote_id"=>HashTool::create($_POST['user_id'] . '_' . $_POST['secret'] . '_' . $_POST['project_id'])
]);
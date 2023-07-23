<?php
namespace Private;

use Exception;

class Request {
    static function ip() : string {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (!empty($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
        else return '';
    }

    static function userAgent() {
        return $_SERVER['HTTP_USER_AGENT']??'';
    }

    static function checkPostData(array $postDataToCheck,$errorMsg='Fields not received: %fields%') {
        $notReceived=[];
        foreach ($postDataToCheck as $value) {
            if(!isset($_POST[$value])) $notReceived[]=$value;
        }
        if(!empty($notReceived)) throw new Exception(str_replace('%fields%',implode(',',$notReceived),$errorMsg),400);
    }
    static function checkPostDataWithRegex(array $postDataToCheck,$errorMsg='Fields not received: %fields%') {
        $notReceived=[];
        $returnData=[];
        foreach ($postDataToCheck as $value=>$regex) {
            if(!isset($_POST[$value]) || !preg_match($regex,$_POST[$value])) $notReceived[]=$value;
            else $returnData[]=$_POST[$value];
        }
        if(!empty($notReceived)) throw new Exception(str_replace('%fields%',implode(',',$notReceived),$errorMsg),400);
        return $returnData;
    }
}
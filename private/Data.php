<?php
namespace Private;

use Private\Utils\DatabaseTool;

class Data {
    static function get(string $from,array $where, $flag='') {
        $str='';
        $first=false;
        foreach ($where as $key => $value) {
                $str.=$first? '':' AND ';
                $str.="$key :$key";
        }
        $sql="SELECT * FROM $from WHERE $str";
        DatabaseTool::sql($flag,$sql,$where);
    }
}
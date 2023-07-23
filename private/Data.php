<?php
namespace Private;

use Exception;
use Private\Utils\DatabaseTool;

class Data {
    static function get(string $from,array $where, $flag='') {
        $str='';
        $first=true;
        foreach ($where as $key => $value) {
                $str.= $first ? ($first=false) : ' AND ';
                $str.="`$key` = :$key";
        }
        $sql="SELECT * FROM $from WHERE $str";
        return DatabaseTool::sql($flag,$sql,$where);
    }
    static function delete(string $from,array $where, int $limit, $flag='') {
        $str='';
        $first=true;
        if(empty($where)) throw new Exception("https://www.youtube.com/watch?v=i_cVJgIz_Cs", 1);
        foreach ($where as $key => $value) {
            $str.= $first ? ($first=false) : ' AND ';
            $str.="`$key` = :$key";
        }
        if($str=='') throw new Exception("https://www.youtube.com/watch?v=i_cVJgIz_Cs", 1);
        $sql="DELETE FROM $from WHERE $str LIMIT $limit";
        return DatabaseTool::sql($flag,$sql,$where);
    }

    static function update(string $to, array $what, array $where, $flag='') {
        $wherestr='';
        $whatstr='';
        $first=true;
        $newWhat=[];
        $newWhere=[];
        foreach ($what as $key => $value) {
            $newKey="set_$key";
            $whatstr.= $first ? ($first=false) : ', ';
            $whatstr.=" `$key` = :$newKey ";
            $newWhat[$newKey]=$value;
        }
        $first=true;
        foreach ($where as $key => $value) {
            $newKey="whr_$key";
            $wherestr.= $first ? ($first=false) : ' AND ';
            $wherestr.="`$key` = :$newKey";
            $newWhere[$newKey]=$value;
        }
        $sql="UPDATE $to SET $whatstr WHERE $wherestr";
        return DatabaseTool::sql($flag,$sql,array_merge($newWhere,$newWhat));
    }
}
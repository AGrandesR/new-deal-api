<?php
namespace Private;

use Exception;
use Private\Utils\DatabaseTool;

class Queries {
    static function getTrustChain($id, $setter){
        if(!in_array($setter,['city','land','state'])) throw new Exception("Avoid SQL injection Error", 1);
        $setter=$setter.'_trust';
        $sql="WITH RECURSIVE recursive_loop  AS (
            SELECT $setter FROM newdeal.User WHERE id=:id AND $setter IS NOT NULL
            UNION ALL
            SELECT c.$setter FROM newdeal.User c
            INNER JOIN recursive_loop  l ON l.$setter = c.id
        ) SELECT * FROM recursive_loop ;";

        $data=DatabaseTool::sql('',$sql, [
            'id'=>$id
        ]);
        if(!is_array($data) || empty($data)) return [];
        return array_column($data, $setter);
    }

    static function getTrustLegacy($id, $setter) {
        if(!in_array($setter,['city','land','state'])) throw new Exception("Avoid SQL injection Error", 1);
        $setter=$setter.'_trust';
        $sql="WITH RECURSIVE recursive_loop AS (
            SELECT id FROM newdeal.user u WHERE state_trust =:state_trust 
            UNION ALL
            SELECT c.id FROM newdeal.User c 
            INNER JOIN recursive_loop l ON l.id = c.state_trust 
            where c.state_trust IS NOT null
            ) SELECT COUNT(*) FROM recursive_loop;";
        $data=DatabaseTool::sql('',$sql, [
            'id'=>$id
        ]);
        if(!is_array($data) || empty($data)) return [];
        return array_column($data, $setter);
    }
}
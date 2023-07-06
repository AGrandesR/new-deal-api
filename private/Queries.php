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
        return array_column(DatabaseTool::sql('',$sql, [
            'id'=>$id
        ]), $setter);
    }
}
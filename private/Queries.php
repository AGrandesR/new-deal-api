<?php
namespace Private;

use Private\Utils\DatabaseTool;

class Queries {
    static function getTrustChain($id){
        $sql="WITH RECURSIVE recursive_loop  AS (
            SELECT city_trust FROM newdeal.users WHERE id=:id AND city_trust IS NOT NULL
            UNION ALL
            SELECT c.city_trust FROM newdeal.users c
            INNER JOIN recursive_loop  l ON l.city_trust = c.id
        ) SELECT * FROM recursive_loop ;";
        return array_column(DatabaseTool::sql('',$sql, [
            'id'=>$id
        ]),'city_trust');
    }
}
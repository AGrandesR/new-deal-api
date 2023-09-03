<?php
namespace Private;

class Cors {
    static function allow() {
        $origin = $_SERVER['HTTP_ORIGIN'];

        $allowedOrigins = array(
            'http://localhost:3000'
        );

        if (in_array($origin, $allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $origin);

            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
            header('Access-Control-Allow-Headers: Authorization, Content-Type');

            header('Access-Control-Allow-Credentials: true');
        }
    }
}
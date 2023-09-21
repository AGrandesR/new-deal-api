<?php
namespace Private;

class Cors {
    static function allow(array $url=['http://localhost:3000'], array $methods=[], array $headers=['Authorization', 'Content-Type']) {
        $origin = $_SERVER['HTTP_ORIGIN'];

        $allowedOrigins = $url;

        if (in_array($origin, $allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $origin);

            header('Access-Control-Allow-Methods: ' . empty($methods)?'*' : implode(', ', $methods));
            header('Access-Control-Allow-Headers: ' . implode(', ', $headers));

            header('Access-Control-Allow-Credentials: true');
        }
    }
}
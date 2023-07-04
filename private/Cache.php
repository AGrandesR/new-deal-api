<?php
namespace Private;

use Error;
use Exception;

class Cache {
    static function write(string $file_key, string $file_data) {
        try {
            $folderpath='../tmp/cache/';
            $folderpath=str_replace('/',DIRECTORY_SEPARATOR,$folderpath);
            $filepath="$file_key.txt";
            if (!file_exists($folderpath)) {
                //TODO: Throw email or telegram message
                //mkdir('cache', 0666, true);
            }
            file_put_contents($file_key, $file_data);
        } catch (Exception|Error $e){
            //TODO: Throw email or telegram message
        }
    }
    static function read(string $file_key, int $expiration=600) {
        $filepath="../tmp/cache/$file_key.txt";
        $filepath=str_replace('/',DIRECTORY_SEPARATOR,$filepath);
        if (file_exists($filepath)) {
            return file_get_contents($filepath);
        }
        return false;
    }

    /**
     * This function is to dont waste resource checking content if is a response cache
     */
    static function show(string $file_key, $format='txt', int $expirationSeconds=600) {
        $filepath="../tmp/cache/$file_key.txt";
        if (file_exists($filepath)) {
            $creationTimestamp=filectime($filepath);
            if(($creationTimestamp+$expirationSeconds)>time()) return false;
            if($format=='json') header('Content-Type: application/json');
            readfile($filepath);
            exit;
        } else {
            return false;
        }        
    }
}
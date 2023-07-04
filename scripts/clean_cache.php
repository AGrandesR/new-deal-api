<?php
if(getcwd()!==__DIR__ && getcwd().DIRECTORY_SEPARATOR.'scripts'!==__DIR__) throw new Exception("Incorrect PHP path call", 1);
if(getcwd().DIRECTORY_SEPARATOR.'scripts'==__DIR__) $directory = '../tmp/cache';
if(getcwd()!==__DIR__) $directory='tmp/cache';
if(!$directory) throw new Exception("Error Processing Request", 1);

$timeCleaning=intval($argv[1] ?? 3600); //How old will be the files to kill
$filename=$argv[2]??''; //If '' this means that the code we kill all elements 😱

echo getcwd() . "\n";


deleteOldFiles(time(), $directory, $timeCleaning,$filename);

function deleteOldFiles($currentHour, $directory, $timeCleaning, $checkFileName) {
    $directory=str_replace('/',DIRECTORY_SEPARATOR,$directory);

    if (is_dir($directory)) {
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $directory .DIRECTORY_SEPARATOR . $file;
                echo filectime($filePath) . " < " . ($currentHour - $timeCleaning);
                if (is_file($filePath) && (filectime($filePath) < $currentHour - $timeCleaning)) {
                    unlink($filePath);
                    echo 'File deleted: ' . $filePath . '<br>';
                } elseif (is_dir($filePath)) {
                    deleteOldFiles($currentHour,$filePath,$timeCleaning,$checkFileName);
                }
            }
        }
    }
}
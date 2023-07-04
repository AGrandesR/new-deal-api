<?php
namespace Private\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Dir {
    static function size($folder) {
        $size = 0;
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder)) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }
}

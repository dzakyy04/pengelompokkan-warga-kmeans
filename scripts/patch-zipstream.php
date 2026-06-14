<?php

/**
 * Patch ZipStream File.php to fix PHP 8.2 TypeError:
 * "Cannot assign float to property ZipStream\File::$crc of type int"
 *
 * hexdec() can return float for large CRC32 values, but the $crc property
 * is typed as int in ZipStream\File. This patch adds an (int) cast.
 */

$file = __DIR__ . '/../vendor/maennchen/zipstream-php/src/File.php';

if (!file_exists($file)) {
    echo "ZipStream File.php not found, skipping patch.\n";
    exit(0);
}

$content = file_get_contents($file);
$search = '$this->crc = hexdec(hash_final($hash));';
$replace = '$this->crc = (int) hexdec(hash_final($hash));';

if (str_contains($content, $replace)) {
    echo "ZipStream File.php already patched.\n";
    exit(0);
}

if (str_contains($content, $search)) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "ZipStream File.php patched successfully.\n";
} else {
    echo "ZipStream File.php: target line not found, patch may not be needed.\n";
}

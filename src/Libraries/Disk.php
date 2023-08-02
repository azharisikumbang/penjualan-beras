<?php

class Disk
{
    public static function simpanBuktiPembayaran(array $files): false|string
    {
        $location = config('disk', 'bukti_pembayaran');
        $ext = pathinfo($files['name'], PATHINFO_EXTENSION);
        $filename = strtolower(md5($files['name'] . microtime()) . "." . $ext);
        $target = sprintf("%s/%s", rtrim($location, "/"), basename($filename));

        if(false === move_uploaded_file($files['tmp_name'], $target)) return false;

        return $filename;
    }
}
<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * @param string $fileName
     * @return array
     */
    public static function parseFile(string $fileName): array
    {
        if (strpos($fileName, 'http') !== false) {
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $fileName);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $file = curl_exec($curl_handle);
            curl_close($curl_handle);
        } else {
            $file = file_get_contents(storage_path() . '/app/' . $fileName);
        }

        $data = explode("\n", $file);
        return $data ?: [];
    }
}
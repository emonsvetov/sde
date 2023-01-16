<?php

namespace App\Models;

use App\Helpers\FileHelper;

class Driver
{
    private array $data = [];

    public function __construct(string $filePath)
    {
        $this->setData($filePath);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(string $filePath)
    {
        $drivers = FileHelper::parseFile($filePath);
        $drivers = $this->prepareDrivers($drivers);
        $this->data = $drivers;
    }

    /**
     * Preliminary calculation of drivers
     *
     * @param array $drivers
     * @return array
     */
    private function prepareDrivers(array $drivers): array
    {
        $result = [];
        $a = ["a", "e", "i", "o", "u"];
        foreach ($drivers as $driver) {
            $vowel = 0;
            $consonant = 0;
            foreach (count_chars($driver, 1) as $k => $v) {
                if (in_array(strtolower(chr($k)), $a)) {
                    $vowel += $v;
                } else {
                    $consonant += $v;
                }
            }
            $result[] = (object)[
                'name' => $driver,
                'length' => strlen($driver),
                'vowel' => $vowel,
                'consonant' => $consonant
            ];
        }
        return $result;
    }
}

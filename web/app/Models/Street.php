<?php

namespace App\Models;

use App\Helpers\FileHelper;

class Street
{
    const TYPE_EVEN = 'even';
    const TYPE_ODD = 'odd';

    private array $data = [];

    public function __construct(string $filePath)
    {
        $this->setData($filePath);
    }

    /**
     * Street address parsing and normalization
     *
     * @param string $street
     * @return string
     */
    public function parseStreetName(string $street): string
    {
        // TODO: need technical specification
        return $street;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(string $filePath)
    {
        $streets = FileHelper::parseFile($filePath);
        $streets = $this->prepareStreets($streets);
        $this->data = $streets;
    }

    /**
     * Preliminary calculation of streets
     *
     * @param array $streets
     * @return array
     */
    private function prepareStreets(array $streets): array
    {
        $result = [];
        foreach ($streets as $street) {
            $streetName = $this->parseStreetName($street);
            $streetLength = strlen($streetName);
            $type = ($streetLength % 2 === 0) ? self::TYPE_EVEN : self::TYPE_ODD;
            $result[] = (object)[
                'name' => $this->parseStreetName($streetName),
                'length' => $streetLength,
                'type' => $type
            ];
        }
        return $result;
    }
}

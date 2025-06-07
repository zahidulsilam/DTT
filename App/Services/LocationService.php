<?php

namespace App\Services;

use App\Models\LocationModel;

class LocationService
{
    private $locationModel;

    function __construct()
    {
        $this->locationModel = new LocationModel();
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @return array
     */
    function addLocation(array $data): array
    {
        $data = $this->locationModel->addLocation($data);
        return $data;
    }

    /**
     * Undocumented function
     *
     * @param int $locationId
     * @return boolean
     */
    function checkLocationExist(int $locationId): bool
    {
        $data = $this->locationModel->findLocationId($locationId);
        if (empty($data)) {
            return false;
        }
        return true;
    }
}

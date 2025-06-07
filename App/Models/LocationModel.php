<?php

namespace App\Models;

use App\Plugins\Di\Injectable;
use App\Plugins\Http\Response as Status;
use Exception;

class LocationModel extends Injectable
{

    private $userTable;
    private $locationTable;

    function __construct()
    {
        $this->userTable = 'users';
        $this->locationTable = 'locations';
    }

    /**
     * Model function to add Location.
     * @return array
     */

    function addLocation($data): array
    {
        try {
            $sql = 'INSERT INTO ' . $this->locationTable . '(city, address,country_code,zip_code,phone_no) VALUES (:city, :address,:country_code,:zip_code,:phone_no)';
            $data = [
                ':city' => $data['city'],
                ':address' => $data['address'],
                ':country_code' => $data['country_code'],
                ':zip_code' => $data['zip_code'],
                ':phone_no' => $data['phone_no'],
            ];
            $query =  $this->db->executeQuery($sql, $data);
            $insertId = $this->db->getLastInsertedId();
            $query = "SELECT * FROM $this->locationTable WHERE id = :id";
            $bind = [
                ':id' => $insertId
            ];
            $getInsertedData = $this->db->executeQueryFetchSingleData($query, $bind);
            return $getInsertedData;
        } catch (Exception $e) {
            (new Status\BadRequest(['message' => $e->getMessage(), 'data' => []]))->send();
            return [];
        }
        return [];
    }

    /**
     * Model function to add Location.
     * @return array
     */

    function findLocationId($locationId): array
    {
        $query = "SELECT *  FROM " . $this->locationTable . " WHERE id = :id LIMIT 1";
        $bind = [
            ':id' => $locationId,
        ];
        $dataResult = $this->db->executeQueryFetchSingleData($query, $bind);
        return $dataResult;
    }
}

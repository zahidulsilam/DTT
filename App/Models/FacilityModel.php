<?php

namespace App\Models;

use App\Plugins\Di\Injectable;
use App\Plugins\Http\Response as Status;
use Exception;

class FacilityModel extends Injectable
{

    private $userTable;
    private $LocationTable;
    private $FacilityTable;
    private $TagTable;
    private $TagFacTable;

    function __construct()
    {
        $this->userTable = 'users';
        $this->LocationTable = 'locations';
        $this->FacilityTable = 'facilities';
        $this->TagTable = 'tags';
        $this->TagFacTable = 'tag_facility';
    }

    /**
     * add Facility in Database
     *
     * @param array $data
     * @return array
     */
    function addFacility(array $data): array
    {
        try {
            $sql = 'INSERT INTO ' . $this->FacilityTable . '(name, location_id,user_id) VALUES (:name, :location_id,:user_id)';
            $data = [
                ':name' => $data['name'],
                ':location_id' => $data['location_id'],
                ':user_id' => $data['user_id'],
            ];
            $query =  $this->db->executeQuery($sql, $data);
            $insertId = $this->db->getLastInsertedId();
            $query = "SELECT * FROM $this->FacilityTable WHERE id = :id";
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
     * find fcility in db from id
     *
     * @param integer $locationId
     * @return array
     */
    function findFaclityById(int $facilityId): array
    {
        $query = "SELECT *  FROM " . $this->FacilityTable . " WHERE id = :id LIMIT 1";
        $bind = [
            ':id' => $facilityId,
        ];
        $dataResult = $this->db->executeQueryFetchSingleData($query, $bind);
        return $dataResult;
    }

    /**
     * fetch single data to check if tags already exist
     *
     * @param string $tagName
     * @return array
     */
    function fetchTagFromName(string $tagName): array
    {
        $query = "SELECT *  FROM " . $this->TagTable . " WHERE name = :name LIMIT 1";
        $bind = [
            ':name' => $tagName,
        ];
        $dataResult = $this->db->executeQueryFetchSingleData($query, $bind);
        return $dataResult;
    }

    /**
     * tag Insert and get Id
     *
     * @param string $tagName
     * @return integer
     */
    function tagInsertAndGetId(string $tagName): int
    {
        $sql = 'INSERT INTO ' . $this->TagTable . '(name) VALUES (:name)';
        $data = [
            ':name' => $tagName,
        ];
        $this->db->executeQuery($sql, $data);
        $insertId = $this->db->getLastInsertedId();
        return $insertId;
    }

    /**
     * insert Relatioship tag and facility
     *
     * @param integer $tagId
     * @param integer $facilityId
     * @return boolean
     */
    function addRelationFacilityAndTag(int $tagId, int $facilityId): bool
    {
        $sql = 'INSERT INTO ' . $this->TagFacTable . '(facility_id,tag_id) VALUES (:facilityId,:tagId)';
        $data = [
            ':facilityId' => $facilityId,
            ':tagId' => $tagId,
        ];
        return $this->db->executeQuery($sql, $data);
    }

    /**
     * check if relation already exist
     *
     * @param integer $tagId
     * @param integer $data
     * @return array
     */
    function findAlreadyAssignTags(int $tagId, int $facilityId): array
    {
        $query = "SELECT *  FROM " . $this->TagFacTable . " WHERE facility_id = :facilityId and tag_id = :tagId LIMIT 1";
        $bind = [
            ':facilityId' => $facilityId,
            ':tagId' => $tagId,
        ];
        $dataResult = $this->db->executeQueryFetchSingleData($query, $bind);
        return $dataResult;
    }

    /**
     * get facility data from query
     *
     * @return array
     */
    function getFacilityData($facilityId, int | string | null $q = null): array
    {
        if ($facilityId != null) {
            $where = 'WHERE f.id = :facilityId';
        } else if ($q != null) {
            $where = $this->searchFillter($q);
        } else {
            $where = '';
        }
        $query = "
        SELECT 
            f.id AS facility_id,
            f.name AS facility_name,
            l.city AS location_city,
            l.address AS location_address,
            l.country_code AS location_country_code,
            l.phone_no AS location_phone_no,
            l.zip_code AS location_zip_code,
            u.name AS user_full_name,
            u.email AS user_email,
            u.user_name AS user_name,
            t.id AS tag_id,
            t.name AS tag_name
        FROM facilities f
        JOIN locations l ON f.location_id = l.id
        JOIN users u ON f.user_id = u.id
        LEFT JOIN tag_facility ft ON f.id = ft.facility_id
        LEFT JOIN tags t ON ft.tag_id = t.id
         " . $where . " 
        ORDER BY f.id
        ";
        if ($where == null) {
            $data = $this->db->executeQueryFetchData($query);
        } else if ($facilityId != null) {
            $bind = [
                ':facilityId' => $facilityId
            ];
            $data = $this->db->executeQueryFetchData($query, $bind);
        } else if ($q != null) {
            $q = "%$q%";
            $data = $this->db->searchqueryLike($query, $q);
        }
        return $data;
    }

    /**
     * Undocumented function
     *
     * @param  $data
     * @return boolean
     */
    function updateFacilityAndTags($data): bool
    {
        if (isset($data['name'])) {
            $sql = 'UPDATE ' . $this->FacilityTable . ' SET name = :name WHERE id = :id';
            $dataquery = [
                ':id' => $data['facilityId'],
                ':name' => $data['name'],
            ];
            $this->db->executeQuery($sql, $dataquery);
        }
        if (isset($data['location_id'])) {
            $sql = 'UPDATE ' . $this->FacilityTable . ' SET location_id = :location_id WHERE id = :id';
            $dataQuery = [
                ':id' => $data['facilityId'],
                ':location_id' => $data['location_id'],
            ];
            $this->db->executeQuery($sql, $dataQuery);
        }
        if (isset($data['tags'])) {
            $this->deleteTagsOfFacility($data['facilityId']);
            foreach ($data['tags'] as $tag) {
                $query = "SELECT COUNT(*) as tags,id as tag_id FROM " . $this->TagTable . " WHERE name = :name";
                $bindData = [
                    ':name' => $tag
                ];
                $getTag = $this->db->executeQueryFetchSingleData($query, $bindData);
                if ($getTag['tags'] == 0) {
                    $tagId = $this->tagInsertAndGetId($tag);
                } else {
                    $tagId = $getTag['tag_id'];
                }
                $this->addRelationFacilityAndTag($tagId, $data['facilityId']);
            }
        }
        return true;
    }

    /**
     * delete Tags
     *
     * @param integer $facilityId
     * @return boolean
     */
    private function deleteTagsOfFacility(int $facilityId): bool
    {
        $query = "DELETE FROM " . $this->TagFacTable . " WHERE facility_id = :facility_id";
        $dataQuery = [
            ':facility_id' => $facilityId,
        ];
        return $this->db->executeQuery($query, $dataQuery);
    }

    /**
     * create search where clause  condition
     *
     * @param [type] $q
     * @return void
     */
    private function searchFillter(int|string $q): string
    {
        $where = ' WHERE f.name Like :q OR l.city  Like :q OR t.name Like :q ';
        return $where;
    }

    function deleteFacilityAndItsTags(int $facilityId): bool
    {
        $query = "DELETE FROM " . $this->FacilityTable . " WHERE id = :facility_id";
        $dataQuery = [
            ':facility_id' => $facilityId,
        ];
        $this->db->executeQuery($query, $dataQuery);
        return $this->deleteTagsOfFacility($facilityId);
    }
}

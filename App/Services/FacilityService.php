<?php

namespace App\Services;

use App\Models\FacilityModel;
use App\Models\LocationModel;

class FacilityService
{
    private $facilityModel;

    function __construct()
    {
        $this->facilityModel = new FacilityModel();
    }

    /**
     * Undocumented function
     *
     * @param array $data
     * @return array
     */
    function addFacility(array $data): array
    {
        $data = $this->facilityModel->addFacility($data);
        return $data;
    }

    /**
     * check Facility record have in database
     *
     * @param integer $locationId
     * @return boolean
     */
    function checkFacilityExist(int $locationId): bool
    {
        $data = $this->facilityModel->findFaclityById($locationId);
        if (empty($data)) {
            return false;
        }
        return true;
    }

    /**
     * add tags service
     *
     * @param array $data
     * @return boolean
     */
    function addTags(array $data): bool
    {
        foreach ($data['tags'] as $tagName) {
            $tagsData = $this->facilityModel->fetchTagFromName($tagName);
            if (empty($tagsData)) {
                $tagId = $this->facilityModel->tagInsertAndGetId($tagName);
            } else {
                $tagId = $tagsData['id'];
            }
            $checkLink = $this->facilityModel->findAlreadyAssignTags($tagId, $data['facilityId']);
            if (empty($checkLink)) {
                $this->facilityModel->addRelationFacilityAndTag($tagId, $data['facilityId']);
            }
        }

        return true;
    }

    /**
     * get Facility Data
     *
     *
     * @return boolean
     */
    function getFacillitiedData($facilityId = null, int | string | null $q = null): array
    {
        $result = $this->facilityModel->getFacilityData($facilityId, $q);
        foreach ($result as $row) {
            $id = $row['facility_id'];
            if (!isset($facilities[$id])) {
                $facilities[$id] = [
                    'id' => $id,
                    'name' => $row['facility_name'],
                    'location' => [
                        'country_code' => $row['location_country_code'],
                        'city' => $row['location_city'],
                        'zip_code' => $row['location_zip_code'],
                        'address' => $row['location_address'],
                        'phone' => $row['location_phone_no'],
                    ],
                    'user' => [
                        'name' => $row['user_full_name'],
                        'email' => $row['user_email'],
                        'user_name' => $row['user_name'],
                        ''
                    ],
                    'tags' => []
                ];
            }

            if (!empty($row['tag_id'])) {
                $facilities[$id]['tags'][] = [
                    'id' => $row['tag_id'],
                    'name' => $row['tag_name']
                ];
            }
        }
        if (!empty($facilities)) {
            $facilities = array_values($facilities);
        } else {
            $facilities = [];
        }

        return $facilities;
    }

    /**
     * update facility and its tags
     *
     * @param array $data
     * @return boolval
     */
    function updateFacilityAndTags(array $data): bool
    {
        $result = $this->facilityModel->updateFacilityAndTags($data);
        return true;
    }

    /**
     * Service for delete facility and its tags
     *
     * @param integer $facilityId
     * @return boolean
     */
    function delete(int $facilityId): bool
    {
        $delete = $this->facilityModel->deleteFacilityAndItsTags($facilityId);
        return $delete;
    }
}

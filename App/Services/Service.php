<?php

namespace App\Services;

use App\Models\ApiModel;

class Service
{
    private $apiModel;

    function __construct()
    {
        $this->apiModel = new ApiModel();
    }

    /**
     * To check field is unique
     *
     * @param string $field
     * @param string $value
     * @return boolean
     */
    // function isFieldValueUnique(string $field, string $value): bool
    // {
    //     $count = $this->apiModel->checkUniqueField($field, $value);
    //     if ($count == 0) {
    //         $unique = true;
    //     } else {
    //         $unique = false;
    //     }
    //     return $unique;
    // }

    /**
     * create User Service 
     *
     * @param array $data
     * @return boolean
     */
    function createUser(array $data): bool
    {
        $insertData = $this->apiModel->createUser($data);
        return true;
    }

    /**
     * Login Service
     *
     * @param array $data
     * @return array
     */
    function login(array $data): array
    {
        $result  = $this->apiModel->login($data);
        return $result;
    }
}

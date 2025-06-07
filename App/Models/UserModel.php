<?php

namespace App\Models;

use App\Plugins\Di\Injectable;
use Exception;

class UserModel extends Injectable
{

    private $userTable;
    private $locationTable;

    function __construct()
    {
        $this->userTable = 'users';
    }

    /**
     * check is user id available
     *
     * @param integer $userId
     * @return array
     */
    function findUserById(int $userId): array
    {   
        $query = "SELECT *  FROM " . $this->userTable . " WHERE id = :id LIMIT 1";
        $bind = [
            ':id' => $userId,
        ];
        $dataResult = $this->db->executeQueryFetchSingleData($query, $bind);
        return $dataResult;
    }
}

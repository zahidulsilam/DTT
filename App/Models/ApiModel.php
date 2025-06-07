<?php

namespace App\Models;

use App\Plugins\Di\Injectable;
use App\Plugins\Http\Response as Status;
use Exception;

class ApiModel extends Injectable
{

    private $userTable;
    private $LocationTable;

    function __construct()
    {
        $this->userTable = 'users';
        $this->LocationTable = 'locations';
    }

    public function result()
    {
        // Respond with 200 (OK):
        $data = $this->db->executeQueryFetchData("Select * from test");
        return $data;
    }

    /**
     * Model function used to check unique Field.
     * @return int
     */

    function checkUniqueField(string $field, string $value): int
    {
        $data = $this->db->executeQueryFetchSingleData("SELECT COUNT(*) as count_user FROM $this->userTable WHERE $field = '" . $value . "'");
        return $data['count_user'];
    }

    /**
     * Model function insert User In DB.
     * @return bool
     */

    function createUser(array $data): int
    {
        $sql = 'INSERT INTO ' . $this->userTable . '(name, email,user_name,password) VALUES (:name, :email , :user_name, :password)';
        $data = [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':user_name' => $data['user_name'],
            ':password' => md5($data['password']),
        ];
        try {
            $query =  $this->db->executeQuery($sql, $data);
            $insertId = $this->db->getLastInsertedId();
            return $insertId;
        } catch (Exception $e) {
            (new Status\BadRequest(['message' => $e->getMessage(), 'data' => []]))->send();
            return 0;
        }
        return 0;
    }

    /**
     * Model function update Token.
     * @return bool
     */

    function updateToken(int $userId, string $token): bool
    {

        $sql = 'UPDATE ' . $this->userTable . ' SET token = :token WHERE id = :id';
        $data = [
            ':id' => $userId,
            ':token' => $token,
        ];
        try {
            $query =  $this->db->executeQuery($sql, $data);
            return $query;
        } catch (Exception $e) {
            (new Status\BadRequest(['message' => $e->getMessage(), 'data' => []]))->send();
            return 0;
        }
        return 0;
    }

    /**
     * Model function uer login.
     * @return array
     */

    function login(array $data): array
    {

        $query = "SELECT *  FROM " . $this->userTable . " WHERE user_name = :user_name and password = :password LIMIT 1";
        $bind = [
            ':user_name' => $data['user_name'],
            ':password' => md5($data['password']),
        ];
        $dataResult = $this->db->executeQueryFetchSingleData($query, $bind);
        return $dataResult;
    }
}

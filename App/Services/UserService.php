<?php

namespace App\Services;

use App\Models\FacilityModel;
use App\Models\UserModel;

class UserService
{
    private $facilityModel;
    private $userModel;

    function __construct()
    {
        $this->facilityModel = new FacilityModel();
        $this->userModel = new UserModel();
    }

    /**
     * Undocumented function
     *
     * @param int $data
     * @return bool
     */
    function checkUserExist(int $userId): bool
    {
        $data = $this->userModel->findUserById($userId);
        if (empty($data)) {
            return false;
        }
        return true;
    }
}

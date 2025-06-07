<?php

namespace App\Controllers;

use App\Plugins\Http\Response as Status;
use App\Services\FacilityService;
use App\Services\Service;
use App\Services\TokeService;
use App\Services\LocationService;
use App\Services\UserService;
use Exception;

class ApiController extends BaseController
{
    private $postData;
    private $service;
    private $tokenServce;
    private $locationService;
    private $facilityService;
    private $userService;

    function __construct()
    {
        $this->service = new Service();
        $this->tokenServce = new TokeService();
        $this->locationService = new LocationService();
        $this->facilityService = new FacilityService();
        $this->userService = new UserService();
    }

    /**
     * Controller function to register a user and return token.
     * @return string
     */
    function signUp(): string
    {
        $this->postData = $_POST;
        $requiredArray = ['name', 'email', 'user_name', 'password'];
        checkRequiredFields($requiredArray, $this->postData);
        $email = $this->postData['email'];
        $user_name = $this->postData['user_name'];
        CheckEmailIsValid($email);
        isFieldValueUnique('email', $email, 'Email');
        isFieldValueUnique('user_name', $user_name, 'Username Address');
        try {
            $insertUserId = $this->service->createUser($this->postData);
            if ($insertUserId) {
                (new Status\Ok([['message' => 'User Successfully added now you can login to use your username and password', 'data' => []]]))->send();
            } else {
                throw  new Exception('Some Error Please Contact Webmaster');
            }
        } catch (Exception $e) {
            (new Status\InternalServerError([['message' => $e->getMessage(), 'data' => []]]))->send();
            return '';
        }

        return '';
    }

    /**
     * Controller function used to user login.
     * @return string
     */
    public function login(): string
    {
        $this->postData = $_POST;
        $requiredArray = ['user_name', 'password'];
        checkRequiredFields($requiredArray, $this->postData);
        $data = $this->service->login($this->postData);
        try {
            if (!empty($data)) {
                $id = $data['id'];
                $token = $this->tokenServce->generateToken($id);
                $allUserData = $this->service->login($this->postData);
                unset($allUserData['password']);
                (new Status\Ok([['message' => 'Success', 'data' => [['data' => $allUserData]]]]))->send();
            } else {
                throw  new Exception('Username and password is wrong');
            }
        } catch (Exception $e) {
            (new Status\Unauthorized([['message' => $e->getMessage(), 'data' => []]]))->send();
            return '';
        }
        return '';
    }

    /**
     * Controller function used to add.
     * @return string
     */
    public function addLocation(): string
    {
        $this->tokenServce->verifyToken();
        $this->postData = $_POST;
        $requiredArray = ['city', 'address', 'zip_code', 'phone_no', 'country_code'];
        checkRequiredFields($requiredArray, $this->postData);
        $numericField = ['country_code'];
        checkNumericFields($numericField, $this->postData);
        try {
            $insertedData = $this->locationService->addLocation($this->postData);
            (new Status\Ok(['message' => 'Location successfully added', 'data' => [['location' => $insertedData]]]))->send();
        } catch (Exception $e) {
            (new Status\InternalServerError(['message' => $e->getMessage(), 'data' => [['location' => $insertedData]]]))->send();
        }
        return '';
    }

    /**
     * Controller function add facility.
     * @return string
     */
    public function addFacility(): string
    {
        $this->tokenServce->verifyToken();
        $this->postData = $_POST;
        $requiredArray = ['name', 'location_id', 'user_id'];
        checkRequiredFields($requiredArray, $this->postData);
        $numericField = ['location_id', 'user_id'];
        checkNumericFields($numericField, $this->postData);
        try {
            $insertedData = $this->locationService->checkLocationExist($this->postData['location_id']);
            if (!$insertedData) {
                throw  new Exception('Location Id not found');
            }
            $userData = $this->userService->checkUserExist($this->postData['user_id']);
            if (!$userData) {
                throw  new Exception('User Id not found');
            }
            $insertedData = $this->facilityService->addFacility($this->postData);
            (new Status\Ok([['message' => 'Facility successfully added', 'data' => [['facility' => $insertedData]]]]))->send();
            return '';
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            return '';
        }
    }

    /**
     * add Tags Of a Facility
     *
     * @return string
     */
    function addTagsOfFacilityByID(): string
    {
        $this->tokenServce->verifyToken();
        $this->postData = $_POST;
        $requiredArray = ['facilityId', 'tags'];
        checkRequiredFields($requiredArray, $this->postData);
        $numericField = ['facilityId'];
        checkNumericFields($numericField, $this->postData);
        validateAndCleanTags($this->postData['tags']);
        requiedTagsField($this->postData['tags']);
        try {
            $insertedData = $this->facilityService->checkFacilityExist($this->postData['facilityId']);
            if (!$insertedData) {
                throw  new Exception('Facility Id not found');
            }
            $insertTags = $this->facilityService->addTags($this->postData);
            if ($insertTags) {
                (new Status\Ok([['message' => 'Tags are Successfully addes', 'data' => []]]))->send();
                return '';
            }
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            return '';
        }
        return '';
    }

    /**
     * get data of all facilities and it location and tags
     *
     * @return string
     */
    function getFacillitiedData(): string
    {
        $this->tokenServce->verifyToken();
        if (isset($_GET['facilityId']) && $_GET['facilityId']) {
            $facilityId = $_GET['facilityId'];
        } else {
            $facilityId = null;
        }
        try {
            $data = $this->facilityService->getFacillitiedData($facilityId);
            if (empty($data)) {
                throw  new Exception('Data not Found');
            }
            (new Status\Ok([['message' =>  'data Successfully retrieved', 'data' => $data]]))->send();
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            return '';
        }
        return '';
    }

    /**
     * update facility and its tag
     *
     * @return string
     */
    function updateFacilityAndTags(): string
    {
        $this->tokenServce->verifyToken();
        $this->postData = $_POST;
        $requiredArray = ['facilityId'];
        if (isset($this->postData['name'])) {
            array_push($requiredArray, 'name');
        }
        if (isset($this->postData['location_id'])) {
            array_push($requiredArray, 'location_id');
            $numericField = ['location_id'];
            checkNumericFields($numericField, $this->postData);
            try {
                $insertedData = $this->locationService->checkLocationExist($this->postData['location_id']);
                if (!$insertedData) {
                    throw  new Exception('Location Id not found');
                }
                $this->facilityService->updateFacilityAndTags($this->postData);
            } catch (Exception $e) {
                (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
                exit();
            }
        }
        if (isset($this->postData['tags'])) {
            validateAndCleanTags($this->postData['tags']);
            requiedTagsField($this->postData['tags']);
        }
        checkRequiredFields($requiredArray, $this->postData);
        $facilityId = ['facilityId'];
        checkNumericFields($facilityId, $this->postData);
        try {
            $insertedData = $this->facilityService->checkFacilityExist($this->postData['facilityId']);
            if (!$insertedData) {
                throw  new Exception('Facility Id not found');
            }
            $this->facilityService->updateFacilityAndTags($this->postData);
            $dataFacility = $this->facilityService->getFacillitiedData($this->postData['facilityId']);
            (new Status\Ok([['message' =>  'Facility Successfully Updated', 'data' => $dataFacility]]))->send();
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            exit();
        }
        return '';
    }

    /**
     * filter search implement
     *
     * @return string
     */
    function getFilterFacilites(): string
    {
        $this->tokenServce->verifyToken();
        if (isset($_GET['q']) && $_GET['q'] != '') {
            $q = $_GET['q'];
        } else {
            $q = null;
        }
        try {
            $data = $this->facilityService->getFacillitiedData(null, $q);
            if (empty($data)) {
                throw  new Exception('Data not Found');
            }
            (new Status\Ok([['message' =>  'data Successfully retrieved', 'data' => $data]]))->send();
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            return '';
        }
        return '';
    }

    /**
     * delete facility and its tags relationship
     *
     * @return string
     */
    function deleteFacilite(): string
    {
        $this->tokenServce->verifyToken();
        $this->postData = $_POST;
        $requiredArray = ['facilityId'];
        checkRequiredFields($requiredArray, $this->postData);
        $numericField = ['facilityId'];
        checkNumericFields($numericField, $this->postData);
        try {
            $checkFacilityExist = $this->facilityService->checkFacilityExist($this->postData['facilityId']);
            if (!$checkFacilityExist) {
                throw  new Exception('Facility Id not found');
            }
            if ($this->facilityService->delete($this->postData['facilityId'])) {
                (new Status\Ok([['message' =>  'Facility Successfully deleted', 'data' => []]]))->send();
            }
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            return '';
        }
        return '';
    }
}

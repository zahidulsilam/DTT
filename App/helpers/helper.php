<?php

use App\Plugins\Http\Response as Status;
use App\Models\ApiModel;

if (!function_exists('dd')) {
    function dd($arr): void
    {
        echo '<pre>';
        print_r($arr);
        exit();
    }
}

if (!function_exists('checkRequiredFields')) {
    function checkRequiredFields(array $requiredFields, array $requestData): void
    {
        try {
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($requestData[$field])) {
                    $missingFields[] = $field;
                }
            }
            if (!empty($missingFields)) {
                throw new Exception('Missing required fields: ' . implode(', ', $missingFields));
            }
            $error = '';
        } catch (Exception $e) {
            $error = (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            echo $error;
            exit();
        }
    }
}

if (!function_exists('CheckEmailIsValid')) {
    function CheckEmailIsValid($email): void
    {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email is invalid!');
            }
            $error = '';
        } catch (Exception $e) {
            echo (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            exit();
        }
    }
}

if (!function_exists('checkNumericFields')) {
    function checkNumericFields(array $numericField, array $requestData): void
    {
        try {
            $missingFields = [];
            foreach ($numericField as $field) {
                if (!is_numeric($requestData[$field])) {
                    $missingFields[] = $field;
                }
            }
            if (!empty($missingFields)) {
                throw new Exception('Enter Integer value: ' . implode(', ', $missingFields));
            }
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            exit;
        }
    }
}
if (!function_exists('validateAndCleanTags')) {
    function validateAndCleanTags($tags): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $error = [];
            $tags = $tags ?? null;
            if (!is_array($tags)) {
                throw new Exception('Tags are should be in  array');
                // $error = ['error' => 'Tags are should be in  array'];
            }
        } catch (Exception $e) {
            (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            exit;
        }
    }
}

if (!function_exists('isFieldValueUnique')) {
    function isFieldValueUnique(string $field, string $value, string $messageFieldName): void
    {
        try {
            $apiModel = new ApiModel();
            $count = $apiModel->checkUniqueField($field, $value);
            if ($count != 0) {
                throw new Exception($messageFieldName . ' already exist');
            }
        } catch (Exception $e) {
            echo (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            exit();
        }
    }
}

if (!function_exists('requiedTagsField')) {
    function requiedTagsField(array $tags): void
    {
        try {
            foreach ($tags as $tag) {
                if ($tag == '') {
                    throw  new Exception('Tags is required field');
                    break;
                }
            }
        } catch (Exception $e) {
            echo (new Status\BadRequest([['message' => $e->getMessage(), 'data' => []]]))->send();
            exit();
        }
    }
}
if (!function_exists('debugPDOQuery')) {
    function debugPDOQuery(string $query, array $params): string
    {
        foreach ($params as $key => $value) {
            // Ensure key is prefixed with ':' for replacement
            $key = strpos($key, ':') === 0 ? $key : ':' . $key;
            // Quote non-numeric values
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            // Replace only the first instance of the placeholder
            $query = preg_replace('/' . preg_quote($key, '/') . '/', $value, $query, 1);
        }
        return $query;
    }
}

<?php
// cannot access helper directly

/** @var Bramus\Router\Router $router */

$router->get('/', App\Controllers\ApiController::class . '@signUp');
$router->post('/register', App\Controllers\ApiController::class . '@signUp');
$router->post('/login', App\Controllers\ApiController::class . '@login');
$router->post('/add-location', App\Controllers\ApiController::class . '@addLocation');
$router->post('/add-facility', App\Controllers\ApiController::class . '@addFacility');
$router->post('/add-facility-tags', App\Controllers\ApiController::class . '@addTagsOfFacilityByID');
$router->get('/get-facility-data.*', App\Controllers\ApiController::class . '@getFacillitiedData');
$router->post('/update-facility-tags', App\Controllers\ApiController::class . '@updateFacilityAndTags');
$router->get('/filter.*', App\Controllers\ApiController::class . '@getFilterFacilites');
$router->post('/delete-facility', App\Controllers\ApiController::class . '@deleteFacilite');

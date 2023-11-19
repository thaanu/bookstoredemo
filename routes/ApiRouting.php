<?php 
use Heliumframework\Router;


// API ROUTES
Heliumframework\Router::get('/api', 'ApiController@index');

// Test Route //To-do clean as API end point
Heliumframework\Router::post('/api/devices/posting', 'DevicesApiController@postingData');
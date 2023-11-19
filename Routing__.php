<?php 

use Heliumframework\Router;

Router::get('/', 'SessionController@index');
Router::get('/login', 'SessionController@index');
Router::post('/login', 'SessionController@processLogin');
Router::get('/logout', 'SessionController@logout');

// Dashboard
Router::get('/dashboard', 'DashboardController@index');

// Audits
Router::get('/audits', 'AuditController@index');
Router::get('/audits/fetch', 'AuditController@fetch');


// Applications
Router::get('/applications', 'ApplicationsController@index');
Router::get('/applications/create', 'ApplicationsController@create');
Router::post('/applications/create', 'ApplicationsController@store');
Router::post('/applications/revoke', 'ApplicationsController@destroy');


// Users
Router::get('/users', 'UsersController@index');
Router::get('/users/active', 'UsersController@activeUsers');
Router::get('/users/in-active', 'UsersController@inActiveUsers');
Router::get('/users/create', 'UsersController@create');
Router::post('/users/create', 'UsersController@store');
Router::get('/users/update', 'UsersController@update');
Router::post('/users/update', 'UsersController@patch');


// Akhil API test routes
Router::get('/akhil-api', 'AkhilApiController@index');
Router::get('/akhil-api/loadview', 'AkhilApiController@loadView');


// Include API routing
include __DIR__ . '/ApiRouting.php';
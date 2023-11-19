<?php 

use Heliumframework\Router;

/**
 * Control Panel Routes
 */

Router::get('/cp', 'SessionController@index');

Router::get('/cp/login', 'SessionController@index');
Router::post('/cp/login', 'SessionController@processLogin');
Router::get('/cp/logout', 'SessionController@logout');

// Dashboard
Router::get('/cp/dashboard', 'DashboardController@index');
Router::post('/cp/dashboard/data', 'DashboardController@fetchData');

// Documentation
Router::get('/cp/documentation', 'DocumentationController@index');
Router::post('/cp/documentation/topic', 'DocumentationController@topic');

// Profile
Router::get('/cp/profile', 'ProfileController@index');
Router::post('/cp/profile/ajax', 'ProfileController@ajaxHandler');

// Audits
Router::get('/cp/audits', 'AuditController@index');
Router::get('/cp/audits/fetch', 'AuditController@fetch');

// Applications
Router::get('/cp/api-clients', 'ApplicationsController@index');
Router::get('/cp/api-clients/create', 'ApplicationsController@create');
Router::post('/cp/api-clients/create', 'ApplicationsController@store');
Router::post('/cp/api-clients/revoke', 'ApplicationsController@destroy');

// Books
Router::get('/cp/books', 'BooksController@index');
Router::post('/cp/books/ajax', 'BooksController@ajaxHandler');

// API Documentation
Router::get('/cp/api-documentation', 'ApiDocumentationController@index');

// Groups
Router::get('/cp/groups', 'GroupsController@index');
Router::post('/cp/groups/ajax', 'GroupsController@ajaxHandler');

// Users
Router::get('/cp/users', 'UsersController@index');
Router::post('/cp/users/ajax', 'UsersController@ajaxHandler');
Router::get('/cp/users/create', 'UsersController@create');
Router::post('/cp/users/create', 'UsersController@store');
Router::get('/cp/users/update', 'UsersController@update');
Router::post('/cp/users/update', 'UsersController@put');

// Cronjobs
Router::get('/cp/cronjobs', 'CronjobsController@index');
Router::post('/cp/cronjobs/ajax', 'CronjobsController@ajaxHandler');
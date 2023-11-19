<?php 
use Heliumframework\Router;


// API ROUTES
Heliumframework\Router::get('/api', 'ApiController@index');

// Books
Heliumframework\Router::get('/api/books/all', 'BooksApiController@all');
Heliumframework\Router::get('/api/books/list', 'BooksApiController@list');
Heliumframework\Router::post('/api/books/create', 'BooksApiController@store');
Heliumframework\Router::put('/api/books/update', 'BooksApiController@update');
Heliumframework\Router::delete('/api/books/remove', 'BooksApiController@delete');
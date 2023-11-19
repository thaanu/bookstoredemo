<?php 
use Heliumframework\Router;

// Session Authentication
Router::post('/api/guest-login', 'SessionApiController@normalLogin'); // Normal login with username and password
Router::post('/api/guest-login-efass', 'SessionApiController@efassLogin'); // Login using eFass method
Router::post('/api/guest-login-otp', 'SessionApiController@efassLogin'); // Login using OTP method

// This is one time login, after the first successful login this method will expire
Router::post('/api/guest-login-qr', 'SessionApiController@qrCodeLogin'); // Login using QR method


// Guest
Router::post('/api/create-guest', 'GuestApiController@createGuest');
Router::post('/api/validate', 'GuestApiController@validate');

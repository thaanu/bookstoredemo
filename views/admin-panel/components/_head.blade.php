<!DOCTYPE html>
<html lang="en" data-topbar-color="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">

        <title>@yield('page-title', 'Welcome') | {{ env('APP_NAME') }}</title>
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ assets('tth/favicon.ico') }}">

        {{-- JQuery Toast Css --}}
        <link href="{{ assets('assets/libs/jquery-toast-plugin/jquery.toast.min.css') }}" rel="stylesheet" type="text/css" />

		<!-- Theme Config Js -->
		<script src="{{ assets('assets/js/head.js') }}"></script>

		<!-- Bootstrap css -->
		<link href="{{ assets('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

		<!-- App css -->
		<link href="{{ assets('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

		<!-- Icons css -->
		<link href="{{ assets('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

		<!-- Switchery -->
        <link href="{{ assets('assets/libs/mohithg-switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Selectize -->
        <link href="{{ assets('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />

        <!-- Select 2 -->
        <link href="{{ assets('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

        {{-- Tour --}}
        <link href="{{ assets('assets/libs/hopscotch/css/hopscotch.min.css') }}" rel="stylesheet" type="text/css" />

        {{-- Switchry --}}
        <link href="{{ assets('assets/libs/mohithg-switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />

        {{-- Goto --}}
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

        @include('hf.heliumframework-css')
        
    </head>
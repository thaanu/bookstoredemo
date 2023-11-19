@extends('admin-panel.layout')
@section('page-title', 'Users')
@section('page-content')

<div class="py-2">
    <a href="{{ cpanelPermalink('users/create') }}" id="create-user-btn" class="btn btn-primary">New User</a>
</div>

<div id="content"><center>Loading...</center></div>

@include('admin-panel.users.parts._offcanvas')

@include('admin-panel.users.script')

@endsection
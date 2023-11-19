@extends('admin-panel.layout')
@section('page-title', 'Applications')
@section('page-content')


<div class="card-box">
    <h4 class="m-t-0 m-b-20 header-title"><b>Create New Application</b></h4>
    
    <form class="form-horizontal HFForm" action="{{ permalink('applications.create') }}" method="post" data-na="success-then-redirect-to-next-screen" data-ns="{{ permalink('applications') }}" >                              
        <div class="form-group">
            <label class="col-md-2 control-label" for="app_name">Application Name</label>
            <div class="col-md-10">
                <input type="text" class="form-control" name="app_name" id="app_name">
            </div>
        </div>
        {{ csrf() }}
        <div class="form-group m-b-0">
            <div class="col-sm-offset-2 col-sm-9">
              <button type="submit" class="btn btn-success waves-effect waves-light">Create</button>
            </div>
        </div>
    </form>
        
</div>


@endsection
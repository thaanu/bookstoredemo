@extends('admin-panel.layout')
@section('page-title', 'API Clients')
@section('page-content')


<div class="card-box">
    <h4 class="m-t-0 m-b-20 header-title"><b>Create New Client</b></h4>
    
    <form class="form-horizontal HFForm" action="{{ cpanelPermalink('api-clients/create') }}" method="post" data-na="success-then-redirect-to-next-screen" data-ns="{{ cpanelPermalink('api-clients') }}" >                              
        <div class="mb-3">
            <label class="col-md-2 control-label" for="client_name">Client Name</label>
            <div class="col-md-10">
                <input type="text" class="form-control" name="client_name" id="client_name">
            </div>
        </div>
        {{ csrf() }}
        <div class="mb-3">
            <div class="col-sm-offset-2 col-sm-9">
              <button type="submit" class="btn btn-success waves-effect waves-light">Create</button>
            </div>
        </div>
    </form>
        
</div>


@endsection
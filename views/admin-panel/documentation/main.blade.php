@extends('admin-panel.layout')
@section('page-title', 'Documentation')
@section('page-content')

<div class="row">
    <div class="col-lg-3">
        <h5>Application Documentation</h5>
        <div class="list-group mb-4">
            <a href="{{ cpanelPermalink('documentation/topic/setup') }}" class="list-group-item list-group-item-action"><i class="fas fa-puzzle-piece" style="margin-right: 10px;"></i> Setup</a>
        </div>
        <h5>API Documentation</h5>
        <div class="list-group">
            <a href="{{ cpanelPermalink('documentation/topic/authentication') }}" class="list-group-item list-group-item-action"><i class="fas fa-lock" style="margin-right: 10px;"></i> Authentication</a>
            <a href="{{ cpanelPermalink('documentation/topic/books') }}" class="list-group-item list-group-item-action"><i class="fas fa-book" style="margin-right: 10px;"></i> Books</a>
            <a href="{{ cpanelPermalink('documentation/topic/status-codes') }}" class="list-group-item list-group-item-action"><i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i> Status Codes</a>
        </div>
    </div>
    <div class="col-lg-9" id="pg-content">
        <center>Choose a topic</center>
    </div>
</div>

<script>
    let links = document.querySelectorAll('.list-group-item');
    if ( links.length > 0 ) {
        for ( let i = 0; i < links.length; i++ ) {
            let btn = links[i];
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                hfPostRequest(e.target.href).then(response => {
                    if (response.status == 200) {
                        document.querySelector('#pg-content').innerHTML = response.view;
                    } else {
                        hf_error_toast(response.error);
                    }
                });
            });
        }
    }
</script>

@endsection
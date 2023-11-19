@extends('admin-panel.layout')
@section('page-title', 'Audit')
@section('page-content')

<div id="log-container"></div>

<script>
    let logContainer = document.querySelector('#log-container');
    let fetchURL = `/cpanel/audits/fetch/1/1000`;

    function fetchLog()
    {
        fetch(fetchURL)
        .then((response) => {
            if ( response.status == 404 ) {
                throw new Error("Requested endpoint was not found", {cause: response});
            }
            return response.text();
        })
        .then((html) => {
            logContainer.innerHTML = html;
        })
        .catch((error) => {
            logContainer.innerHTML = 'Refresh page';
            hf_error_toast(error);
        });
    }

    document.addEventListener('DOMContentLoaded', fetchLog, false);

</script>

@endsection
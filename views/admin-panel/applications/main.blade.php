@extends('admin-panel.layout')
@section('page-title', 'API Clients')
@section('page-content')

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ cpanelPermalink('api-clients/create') }}">New Client</a>
    </div>

    <div class="card-box">
        <h4 class="m-t-0 header-title"><b>Active Clients</b></h4>

        @if ($applications['count'] == 0)
            <p class="text-muted font-13">No Clients</p>
        @else
            <p class="text-muted font-13">{{ $applications['count'] }} Clients</p>

            <div class="row">
                @foreach ($applications['data'] as $i => $app)
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h3>{{ $app['client_name'] }}</h3>
                                <p><code>{{ $app['client_key'] }}</code></p>
                                <p>
                                    <button data-url="{{ cpanelPermalink('api-clients/revoke/' . $app['client_id']) }}"
                                        class="revoke-btn btn btn-primary" data-title="Revoke Client Access?"
                                        data-text="You cannot undo this action." data-type="warning"
                                        data-ns="{{ cpanelPermalink('api-clients') }}">Revoke</button>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

    </div>

    <script>
        let revokeBtns = document.querySelectorAll('.revoke-btn');
        if (revokeBtns.length > 0) {
            for (let i = 0; i < revokeBtns.length; i++) {
                let btn = revokeBtns[i];
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    let dataset = e.target.dataset;

                    Swal.fire({
                        title: dataset.title,
                        text: dataset.text,
                        type: dataset.type,
                        icon: dataset.type,
                        dangerMode: true,
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: "Yes, revoke",
                        closeOnConfirm: false,
                    }).then( ( result ) => {
                        if( result.isConfirmed ) {
                            hfPostRequest(e.target.dataset.url).then(response => {
                                if ( response.status == 200 ) {
                                    hf_success_toast(response.textMessage);
                                    window.location.reload();
                                } else {
                                    hf_error_toast(response.error);
                                }
                            });
                        }
                    });

                });
            }
        }
    </script>

@endsection

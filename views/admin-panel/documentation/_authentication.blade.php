<div class="card card-body">
    <h5 class="card-title m-0"><i class="fas fa-lock" style="margin-right: 10px;"></i>Authentication</h5>
    <hr>
    <div class="card-text">
        
        <p>To connect to the API, the client must send an authentication key in the header of the request.</p>
        <p><a href="{{ cpanelPermalink('api-clients') }}" class="btn btn-primary">Create API Client</a></p>
        <p>Once the client is registered, a random key will be generated.</p>
        <figure>
            <img src="{{ assets('res/postman.png') }}" alt="postman.png" style="width: 340px;">
            <br>
            <small>Example API Client</small>
        </figure>
        <p>In the request header, set the following parameters.</p>
        <table class="table">
            <tr>
                <th>Auth-Key</th>
                <td><code><< Autehentication Code >></code></td>
            </tr>
            <tr>
                <th>Content-Type</th>
                <td>application/json</td>
            </tr>
        </table>
    </div>
</div>
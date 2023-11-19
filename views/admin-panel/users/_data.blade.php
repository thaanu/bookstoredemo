
    <div class="card-box">
        {{-- <h4 class="m-t-0 header-title"><b>{{ $title }}</b></h4> --}}
        
        @if ( $users['count'] == 0 )
            <p class="text-muted font-13">No Users</p>
        @else
            {{-- <p class="text-muted font-13">{{ titleWithCount('User', $users['count']) }}</p> --}}
        
            <div class="row">
                @foreach ( $users['data'] as $user)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4><i class="fas fa-user-circle"></i> {{ $user['full_name'] }}</h4>
                                <p>{{ $user['email'] }}</p>
                                <p>{{ $user['group_name'] }}</p>
                                <p>
                                    <a class="btn btn-primary update-btn" href="{{ cpanelPermalink('users/update/'.$user['user_id']) }}">Update</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        @endif
            
    </div>

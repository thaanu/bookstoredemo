@if ( $groups['count'] == 0 )
    <p class="text-center">No groups</p>
    <p class="text-center">
        <button type="button" id="add-group-btn" class="btn btn-primary" >New Group</button>
    </p>
@else
    
    <div class="py-2 mb-2">
        <button type="button" id="add-group-btn" class="btn btn-primary" >New Group</button>
    </div>

    <div class="row">
        @foreach ($groups['data'] as $group)
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $group['group_name'] }}</h4>
                        <p>
                            <button data-url="{{ cpanelPermalink('groups/ajax/show-group/'.$group['ug_id']) }}" class="edit-btn btn btn-sm btn-light" style="margin-right: 10px;">Edit</button> 
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endif
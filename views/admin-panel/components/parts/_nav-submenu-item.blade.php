{{-- {{ ( isActiveMenu($item->url) ? 'menuitem-active' : '' ) }} --}}
@if ( \Heliumframework\Auth::hasPermission($item->permission) )
    <li class="menu-item">
        <a href="#{{ str_replace(' ', '', $item->label) }}" data-bs-toggle="collapse" class="menu-link">
            <span class="menu-icon"><i data-feather="{{ $item->icon }}"></i></span>
            <span class="menu-text"> {{ $item->label }} </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="{{ str_replace(' ', '', $item->label) }}">
            <ul class="sub-menu">
                @foreach ( $item->sub as $subitem)
                    <li class="menu-item">
                        <a class="menu-link" href="{{ \Heliumframework\Permalink::cpanel($subitem['url']) }}">
                            <span class="menu-text">{{ $subitem['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </li>
@endif
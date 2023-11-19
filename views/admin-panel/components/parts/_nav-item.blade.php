<li class="menu-item"  id="nav-{{ strtolower(str_replace(' ', '-', $item->label)) }}">
    <a href="{{ \Heliumframework\Permalink::cpanel($item->url) }}" class="menu-link">
        <span class="menu-icon"><i data-feather="{{ $item->icon }}"></i></span>
        <span class="menu-text"> {{ $item->label }} </span>
    </a>
</li>
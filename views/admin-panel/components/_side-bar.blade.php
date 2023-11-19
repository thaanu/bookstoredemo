<?php 
    $__sidebarNav = (object) json_decode(file_get_contents(dirname(__DIR__).'/resources/sidebar-navigation.json'), true);
?>


<div class="app-menu">  

    <!-- Brand Logo -->
    <div class="logo-box">
        <!-- Brand Logo Light -->
        <a href="{{ \Heliumframework\Permalink::cpanel('') }}" class="logo-light">
            <img src="{{ assets('logos/custom_aside_dark.png') }}" alt="logo" class="logo-lg">
            <img src="{{ assets('logos/custom_aside_dark.png') }}" alt="small logo" class="logo-sm">
        </a>

        <!-- Brand Logo Dark -->
        <a href="{{ \Heliumframework\Permalink::cpanel('') }}" class="logo-dark">
            <img src="{{ assets('logos/custom_aside.png') }}" alt="dark logo" class="logo-lg">
            <img src="{{ assets('logos/custom_aside.png') }}" alt="small logo" class="logo-sm">
        </a>
    </div>

    <!-- menu-left -->
    <div class="scrollbar">

        <!-- User box -->
        <div class="user-box text-center">
            <img src="assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block" data-bs-toggle="dropdown">Geneva Kennedy</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted mb-0">Admin Head</p>
        </div>

        <!--- Menu -->
        <ul class="menu">

            @foreach ($__sidebarNav as $title => $menu)
                @if ( hasMenuPermission( $menu ) )
                    <li class="menu-title">{{ ucwords($title) }}</li>
                    @foreach ( $menu as $item )
                        @if ( empty($item['sub']) )
                            @if ( empty($item['permission']) )
                                @include('admin-panel.components.parts._nav-item', ['item' => (object) $item])
                            @else 
                                @if ( \Heliumframework\Auth::hasPermission($item['permission']) )
                                    @include('admin-panel.components.parts._nav-item', ['item' => (object) $item])
                                @endif
                            @endif
                        @else
                            @if ( empty($item['permission']) )
                                @include('admin-panel.components.parts._nav-submenu-item', ['item' => (object) $item])
                            @else 
                                @if ( \Heliumframework\Auth::hasPermission($item['permission']) )
                                    @include('admin-panel.components.parts._nav-submenu-item', ['item' => (object) $item])
                                @endif
                            @endif
                            
                        @endif
                    @endforeach
                @endif
            @endforeach

        </ul>
        <!--- End Menu -->
        <div class="clearfix"></div>
    </div>
</div>
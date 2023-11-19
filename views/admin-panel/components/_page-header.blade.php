<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    @php $bcs = breadCrumb(); @endphp
                    @foreach ( $bcs as $i => $bc)
                        @if ( $i == count($bcs) - 1 )
                            <li class="breadcrumb-item active">{{ cleanupBreadcrumb($bc) }}</li>
                        @else
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ cleanupBreadcrumb($bc) }}</a></li>
                        @endif
                    @endforeach
                </ol>
            </div>
            <h4 class="page-title">@yield('page-title', 'page-title')</h4>
        </div>
    </div>
</div>
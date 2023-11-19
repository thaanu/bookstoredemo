<div class="widget-rounded-circle card">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="avatar-lg rounded-circle bg-soft-{{ $color }} border-{{ $color }} border">
                    <i class="{{ $icon }} font-22 avatar-title text-{{ $color }}"></i>
                </div>
            </div>
            <div class="col-6">
                <div class="text-end">
                    <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $value }}</span></h3>
                    <p class="text-muted mb-1 text-truncate">{{ $label }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
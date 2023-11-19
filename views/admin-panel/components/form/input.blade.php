
@if ( $type == 'email' )
<div class="hf-form-group form-floating mb-3">
    <input type="email" class="form-control" name="{{ $name }}" id="{{ $id }}" placeholder="" value="{{ ( empty($value) ? '' : $value ) }}" >
    <label for="{{ $id }}">{{ $label }}</label>
</div>
@endif

@if ( $type == 'text' )
<div class="hf-form-group form-floating mb-3">
    <input type="text" class="form-control" name="{{ $name }}" id="{{ $id }}" {{ (isset($readonly)? 'readonly' : '') }} placeholder="" value="{{ ( empty($value) ? '' : $value ) }}" >
    <label for="{{ $id }}">{{ $label }}</label>
</div>
@endif

@if ( $type == 'tel' )
<div class="hf-form-group form-floating mb-3">
    <input type="tel" class="form-control" name="{{ $name }}" id="{{ $id }}" placeholder="" value="{{ ( empty($value) ? '' : $value ) }}" >
    <label for="{{ $id }}">{{ $label }}</label>
</div>
@endif

@if ( $type == 'date' )
<div class="hf-form-group form-floating mb-3">
    <input type="date" class="form-control" name="{{ $name }}" id="{{ $id }}" placeholder="" value="{{ ( empty($value) ? '' : $value ) }}" >
    <label for="{{ $id }}">{{ $label }}</label>
</div>
@endif

@if ( $type == 'password' )
<div class="hf-form-group form-floating mb-3">
    <input type="password" class="form-control" name="{{ $name }}" id="{{ $id }}" placeholder="" value="{{ ( empty($value) ? '' : $value ) }}" >
    <label for="{{ $id }}">{{ $label }}</label>
</div>
@endif

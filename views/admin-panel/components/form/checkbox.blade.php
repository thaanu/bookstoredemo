<div class="hf-form-group mb-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" {{ (isset($checked) && $checked ? ' checked ' : '') }} id="{{ $id }}" name="{{ $name }}" value="yes" >
        <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    </div>
</div>
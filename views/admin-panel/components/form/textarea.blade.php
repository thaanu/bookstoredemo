<div class="hf-form-group form-floating mb-3">
    <textarea class="form-control" name="{{ $name }}" id="{{ $id }}" placeholder="" style="height: 100px">{{ (isset($value) ? $value : '') }}</textarea>
    <label for="{{ $id }}">{{ $label }}</label>
</div>
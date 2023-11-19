<div class="form-floating mb-3">
    <select class="form-select" name="{{ $name }}" id="{{ $id }}" aria-label="{{ $label }}">
        <option value="" selected>Select</option>
        @if ( ! empty($options) )
            @foreach ($options as $key => $kvalue)
                @if ( isset($value) )
                    <option {{ ( $key == $value ? ' selected ' : '' ) }} value="{{ $key }}">{{ $kvalue }}</option>
                @else
                    <option value="{{ $key }}">{{ $kvalue }}</option>
                @endif

            @endforeach
        @endif
    </select>
    <label for="{{ $id }}">{{ $label }}</label>
</div>
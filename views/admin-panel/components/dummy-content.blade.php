@for ($i = 0; $i < $rows; $i++)
    <p class="card-text placeholder-wave">
        @for ($j = 0; $j < $cols; $j++)
            <span class="placeholder col-{{ $cols * 2 }} }}"></span>
        @endfor
    </p>
@endfor
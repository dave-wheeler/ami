<details{!! $toplevel ? '' : ' open=""' !!}>
    <summary{!! $toplevel ? ' class="text-summary"' : '' !!}>
        {{ $summary }}
    </summary>
    <ul>
        @foreach($details as $k => $detail)
            <li>
                @if (is_array($detail))
                    @include('stats.showArray', ['summary' => $k, 'details' => $detail, 'toplevel' => false])
                @else
                    {{ $k }}: {{ $detail }}
                @endif
            </li>
        @endforeach
    </ul>
</details>

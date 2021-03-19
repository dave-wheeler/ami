<x-layout>
    <x-slot name="title">Compare Daily Usage by Date Ranges</x-slot>

    <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
        <div class="text-center">
            <h1>Daily Usage Comparison</h1>
            <h2>{{ $dates['start1'] }} to {{ $dates['end1'] }}</h2>
            <h4>Compared To</h4>
            <h2>{{ $dates['start2'] }} to {{ $dates['end2'] }}</h2>
        </div>

        @if (isset($errors))
            <div>
                @foreach($errors as $error)
                    {{ $error }}<br/>
                @endforeach
            </div>
        @endif

        @if (!empty($tTest))
            <div>
                <details>
                    <summary class="text-summary">Student's t-test</summary>
                    <ul>
                        @foreach($tTest as $k => $v)
                            <li>{{ $k }}: {{ $v }}</li>
                        @endforeach
                    </ul>
                </details>
            </div>
        @endif

        @if (!empty($zTest))
            <div>
                <details>
                    <summary class="text-summary">Z-test</summary>
                    <ul>
                        @foreach($zTest as $k => $v)
                            <li>{{ $k }}: {{ $v }}</li>
                        @endforeach
                    </ul>
                </details>
            </div>
        @endif
    </div>
</x-layout>

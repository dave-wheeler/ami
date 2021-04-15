<x-layout>
    <x-slot name="title">Statistics By Date Range ({{ $dateTimes['start'] }} to {{ $dateTimes['end'] }})</x-slot>

    <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
        <div class="text-center">
            <h2>{{ $dateTimes['start'] }} to {{ $dateTimes['end'] }}</h2>
            <p>Mean daylight each day: {{ $daylightMean }}</p>
        </div>

        @if (isset($errors))
            <div>
                @foreach($errors as $error)
                    {{ $error }}<br/>
                @endforeach
            </div>
        @endif

        @if (isset($stats))
            <div>
                @foreach($stats as $statName => $data)
                    @include('stats.showArray', ['summary' => $statName, 'details' => $data, 'toplevel' => true])
                @endforeach
            </div>
        @endif
    </div>
</x-layout>

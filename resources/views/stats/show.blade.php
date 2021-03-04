<x-layout>
    <x-slot name="title">Statistics By Date Range ({{ $startDateTime }} to {{ $endDateTime }})</x-slot>

    <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
        <div class="text-center">
            <h2>{{ $startDateTime }} to {{ $endDateTime }}</h2>
        </div>

        @if (isset($stats))
            @foreach($stats as $statName => $data)
                @include('stats.showArray', ['summary' => $statName, 'details' => $data, 'toplevel' => true])
            @endforeach
        @elseif (isset($error))
            <h3>Exception Occurred!</h3>
            {{ $error }}
        @endif
    </div>
</x-layout>

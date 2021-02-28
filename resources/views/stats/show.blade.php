<x-layout>
    <x-slot name="title">Statistics By Date Range ({{ $startDateTime }} to {{ $endDateTime }})</x-slot>

    <div class="ml-12">
        <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
            <div class="text-center">
                <h2>{{ $startDateTime }}<br/>to<br/>{{ $endDateTime }}</h2>
            </div>

            @if (isset($stats))
                @foreach($stats as $statName => $data)
                    <h3>{{ $statName }}</h3>
                    <ul>
                        <li>Number of Items (n): {{ $data['n'] }}</li>
                        <li>Min: {{ $data['min'] }}</li>
                        <li>Max: {{ $data['max'] }}</li>
                        <li>Mean: {{ $data['mean'] }}</li>
                        <li>Median: {{ $data['median'] }}</li>
                        <li>σ: {{ $data['sd'] }}</li>
                    </ul>
                @endforeach
            @elseif (isset($error))
                <h3>Exception Occurred!</h3>
                {{ $error }}
            @endif
        </div>
    </div>
</x-layout>

<x-layout>
    <x-slot name="title">Statistics - Date Range Selection</x-slot>

    @include('map.body')

    <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
        @php
            echo Form::open(['route' => 'stats.show']);

            echo Form::hidden('lat', 0);
            echo Form::hidden('lon', 0);

            echo 'Select the date/time range.<br/>';
            // TODO: Use a better date/time selector than native HTML controls
            $now = now();
            echo '<br/>' . Form::label('start', 'Start:') . '<br/>';
            echo Form::date('startDate', $now->format('Y-m-d'), ['class' => 'dark:input']) . '&nbsp;';
            echo Form::time('startTime', $now->format('H:i'), ['class' => 'dark:input']) . '<br/>';
            echo Form::label('end', 'End:') . '<br/>';
            echo Form::date('endDate', $now->format('Y-m-d'), ['class' => 'dark:input']) . '&nbsp;';
            echo Form::time('endTime', $now->format('H:i'), ['class' => 'dark:input']) . '<br/>';
            echo '<br/>' . Form::submit('Submit Query', ['class' => 'dark:input']);
            echo Form::close();
        @endphp
    </div>
</x-layout>

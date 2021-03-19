<x-layout>
    <x-slot name="title">Compare Daily Usage by Date Ranges</x-slot>
    <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
        @php
            $now = now();
            echo Form::open(['route' => 'compare.show']);
            // TODO: Use client-side geolocation and maps so entering lat/lon in a form isn't a user burden

            echo 'Enter your latitude and longitude:<br/>';
            echo '<div class="text-sm">N Latitude is positive, S is negative<br/>';
            echo 'E Longitude is positive, W is negative<br/></div>';
            echo '<br/>' . Form::label('lat', 'Latitude:') . '<br/>';
            echo Form::text('lat', '0', ['class' => 'dark:input']) . '<br/>';
            echo Form::label('lon', 'Longitude:') . '<br/>';
            echo Form::text('lon', '0', ['class' => 'dark:input']) . '<br/>';
            echo '<br/><hr/>';

            echo 'Select the first date range for comparison:<br/>';
            // TODO: Use a better date/time selector than native HTML controls
            echo '<br/>' . Form::label('start1', 'Start:') . '<br/>';
            echo Form::date('startDate1', $now->format('Y-m-d'), ['class' => 'dark:input']) . '<br/>';
            echo Form::label('end1', 'End:') . '<br/>';
            echo Form::date('endDate1', $now->format('Y-m-d'), ['class' => 'dark:input']) . '<br/>';
            echo '<br/><hr/>';

            echo 'Select the second date range for comparison:<br/>';
            // TODO: Use a better date/time selector than native HTML controls
            echo '<br/>' . Form::label('start2', 'Start:') . '<br/>';
            echo Form::date('startDate2', $now->format('Y-m-d'), ['class' => 'dark:input']) . '<br/>';
            echo Form::label('end2', 'End:') . '<br/>';
            echo Form::date('endDate2', $now->format('Y-m-d'), ['class' => 'dark:input']) . '<br/>';
            echo '<br/><hr/>';

            echo Form::submit('Submit Query', ['class' => 'dark:input']);
            echo Form::close();
        @endphp
    </div>
</x-layout>

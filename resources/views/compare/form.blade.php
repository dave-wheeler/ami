@include('map.head')
<x-layout>
    <x-slot name="title">Compare Daily Usage by Date Ranges</x-slot>

    @include('map.body')

    <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
        @php
            $now = now();
            echo Form::open(['route' => 'compare.show']);

            echo Form::hidden('lat', 0);
            echo Form::hidden('lon', 0);

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

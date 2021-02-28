<x-layout>
    <x-slot name="title">Statistics - Date Range Selection</x-slot>

    <div class="ml-12">
        <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
            @php
            echo Form::open(['route' => 'stats.show']);
            echo 'Select the date/time range.<br/>';
            // TODO: Use a better date/time selector than native HTML controls
            $now = \Carbon\Carbon::now();
            //dump($now, $now->format('Y-m-d'), $now->format('H:i'));
            echo '<br/>' . Form::label('start', 'Start:') . '<br/>';
            echo Form::date('startDate', $now->format('Y-m-d')) . '&nbsp;';
            echo Form::time('startTime', $now->format('H:i')) . '<br/>';
            echo '<br/>' . Form::label('end', 'End:') . '<br/>';
            echo Form::date('endDate', $now->format('Y-m-d')) . '&nbsp;';
            echo Form::time('endTime', $now->format('H:i')) . '<br/>';
            echo '<br/><br/>' . Form::submit();
            echo Form::close();
            @endphp
        </div>
    </div>
</x-layout>

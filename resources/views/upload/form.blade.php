<x-layout>
    <x-slot name="title">Upload</x-slot>

    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
        @php
            echo Form::open(['route' => 'upload.store', 'files' => 'true']);
            echo 'Select the file to upload.<br/><br/>';
            // TODO: Use a better uploader than native HTML control
            echo Form::file('data', ['class' => 'dark:input']) . '<br/><br/>';
            echo Form::submit('Upload File', ['class' => 'dark:input']);
            echo Form::close();
        @endphp
    </div>
</x-layout>

<x-layout>
    <x-slot name="title">AMI</x-slot>

    <div class="flex items-center dark:text-white">
        {{-- SVG from https://www.svgrepo.com/svg/141736/electricity --}}
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="10" viewBox="0 0 501.999 501.999" class="w-8 h-8">
            <path
                d="M307.314,58.642l41.571-41.571c3.905-3.905,3.905-10.237,0-14.143c-3.906-3.904-10.236-3.904-14.143,0L293.172,44.5 L278.84,30.168c-1.876-1.875-4.419-2.929-7.071-2.929c-2.652,0-5.196,1.054-7.071,2.929l-30.32,30.321 c-11.843,11.844-19.304,27.526-21.007,44.16c-1.399,13.658,1.12,27.479,7.109,39.701L118.94,245.889 c-9.827,9.827-12.74,24.474-7.422,37.313c5.317,12.84,17.734,21.137,31.633,21.137h93.038l-80.762,80.763 c-0.162-18.742-15.459-33.939-34.238-33.939c-18.879,0-34.238,15.359-34.238,34.239v82.358c0,18.88,15.374,34.239,34.271,34.239 h82.325c18.88,0,34.239-15.359,34.239-34.239c0-18.779-15.196-34.075-33.938-34.237L343.06,294.31 c8.453-8.454,11.773-20.47,9.157-31.835c-0.408-3.52-2.18-7.803-4.385-10.582c-6.188-9.885-17.031-16.032-28.984-16.032h-93.037 l42.496-42.496c10.851,5.861,22.743,8.744,34.587,8.744c18.814,0,37.503-7.276,51.665-21.438l30.32-30.32 c3.905-3.905,3.905-10.237,0-14.143L307.314,58.642z M194.598,238.79c-2.859,2.86-3.716,7.161-2.168,10.898 c1.548,3.736,5.194,6.173,9.239,6.173h117.18c5.778,0,10.941,3.448,13.154,8.787l0.001,0.002 c0.001,0.001,0.001,0.001,0.001,0.001c2.212,5.339,1,11.431-3.087,15.518L172.636,436.451c-2.859,2.86-3.715,7.161-2.168,10.898 c1.548,3.736,5.194,6.173,9.239,6.173h23.84c7.852,0,14.239,6.388,14.239,14.238c0,7.852-6.388,14.239-14.239,14.239h-82.325 c-7.736,0-14.271-6.521-14.271-14.239v-82.358c0-7.852,6.388-14.239,14.238-14.239c7.852,0,14.239,6.388,14.239,14.239v23.841 c0,4.045,2.437,7.691,6.173,9.239c3.737,1.545,8.038,0.691,10.898-2.168l114.903-114.903c2.859-2.86,3.715-7.161,2.168-10.898 c-1.548-3.736-5.194-6.173-9.239-6.173h-117.18c-5.78,0-10.943-3.45-13.155-8.79c-2.212-5.34-1-11.431,3.087-15.518 l98.821-98.822l20.08,20.194L194.598,238.79z M340.417,166.528c-18.229,18.231-46.774,20.75-67.868,5.992 c-2.597-1.816-5.008-3.833-7.167-5.992l-18.832-18.942c-1.916-2.139-3.672-4.448-5.236-6.894 c-13.306-20.797-10.276-48.58,7.205-66.061l23.249-23.25l91.898,91.898L340.417,166.528z"/>
            <path
                d="M270.564,156.768c1.986,1.984,4.188,3.827,6.545,5.477c1.745,1.222,3.744,1.808,5.725,1.808 c3.153,0,6.257-1.488,8.202-4.268c3.166-4.525,2.065-10.76-2.46-13.927c-1.405-0.983-2.709-2.072-3.873-3.236 c-3.907-3.903-10.237-3.9-14.143,0.004C266.656,146.533,266.658,152.864,270.564,156.768z"/>
            <path
                d="M260.234,77.678c-13.122,13.122-17.612,32.215-11.72,49.828c1.4,4.186,5.3,6.83,9.482,6.83 c1.052,0,2.121-0.168,3.174-0.52c5.237-1.752,8.063-7.419,6.311-12.656c-3.471-10.374-0.829-21.616,6.896-29.34 c3.905-3.905,3.905-10.237,0-14.143C270.47,73.774,264.14,73.774,260.234,77.678z"/>
            <path
                d="M412.12,66.164c-3.906-3.904-10.236-3.904-14.143,0l-24.406,24.407c-3.905,3.905-3.905,10.237,0,14.143 c1.953,1.952,4.512,2.929,7.071,2.929s5.118-0.977,7.071-2.929l24.406-24.407C416.025,76.401,416.025,70.069,412.12,66.164z"/>
        </svg>
        <div class="ml-4 text-lg leading-7 font-semibold text-gray-900 dark:text-white">AMI</div>
    </div>

    <div class="ml-12">
        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
            <p>Import and analyze IREA AMI data.</p>
            To Import:
            <ul>
                <li><a href="{{route('upload.index')}}">Upload files</a></li>
                <li>Or use the artesan command import:ami<br/>
                    (Useful for automating multiple file uploads) e.g.<br/>
                    <code>for f in *.json;do ./artisan import:ami $f;done</code>
                </li>
            </ul>

            Analysis
            <ul>
                <li><a href="{{route('stats.form')}}">Stats by date range</a></li>
            </ul>
        </div>
    </div>
</x-layout>

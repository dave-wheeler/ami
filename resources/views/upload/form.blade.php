<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Upload</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}

            .flex{display:flex}  .grid{display:grid}

            .justify-center{justify-content:center}

            .text-sm{font-size:.875rem}  .text-lg{font-size:1.125rem}

            .mx-auto{margin-left:auto;margin-right:auto}

            .mt-2{margin-top:.5rem}

            .ml-4{margin-left:1rem}  .mt-8{margin-top:2rem}  .ml-12{margin-left:3rem}

            .max-w-6xl{max-width:72rem}  .min-h-screen{min-height:100vh}  .overflow-hidden{overflow:hidden}  .p-6{padding:1.5rem}

            .relative{position:relative}

            .shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}  .text-center{text-align:center}

            .text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}  .text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}

            .antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}

            .grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}

                .sm\:items-center{align-items:center}

                .sm\:ml-0{margin-left:0}  .sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}  .sm\:pt-0{padding-top:0}

                .sm\:text-right{text-align:right}}@media (min-width:768px){
                .md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}  .dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}

                .dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="p-6">
                            <div class="ml-12">
                                <div class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                                    <?php
                                    echo Form::open(['route' => 'upload.store', 'files' => 'true']);
                                    echo 'Select the file to upload.<br/><br/>';
                                    echo Form::file('data') . '<br/><br/>';
                                    echo Form::submit('Upload File');
                                    echo Form::close();
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="flex flex-shrink-0 w-64">
            @include('layouts.sidebar')
        </div>

        <div class="flex flex-col flex-1 min-w-0">
            @include('layouts.navigation')
            <!-- Main Content -->
            <div class="flex-1 p-6">
              @isset($header)
              {{ $header }}
                  
              @endisset              

                <main>
                    {{ $slot }}
                </main>
              

                

            </div>

        </div>

    </div>
</body>

</html>

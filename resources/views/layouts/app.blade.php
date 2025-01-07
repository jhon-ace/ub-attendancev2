<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preload" as="image" href="{{ asset('assets/img/ublogo.jpg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">

        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/fontawesome.min.css"/>
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css')}}"/> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/solid.min.css">
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/solid.min.css')}}"/> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/regular.min.css"/>
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/regular.min.css')}}"/> -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/fancybox.css')}}"/> -->

        <title>{{ $title ?? config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net"> 
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <!-- <script src="{{ asset('assets/js/popper.min.js')}}"  defer></script> -->
        <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
        <!-- <script src="{{ asset('assets/js/tippy-bundle.umd.js')}}"  defer></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
        <!-- <script src="{{ asset('assets/js/jspdf.umd.min.js')}}" defer></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <!-- <script src="{{ asset('assets/js/html2canvas.min.js')}}"  defer></script> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.css')}}"/> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/dark.css')}}"/> -->
        <!-- Scripts -->
         
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
         <!-- <script src="{{ asset('assets/js/sweetalert2@11.js')}}"></script> -->
         
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased " style="font-family:arial">
        <div class="min-h-screen bg-slate-300">
            
            @if (request()->routeIs('admin.attendance.employee_attendance.portal'))

            @else
                @include('layouts.sidebar-navigation')
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <!-- <script src="{{ asset('assets/js/jquery.min.js')}}" defer></script> -->
        <!--  Flatpickr  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>
        <!-- <script src="{{ asset('assets/js/flatpickr.js')}}" defer></script> -->
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <!-- <script src="{{ asset('assets/js/fancybox.umd.js')}}" defer></script>
        <script src="{{ asset('assets/js/sweetalert2@11')}}" defer></script>
        <script src="{{ asset('assets/js/popper.min.js')}}" defer></script>
        <script src="{{ asset('assets/js/tippy-bundle.umd.js')}}" defer></script> -->
        
    </body>
</html>

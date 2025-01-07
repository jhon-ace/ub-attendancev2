<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance System</title>

    <!-- Styles -->
    <style>
        /* Your existing styles here */
        .logo-background {
            background-image: url('{{ asset('assets/img/logo.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 1; /* Ensure it's behind other content */
           
        }

        /* Center the form */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Ensure full viewport height */
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-r from-yellow-400 to-red-500">
<div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
    <div class="logo-background"></div> <!-- Add this div for the logo background -->
    <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
        <div class="form-container"> <!-- Container for centering -->
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            
        </div> <!-- End .form-container -->
    </div>
</div>
</body>
</html>

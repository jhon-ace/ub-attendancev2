<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance System</title>
    <!-- Fonts -->
    <!-- Styles -->
    <style>
        .logo-background {
            background-image: url('{{ asset('assets/img/bg.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Ensure it's behind other content */
            opacity: .9; /* Adjust opacity as needed */
        }

        .container {
            max-width: 1200px; /* Adjust as needed */
            margin: 0 auto; /* Center the container */
            padding: 0 1rem; /* Add padding to the left and right */
            min-height: 100vh; /* Ensure full viewport height */
            display: flex;
            flex-direction: column;
        }

        .form-section {
            padding: 2rem;
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 400px; /* Adjust width as needed */
            margin-top:50px;/* Center the form horizontally and add top and bottom margin */
        }

        .text-section {
            display:flex;
            justify-content: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.8);
            color: #333;
            font-size: 1.5rem;
            text-align: center;
            width: 100%;
            max-width: 1000px; /* Adjust width as needed */
            margin-top: auto; /* Push text section to the bottom */
        }

        .motto {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased ">
<div class="relative min-h-screen">
    <div class="logo-background"></div> 
    <div class="container">

        <div class="form-section mx-auto mt-16">

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('employee_login') }}">
                @csrf

                <img src="{{ asset('assets/img/logo.png')}}" alt="" class="w-24 mx-auto mb-4">
                <h4 class="text-center mb-10 text-lg uppercase tracking-widest">UB Attendance System Employee Login Portal</h4>

                <div class="mb-4">
                    <x-input-label for="employee_id" :value="__('Enter Employee ID Number')" />
                    <x-text-input id="employee_id" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                  type="text"
                                  name="employee_id"
                                  required
                                  autofocus
                                  autocomplete="employee_id" />
                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                </div>
    
                <div class="mb-4">
                    <x-input-label for="employee_lastname" :value="__('Enter your lastname')" />
                    <x-text-input id="employee_lastname" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                  type="text"
                                  name="employee_lastname"
                                  required
                                  autocomplete="employee_lastname" />
                    <x-input-error :messages="$errors->get('employee_lastname')" class="mt-2" />
                </div>
    
                <div class="flex justify-center">
                    <x-primary-button class="">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
                <div class="flex items-center justify-end " readonly>
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="" >
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Text Section -->
        <div class="text-section mx-auto">
            <p class="motto">A premier university transforming lives for a great future.</p>
        </div>
    </div>
</div>
</body>
</html>

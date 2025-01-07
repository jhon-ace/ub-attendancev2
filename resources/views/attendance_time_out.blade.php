<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="30">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preload" as="image" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Time Out Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Styles for input fields */
        input[type=password] {
            display: block;
            outline: none;
            border: none;
            height: 2em;
            font-size: 16px;
            margin-bottom: 1px;
            outline: none;
            box-shadow: none;
            background: linear-gradient(to right, #FBBF24, #EF4444);
        }

        input[type=password]:focus {
            outline: none;
            box-shadow: none;
            background: linear-gradient(to right, #FBBF24, #EF4444);
            
        }

        /* General body styles */
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: sans-serif;
            background: linear-gradient(to right, #FBBF24, #EF4444);
            color: #000; /* Adjust text color as needed */
        }

        /* Container styles */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            flex: 1; /* Fill remaining vertical space */
        }

        /* Footer styles */
        footer {
            padding: 1rem;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            position: sticky;
            bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="flex w-full">
            <div class="flex flex-1 flex-col w-full -pl-8 mt-5">
                <div class="font-bold uppercase flex justify-center">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-[500px]">
                </div>
            </div>
        </div>
    </div>
    <div class="w-full z-10">
            <form id="attendanceForm" action="{{ route('admin.attendance.time-out.store') }}" method="POST">
                @csrf
                <div class="z-10">
                    <input type="password" id="inputField" name="user_rfid"
                        class="bg-gradient-to-r from-yellow-400 to-red-500 mt-1 p-2 text-[#F9C915] w-full"
                        autocomplete="off" autofocus>
                </div>
            </form>
        </div>
    <footer class="bg-gradient-to-r from-yellow-400 to-red-500 text-white text-center py-3 tracking-wide">
        <div class="max-w-screen-lg mx-auto">
            A premier university transforming lives for a great future. Anchored on: SCHOLARSHIP, CHARACTER, SERVICE
        </div>
    </footer>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputField = document.getElementById('inputField');
            const form = document.getElementById('attendanceForm');

            inputField.addEventListener('input', function () {
                form.submit();
            });
        });
    </script>
    @endpush
</body>
</html>

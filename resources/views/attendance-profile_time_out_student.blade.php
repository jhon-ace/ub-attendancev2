<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Profile Out</title>
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
    <script>
        setTimeout(function() {
            window.location.href = "{{ route('attendance.portal.student') }}";
        }, 500); // 5000 milliseconds = 5 seconds
    </script>
</head>
<body>
    <div class="container">
        @forelse ($students as $student)
            <div class="flex w-full">
                <div style="width: 600px;" class="pl-16 ml-5">
                    @if ($student->student_photo && Storage::exists('public/student_photo/' . $student->student_photo))
                    <div class="flex justify-center mb-4 mt-5">
                        <img src="{{ asset('storage/student_photo/' . $student->student_photo) }}" class="rounded-lg object-contain" alt="Student Photo">
                    </div>
                    @else
                    <div class="flex justify-center mb-4">
                        <img data-fancybox src="{{ asset('assets/img/user.png') }}" class="cursor-pointer w-48 h-48 object-cover hover:border hover:border-red-500 rounded-sm" title="Click to view Picture" alt="Default User Photo">
                    </div>
                    @endif
                </div>
                <div class="flex flex-1 flex-col w-full -pl-8 mt-5">
                    <div class="font-bold uppercase flex justify-center">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-[230px]">
                    </div>
                    <div class="p-2 mb-2 mt-5 font-bold uppercase">
                        <span class="text-sm">Student ID</span><br>
                        <span style="font-size: 35px;" class="text-white shadow-sm">{{ $student->student_id }}</span>
                    </div>
                    <div class="p-2 mb-2 font-bold uppercase">
                        <span class="text-sm">Student Full Name</span><br>
                        <span style="font-size: 40px;" class="text-white shadow-sm">{{ $student->student_lastname }}, {{ $student->student_firstname }} {{ $student->student_middlename }}</span>
                    </div>
                    <div class="p-2 font-bold uppercase">
                        <span class="text-sm">Program</span><br>
                        <span style="font-size: 20px;" class="text-white shadow-sm">{{ $student->course->course_name}}({{ $student->course->course_abbreviation}})</span>
                    </div>
                    <div class="p-2 font-bold uppercase">
                        <span class="text-sm">Department/Office</span><br>
                        <span style="font-size: 25px;" class="text-white shadow-sm">{{ $student->course->department->department_name }}</span>
                    </div>
                </div>
            </div>
        @empty
        <p>No employee found.</p>
        @endforelse
    </div>
    <div class="w-full z-10">
            <form id="attendanceForm" action="{{ route('admin.attendance.store.student') }}" method="POST">
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

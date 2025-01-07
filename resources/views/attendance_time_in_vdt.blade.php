
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="3600">  <!-- 30 seconds refresh -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preload" as="image" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Time In Portal | VDT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .logo-background {
            background-image: url('{{ asset('assets/img/ublogo.jpg?v=1') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index:1;
            opacity:.9;
             transition: background-image 0.3s ease-in-out; /* Smooth transition */
            /* z-index: -1; Ensure it's behind other content */
            /* opacity: 0.2; Adjust opacity as needed */
        }
        /* Styles for input fields */
        input[type=password] {
            display: block;
            outline: none;
            border: none;
            height: 2em;
            font-size: 16px;
            background:none;
            margin-bottom: 1px;
            box-shadow: none;
            /* background: linear-gradient(to right, #FBBF24, #EF4444); */
            /* background: linear-gradient(to right, #1e3a8a, #1e3a8a); */
            z-index: 1000;
        }

        input[type=password]:focus {
            outline: none;
            box-shadow: none;
            /* background: linear-gradient(to right, #FBBF24, #EF4444); */
            /* background: linear-gradient(to right, #1e3a8a, #1e3a8a); */
            text-align:center;
            background:none;
            color:white;
        }

        /* General body styles */
        body {
        margin: 0;
        display: flex; /* ds*/
        flex-direction: column;
        min-height: 100vh;
        font-family: sans-serif;
        /* background: linear-gradient(to right, #FBBF24, #EF4444); */
        /* background: linear-gradient(to right, #1e3a8a, #2563eb); */
        /* background: linear-gradient(to right, #1e3a8a, #1e3a8a); */

        overflow:hidden;

    }

    /* Container styles */
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        margin-left:60px;
        text-align: center;
        flex: 1;
        overflow: hidden; /* Prevents container scroll */
        
    }

    /* Table and content styles */
    .flex-container {
        display: flex;
        justify-content: space-evenly;
        align-items: flex-start;
        width: 100%;
        overflow: hidden; /* Prevents container scroll */
        margin-top:-100px;
        /* background-color:red; */
        
    }

    .table-container {
        border-radius: 4px;
        background-color: rgba(255, 255, 255, 1);
        overflow: hidden; /* Prevents container scroll */
        /* margin-bottom: 2rem; Adds space between tables */
        height:65vh;
        z-index: 10;

    }

    table {
        border-collapse: collapse;
        width: 450px; /* Adjust width as needed */
        background-color: rgba(255, 255, 255, 0.8); 
        padding: 1rem;
        margin: 0.5rem; /* Adjusted margin */
        table-layout:fixed;
    }


    tbody {
        display: block;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        height:400px;
        scrollbar-width: none; 
        -ms-overflow-style: none; 
    }

    td, th {
        padding: 2px;
        width: 350px; /* Adjust width as needed */
        border-right: 1px solid #ccc;
        border: 1px solid;
        text-align:left;
        color:black;
    }

    thead tr {
        background: #FBBF24;
        color: #eee;
        display: block;
        position: relative;
        width: 100%;
        border:1px solid black;
    }

    footer {
        padding: 2rem;
        text-align: center;
        /* background-color: rgba(255, 255, 255, 0.8); */
        position: sticky;
        bottom: 0;
        z-index:1;
        /* background: linear-gradient(to right, #1e3a8a, #1e3a8a); */
    }

    h2 {
        font-weight: bold;
        font-size: 2rem;
        text-transform: uppercase;
        margin-bottom: 1rem;
        color: #fff; /* Ensure contrast against background */
    }

    #my-time {
        font-family:"Let's go Digital";
        font-size: 110px;
        font-weight: bold;
        text-align: center;
        margin-top: 20px; /* Adjust margin to fit design */
        color:   #318CE7;
        position: absolute;
        bottom: 20px; /* Position below the logo */
        left: 50%;
        transform: translateX(-50%);
        padding: 20px; /* Padding to make time more visible */
        z-index: 1;
        -webkit-text-stroke: 2px white; /* Text stroke for WebKit browsers (Safari, Chrome, etc.) */
        box-shadow:none;
    }
    .s{
        z-index: 10;
    }

    #timeInTable tr:last-child,
    #timeOutTable tr:last-child {
        background-color: #FFFFCC; /* Adjust color as needed */
    }

    </style>
</head>
<body>
<div class="container ">
<div class="logo-background" id="logoBackground"></div> 
    <div class="flex-container">
        <div class="table-container shadow-xl mr-[50px]">
            <h2 class="font-bold text-2xl text-black uppercase mb-2 mt-4 tracking-widest text-center">Time - In List</h2>
            <table>
                <thead>
                    <tr>
                        <th class="uppercase text-center tracking-widest" style="max-width:283px;">Employee Name</th>
                        <th class="uppercase text-center tracking-widest">MM - DD :: TIME</th>
                    </tr>
                </thead>
                <tbody  id="timeInTable" >
                    @foreach($curdateDataIn as $data)
                        <tr>
                            <td class="font-bold text-sm uppercase truncate tracking-wider" style="max-width:214px;">
                                <text>{{ $data->employee->employee_lastname}}, {{ $data->employee->employee_firstname}} {{ $data->employee->employee_middlename}}</text>
                            </td>
                            <td class="font-bold text-md uppercase text-center tracking-wider" >{{ date('m-d :: g:i:s A', strtotime($data->check_in_time)) }}</td>
                        </tr>
                    @endforeach
                    <!-- Repeat the above <tr> structure for each row as needed    date('g:i:s A', strtotime($attendanceIn->check_in_time)) -->
                </tbody>
            </table>
        </div> 

        <div class="table-container shadow-xl mr-10">
            <h2 class="font-bold text-2xl text-black uppercase mb-2 mt-4 tracking-widest text-center">Time - OUT List</h2>
            <table>
                <thead>
                    <tr>
                        <th class="uppercase text-center tracking-widest"  style="max-width:281px;">Employee Name</th>
                        <th class="uppercase text-center tracking-widest">MM - DD :: TIME</th>
                    </tr>
                </thead>
                <tbody  id="timeOutTable" >
                    @foreach($curdateDataOut as $dataOut)
                        <tr>
                            <td class="font-bold text-sm uppercase truncate tracking-wider" style="max-width:214px;">
                                <text>{{ $dataOut->employee->employee_lastname}}, {{ $dataOut->employee->employee_firstname}} {{ $dataOut->employee->employee_middlename}}</text>
                            </td>
                            <td class="font-bold text-md uppercase text-center tracking-wider">{{ date('m-d :: g:i:s A', strtotime($dataOut->check_out_time)) }}</td>
                        </tr>
                    @endforeach
                    <!-- Repeat the above <tr> structure for each row as needed -->
                </tbody>
            </table>
        </div>
        <div class="flex-col z-50">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-[250px]">
            <img src="{{ asset('assets/img/vdtlogo.png') }}" alt="Logo" class="w-[250px]">
        </div>
    </div>
    

    @if (session('error'))
        <!-- Modal Background -->
        <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 md:w-1/3 relative">
                <!-- Modal Header -->
                <div class="flex justify-center mb-4">
                    <h2 class="text-xl font-bold text-red-600">Error</h2>
                </div>
                <!-- Modal Body -->
                <div>
                    <p class="text-yellow-800 p-2 font-bold text-[20px] tracking-widest">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    <!--  -->

    @if (session('success'))
        <!-- Modal Background -->
        <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 md:w-1/3 relative">
                <!-- Modal Header -->
                <div class="flex justify-center mb-4">
                    <h2 class="text-xl font-bold text-red-600">Success</h2>
                </div>
                <!-- Modal Body -->
                <div>
                    <p class="text-yellow-800 p-2 font-bold text-[30px] tracking-widest">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif



    <!-- <div id="my-time" class="text-center tracking-wide w-full flex justify-center shadow-xl p-4 md:p-6 lg:p-8"> </div> Date and Time Display -->
    <div id="my-time" class="text-center tracking-wide w-full flex justify-center shadow-xl p-4 md:p-6 lg:p-8">
         <span id="current-date-time"></span>
    </div> <!-- Date and Time Display --> 
</div>

    <div class="w-full z-10">
        <form id="attendanceForm" action="{{ route('admin.attendance.store.vdt') }}" method="POST">
            @csrf
            <div class="z-10">
                <input type="password" id="inputField" name="user_rfid"
                    class=" mt-1 p-2 text-[#F9C915] w-full"
                    autocomplete="off" autofocus>
            </div>
        </form>
    </div>
    <footer class="w-full uppercase  font-semibold border-t border-white text-white text-center py-3 tracking-widest">
        <div class="w-full mx-auto">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('error-modal');
            const closeModalButton = document.getElementById('close-modal');

            // Show the modal
            if (modal) {
                modal.classList.remove('hidden');

                setTimeout(function() {
                modal.classList.add('hidden');
            }, 2000); // 120000 milliseconds = 2 minutes


            }

            // Close the modal when the close button is clicked
            if (closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });
            }

            // Optionally, close the modal when clicking outside of it
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('success-modal');
            const closeModalButton = document.getElementById('close-modal');

            // Show the modal
            if (modal) {
                modal.classList.remove('hidden');

                setTimeout(function() {
                modal.classList.add('hidden');
            }, 2000); // 120000 milliseconds = 2 minutes


            }

            // Close the modal when the close button is clicked
            if (closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });
            }

            // Optionally, close the modal when clicking outside of it
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
    <!-- <script>
    //    document.addEventListener('DOMContentLoaded', function () {
            var timeDisplayElement = document.querySelector('#my-time');
            
            function printTime() {
                var now = new Date();
                var options = { 
                    year: '2-digit', 
                    month: '2-digit', 
                    day: '2-digit'
                };
                var date = now.toLocaleDateString(undefined, options);

                // Manually format the date to match the desired format "Fri, 2024-06-14"
                var dateParts = date.split('/');
                var formattedDate = `${dateParts[0]}`;//-${dateParts[2]}-${dateParts[1]}
                
                var time = now.toLocaleTimeString();
                time = time.replace('AM', 'A.M.').replace('PM', 'P.M.');
                
                timeDisplayElement.innerHTML = `${now.toLocaleDateString(undefined, { weekday: 'short' })}, ${time}`; // ${formattedDate} 
            }
            
            setInterval(printTime, 1000);
        // });

    </script> -->

          <script>
        // Function to update the time display
        function updateTime(serverTime) {
            const timezone = 'Asia/Taipei';

            // Define options for formatting the date
            const dateOptions = {
                weekday: 'short',
                // day: '2-digit',
                timeZone: timezone
            };

            // Define options for formatting the time
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: timezone,
                hour12: true
            };

            // Format the server date and time in Taipei timezone
            const dateFormatter = new Intl.DateTimeFormat('en-US', dateOptions);
            const timeFormatter = new Intl.DateTimeFormat('en-US', timeOptions);

            // Calculate the difference between server time and local time
            const localNow = new Date();
            const timeDiff = localNow - new Date(serverTime);

            // Update the date and time every second
            setInterval(() => {
                const now = new Date(new Date().getTime() - timeDiff);
                const formattedDate = dateFormatter.format(now);
                const formattedTime = timeFormatter.format(now);

                // Combine date and time in the desired format
                const formattedDateTime = `${formattedDate}, ${formattedTime}`;

                document.getElementById('current-date-time').textContent = formattedDateTime;
            }, 1000);
        }

        // Initialize the date and time display
        document.addEventListener('DOMContentLoaded', () => {
            const serverTime = "{{ \Carbon\Carbon::now('Asia/Taipei')->toIso8601String() }}";
            updateTime(serverTime);
        });
    </script>


    <!-- <script>
        // Set the timezone to Taipei
        const timezone = 'Asia/Taipei';

        function updateTime() {
            // Get the current date and time in Taipei timezone
            const now = new Date();
            
            // Define options for formatting the date
            const dateOptions = {
                weekday: 'short', // Short weekday name (e.g., Mon for Monday)
                // year: '2-digit',
                // month: 'short', // Short month name (e.g., Jul for July)
                // day: 'numeric',
                timeZone: timezone
            };

            // Define options for formatting the time
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: timezone,
                hour12: true // Use 12-hour format with AM/PM
            };
            
            // Format the current date and time in Taipei timezone
            const dateFormatter = new Intl.DateTimeFormat('en-US', dateOptions);
            const timeFormatter = new Intl.DateTimeFormat('en-US', timeOptions);

            const formattedDate = dateFormatter.format(now);
            const formattedTime = timeFormatter.format(now);

            // Update the date and time on the page
            document.getElementById('current-date').textContent = formattedDate;
            document.getElementById('current-time').textContent = formattedTime;
        }

        // Update the date and time every second
        setInterval(updateTime, 1000);

        // Initialize the date and time display immediately
        updateTime();
    </script> -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to scroll to the bottom of a container
            function scrollToBottom(containerId) {
                var container = document.getElementById(containerId);
                container.scrollTop = container.scrollHeight;
            }

            // Example usage: scroll to bottom of timeInTable on page load
            scrollToBottom('timeInTable');
             scrollToBottom('timeOutTable');

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var prevScrollpos = window.pageYOffset;
            // var header = document.querySelector('.table-container th');

            window.addEventListener('scroll', function () {
                var currentScrollPos = window.pageYOffset;
                if (prevScrollpos > currentScrollPos) {
                    // Scrolling up
                    // header.classList.add('show');
                } else {
                    // Scrolling down
                    // header.classList.remove('show');
                }
                prevScrollpos = currentScrollPos;
            });
        });
    </script>
     <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                var sessionError = document.getElementById('session-error');
                if (sessionError) {
                    sessionError.style.display = 'none';
                }

                var validationErrors = document.getElementById('validation-errors');
                if (validationErrors) {
                    validationErrors.style.display = 'none';
                }
            }, 5000); // 5000 milliseconds = 5 seconds
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                var sessionSuccess = document.getElementById('session-success');
                if (sessionSuccess) {
                    sessionSuccess.style.display = 'none';
                }

                var validationErrors = document.getElementById('validation-errors');
                if (validationErrors) {
                    validationErrors.style.display = 'none';
                }
            }, 5000); // 5000 milliseconds = 5 seconds
        });
    </script>
     <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bgElement = document.getElementById('logoBackground');
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        bgElement.style.backgroundImage = "url('{{ asset('assets/img/ublogo.jpg') }}')";
                        observer.disconnect(); // Stop observing once loaded
                    }
                });
            });

            observer.observe(bgElement);
        });
    </script>
</body>
</html>

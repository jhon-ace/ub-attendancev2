
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="3600">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preload" as="image" href="{{ asset('assets/img/logo.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Time In-Out Portal | STUDENT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .highlight-row {
            background-color: #f0f9ff; /* Light blue highlight */
            animation: highlightFade 3s ease-out;
        }
        .logo-background {
            /* background-image: url('{{ asset('assets/img/ublogo.jpg?v=1') }}'); */
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
        padding:2px 9px 9px 9px;
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

    <div class="mt-4 md:-mt-20 lg:-mt-10 flex flex-col md:flex-row justify-between items-start gap-4 z-50">

        <!-- TIME IN Section -->
        <div class="w-full md:w-1/2 space-y-4">
            <h2 class="text-3xl md:text-3xl font-bold tracking-widest">TIME IN</h2>
            <div id="timeInContainer" class="flex flex-wrap md:flex-nowrap gap-2 justify-center md:justify-start">
                <div class="w-[250px] h-[250px] md:w-[350px] md:h-[350px] border flex items-center justify-center text-center">
                    <!-- <p class="font-bold">LATEST</p> -->
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 space-y-4 mt-6 md:mt-0 pl-5 pr-5">
            <div class="flex flex-wrap md:flex-nowrap gap-2 justify-center md:justify-start">
                <div class="w-[300px] h-[250px] md:w-[350px] md:h-[350px] flex items-center justify-center text-center relative">
                    <div>
                        
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-[350px] mt-28">
                        <!-- Response Message Above the Image -->
                        <div id="responseMessage" class="mb-4 text-center text-lg font-semibold uppercase absolute top-0 left-0 w-full tracking-widest"></div>
                        
                        <!-- Image Below the Response Message -->
                         
                    </div>
                    
                </div>
                
            </div>
        </div>


        <!-- TIME OUT Section -->
        <div class="w-full md:w-1/2 space-y-4 mt-6 md:mt-0">
            <h2 class="text-3xl md:text-3xl  font-bold tracking-widest">TIME OUT</h2>
            <div id="timeOutContainer" class="flex flex-wrap md:flex-nowrap gap-2 justify-center md:justify-start">
                <div class="w-[250px] h-[250px] md:w-[350px] md:h-[350px] border flex items-center justify-center text-center">
                    <!-- <p class="font-bold">LATEST</p> -->
                </div>
                <!-- <div class="w-24 h-24 md:w-32 md:h-32 border"></div>
                <div class="w-24 h-24 md:w-32 md:h-32 border"></div> -->
            </div>
        </div>

        <!-- LOGO Section (Optional) -->
        <!-- Uncomment if needed -->
        
        <!-- <div class="hidden lg:flex justify-end">
            <div class="ml-32 w-[250px] h-24 md:w-[250px] md:h-40 rounded-full flex items-end justify-end">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="">
            </div>
        </div> -->
       
    </div>




    



    <!-- <div id="my-time" class="text-center tracking-wide w-full flex justify-center shadow-xl p-4 md:p-6 lg:p-8"> </div> Date and Time Display -->
    <div id="my-time" class="text-center tracking-wide w-full flex justify-center shadow-xl p-4 md:p-6 lg:p-8">
         <span id="current-date-time"></span>
    </div> <!-- Date and Time Display --> 
</div>

    <div class="w-full z-10">
        <!-- <form id="attendanceForm" action="{{ route('admin.attendance.store.student') }}" method="POST">
            @csrf
            <div class="z-10">
                <input type="password" id="inputField" name="user_rfid"
                    class=" mt-1 p-2 text-[#F9C915] w-full"
                    autocomplete="off" autofocus>
            </div>
        </form> -->
        <form id="attendanceForm">
                @csrf
                <input type="password" id="user_rfid" ID="inputField" name="user_rfid" class=" mt-1 p-2 text-[#F9C915] w-full"
                    autocomplete="off" autofocus>
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

<script>
    document.getElementById('attendanceForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent page reload

        const formData = new FormData(this); // Collect form data
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.getElementById('user_rfid').value = ''; // Reset the input field
        fetch('{{ route("admin.attendance.store.student") }}', { // Replace with your route
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const responseMessage = document.getElementById('responseMessage');

            if (data.success) {
                responseMessage.textContent = data.message; // Display success message
                responseMessage.style.color = 'white';
                responseMessage.style.textShadow = '0px 0px 1px white, -2px -2px 1px black';

                // Hide the message after 5 seconds
                setTimeout(() => {
                    responseMessage.textContent = ''; // Clear message
                }, 5000); // 5000ms = 5 seconds

                fetchAttendanceData();  

            } else {
                responseMessage.textContent = data.message; // Display error message
                responseMessage.style.color = 'red';

                // Hide the message after 5 seconds
                setTimeout(() => {
                    responseMessage.textContent = ''; // Clear message
                }, 5000); // 5000ms = 5 seconds
            }

        })
        .catch(error => console.error('Error:', error));
    });


    function fetchAttendanceData() {
        fetch('{{ route("admin.attendance.fetch.latest") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type": "application/json"
            },
            credentials: "same-origin"
        })
        .then(response => response.json())
        .then(data => {
            // TIME IN Section
            const timeInContainer = document.getElementById('timeInContainer');
            timeInContainer.innerHTML = ''; // Clear existing content
            
            if (data.curdateDataIn.length === 0) {
                // No Time-In Data Fallback
                timeInContainer.innerHTML = `
                    <div class="flex-col text-center">
                        <img src="{{ asset('assets/img/user.png') }}" alt="Default User Photo" class="rounded-full w-[350px] h-[350px]"/>
                        <p class="text-sm font-medium text-white font-bold uppercase tracking-widest">No Check-In Data Available</p>
                    </div>
                `;
            } else {
                const latestCheckInRecords = data.curdateDataIn.reverse().slice(0,1);
                    latestCheckInRecords.forEach((record, index) => {
                        const studentName = `${record.student ? record.student.student_firstname : 'N/A'} ${record.student ? record.student.student_lastname : 'N/A'}`;
                        const courseName = index === 0 && record.student && record.student.course ? record.student.course.course_abbreviation : '';
                        const idNumber = index === 0 && record.student && record.student.student_id ? record.student.student_id : '';
                        
                        const imageSizeClass = index === 0 ? 'w-[350px] h-[350px] ' : 'w-32 h-32';
                        const imagePad = index === 0 ? 'mt-2 font-xl' : '';
                        const nameLabel = index === 0 ? 'Name: ' : '';
                        const courseLabel = index === 0 ? 'Course: ' : '';
                        const idLabel = index === 0 ? 'Student No: ' : '';

                        timeInContainer.innerHTML += `
                            <div class="flex-col text-center">
                                <div class="flex justify-center">
                                    <img src="${record.student && record.student.profile_image 
                                        ? record.student.profile_image 
                                        : '{{ asset('assets/img/user.png') }}'}" 
                                        alt="Student Photo" 
                                        class=" rounded-sm ${imageSizeClass}"
                                        loading="lazy"/>
                                </div>
                                ${
                                    index === 0 
                                    ? `<p class="text-md text-white uppercase font-bold tracking-widest mt-2 
                                            [text-shadow:0px_0px_1px_yellow]">
                                            ${idLabel}${idNumber}
                                        </p>`
                                    : ``
                                }
                                ${
                                    index === 0 
                                    ? `<p class="text-md text-white text-center uppercase font-bold tracking-widest ${imagePad} 
                                            truncate overflow-hidden whitespace-nowrap [text-shadow:2px_1px_2px_black]">
                                            ${studentName}
                                        </p>
                                        `
                                    : ``
                                }      
                                ${
                                    index === 0 
                                    ? `<p class="text-lg  text-white uppercase font-bold tracking-widest [text-shadow:0px_0px_1px_yellow]">${courseName}</p>` 
                                    : `<p class="text-sm font-medium text-transparent uppercase font-bold tracking-widest">Course Placeholder</p>`
                                }
                                
                            </div>
                        `;
                        // setTimeout(() => {
                        //     timeInContainer.innerHTML = `
                        //          <div class="flex-col text-center border-0">
                        //             <div class="flex flex-col items-center border-0">
                        //                 <img src="{{ asset('assets/img/user.png') }}" 
                        //                     alt="Student Photo" 
                        //                     class="rounded-sm ${imageSizeClass}"
                        //                     loading="lazy"/>
                        //                 <p class=" tracking-widest text-white font-bold mt-2 [text-shadow:0px_0px_2px_black, 0px_0px_2px_black]">
                        //                     TAP ID TO SUBMIT ATTENDANCE
                        //                 </p>
                        //             </div>
                        //         </div>
                        //     `;
                        // }, 60000); 
                    });

            }

            // TIME OUT Section
            const timeOutContainer = document.getElementById('timeOutContainer');
            timeOutContainer.innerHTML = ''; // Clear existing content
            
            if (data.curdateDataOut.length === 0) {
                // No Time-Out Data Fallback
                timeOutContainer.innerHTML = `
                    <div class="flex-col text-center">
                        <img src="{{ asset('assets/img/user.png') }}" alt="Default User Photo" class="rounded-full w-[350px] h-[350px]"/>
                        <p class="text-sm font-medium text-white font-bold uppercase tracking-widest">No Check-Out Data Available</p>
                    </div>
                `;
            } else {
                const latestCheckOutRecords = data.curdateDataOut.reverse().slice(0,1);
                    latestCheckOutRecords.forEach((record, index) => {
                        const studentName = `${record.student ? record.student.student_firstname : 'N/A'} ${record.student ? record.student.student_lastname : 'N/A'}`;
                        const courseName = index === 0 && record.student && record.student.course ? record.student.course.course_abbreviation : '';
                        const idNumber = index === 0 && record.student && record.student.student_id ? record.student.student_id : '';
                        
                        const imageSizeClass = index === 0 ? 'w-[350px] h-[350px]' : 'w-32 h-32';
                        const imagePad = index === 0 ? 'mt-2 font-xl' : '';
                        const nameLabel = index === 0 ? 'Name: ' : '';
                        const courseLabel = index === 0 ? 'Course: ' : '';
                        const idLabel = index === 0 ? 'Student No: ' : '';

                        timeOutContainer.innerHTML += `
                            <div class="flex-col text-center">
                                <div class="flex justify-center">
                                    <img src="${record.student && record.student.profile_image 
                                        ? record.student.profile_image 
                                        : '{{ asset('assets/img/user.png') }}'}" 
                                        alt="Student Photo" 
                                        class="rounded-sm ${imageSizeClass}" loading="lazy"/>
                                </div>
                                ${
                                    index === 0 
                                    ? `<p class="text-md text-white uppercase font-bold tracking-widest mt-2 
                                            [text-shadow:0px_0px_1px_yellow]">
                                            ${idLabel}${idNumber}
                                        </p>`
                                    : ``
                                }
                                ${
                                    index === 0 
                                    ? `<p class="text-md text-white text-center uppercase font-bold tracking-widest ${imagePad} 
                                            truncate overflow-hidden whitespace-nowrap [text-shadow:2px_1px_2px_black]">
                                            ${studentName}
                                        </p>
                                        `
                                    : ``
                                }      
                                ${
                                    index === 0 
                                    ? `<p class="text-lg  text-white uppercase font-bold tracking-widest [text-shadow:0px_0px_1px_yellow]">${courseName}</p>` 
                                    : `<p class="text-sm font-medium text-transparent uppercase font-bold tracking-widest">Course Placeholder</p>`
                                }
                                
                            </div>
                        `;
                        // setTimeout(() => {
                        //     timeOutContainer.innerHTML = `
                        //          <div class="flex-col text-center border-0">
                        //             <div class="flex flex-col items-center border-0">
                        //                 <img src="{{ asset('assets/img/user.png') }}" 
                        //                     alt="Student Photo" 
                        //                     class="rounded-sm ${imageSizeClass}"
                        //                     loading="lazy"/>
                        //                 <p class=" tracking-widest text-white font-bold mt-2 [text-shadow:0px_0px_2px_black, 0px_0px_2px_black]">
                        //                     TAP ID TO SUBMIT ATTENDANCE
                        //                 </p>
                        //             </div>
                        //         </div>
                        //     `;
                        // }, 60000); 
                    });
            }
        })
        .catch(error => {
            console.error('Error fetching attendance data:', error);
            // Fallback in case of fetch error
            document.getElementById('timeInContainer').innerHTML = `
                <div class="flex-col text-center">
                    <img src="{{ asset('assets/img/user.png') }}" alt="Default User Photo" class="rounded-full w-[350px] h-[350px]" loading="lazy"/>
                    <p class="text-sm font-medium text-gray-500">Failed to fetch Check-In Data</p>
                </div>
            `;
            document.getElementById('timeOutContainer').innerHTML = `
                <div class="flex-col text-center">
                    <img src="{{ asset('assets/img/user.png') }}" alt="Default User Photo" class="rounded-full w-[350px] h-[350px]" loading="lazy"/>
                    <p class="text-sm font-medium text-gray-500">Failed to fetch Check-Out Data</p>
                </div>
            `;
        });
    }

    // Fetch initial attendance data on page load
    document.addEventListener('DOMContentLoaded', fetchAttendanceData);

    
    
</script>





</body>
</html>

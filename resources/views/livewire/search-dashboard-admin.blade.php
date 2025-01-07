    
    <div class="">
        <style>
                /* Container styles */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            flex: 1;
            overflow: hidden; /* Prevents container scroll */
        }

        /* Table and content styles */
        .flex-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            margin: 2px;
            overflow: hidden; /* Prevents container scroll */
            margin-top:-50px;
        }

        .table-container {
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.8);
            overflow: hidden; /* Prevents container scroll */
            margin-bottom: .5rem; /* Adds space between tables */
            height:69vh;
        }

        table {
            border-collapse: collapse;
            width: 400px; /* Adjust width as needed */
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
            padding: 5px;
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

        #timeInTable tr:last-child,
        #timeOutTable tr:last-child {
            background-color: #FFFFCC; /* Adjust color as needed */
        }

        input[type=text] {
                
                text-align:center;
                outline: none;
                border: none;
                height: 2em;
                font-size: 16px;
                background:none;
                margin-bottom: 1px;
                box-shadow: none;
                border-bottom:2px solid black;
                width:40%;
            }

            input[type=text]:focus {
            
                outline: none;
                box-shadow: none;
                text-align:center;
                background:none;
                color:black;
                border-bottom:2px solid black;
                width:40%;
            }
        </style>
        
        <h2 class="uppercase font-bold text-3xl">Current Date Employee Attendance Monitoring</h2>
        <span class="font-bold uppercase text-xl">Date: <span class="text-red-500">{{ \Carbon\Carbon::now()->format('F j, Y') }}</span></span>
        <p>
            <div class="mt-4 mx-auto">
                <input wire:model.live="search" type="text" class="text-sm border-0 text-black   px-3 ml-2 py-1.5 w-full md:w-64" placeholder="Search Employee or Department ..." autofocus>
            </div>
        </p>
        @if($search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty())
            <p class="text-black mt-8 text-center">No employee found</p>
            @if($search)
                <p>
                    <button class="ml-2 mt-5 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="clearSearch">
                        <i class="fa-solid fa-remove"></i> Clear Search
                    </button>
                </p>
            @endif

        @elseif (!$search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty())
            <p class="text-black mt-8 text-center">No data available</p>
        @else
            <div class="flex  justify-around ">
                <div class="table-container">
                    <h2 class="font-bold text-2xl text-black uppercase mb-2 mt-4 tracking-widest text-center">Time - In List</h2>
                    <table class="">
                        <thead>
                            <tr>
                                <th class="tracking-wider uppercase">Employee Name</th>
                                <th class="tracking-wider uppercase text-center">MM - DD :: TIME</th>
                            </tr>
                        </thead>
                        <tbody  id="timeInTable" >
                            @foreach($attendanceTimeIn as $data)
                                <tr>
                                    <td class="font-bold text-sm uppercase truncate tracking-wider" style="max-width:214px;">
                                        <text>{{ $data->employee->employee_lastname}}, {{ $data->employee->employee_firstname}} {{ $data->employee->employee_middlename}}</text>
                                    </td>
                                    <td class="font-bold text-md uppercase text-center tracking-wider">{{ date('m-d :: g:i:s A', strtotime($data->check_in_time)) }}</td>
                                </tr>
                            @endforeach
                            <!-- Repeat the above <tr> structure for each row as needed    date('g:i:s A', strtotime($attendanceIn->check_in_time)) -->
                        </tbody>
                    </table>
                </div>
                <div class=""></div>
                <div class="table-container ml-5">
                    <h2 class="font-bold text-2xl text-black uppercase mb-2 mt-4 tracking-widest text-center">Time - OUT List</h2>
                    <table>
                        <thead>
                            <tr>
                                <th class="tracking-wider uppercase">Employee Name</th>
                                <th class="tracking-wider uppercase text-center">MM - DD :: TIME</th>
                            </tr>
                        </thead>
                        <tbody  id="timeOutTable" >
                            @foreach($attendanceTimeOut as $data)
                                <tr>
                                    <td class="font-bold text-sm uppercase truncate tracking-wider" style="max-width:214px;">
                                        <text>{{ $data->employee->employee_lastname}}, {{ $data->employee->employee_firstname}} {{ $data->employee->employee_middlename}}</text>
                                    </td>
                                    <td class="font-bold text-md uppercase text-center tracking-wider">{{ date('m-d :: g:i:s A', strtotime($data->check_out_time)) }}</td>
                                </tr>
                            @endforeach
                            <!-- Repeat the above <tr> structure for each row as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


    </div>

    <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Function to scroll to the bottom of a container
                scrollToBottom('timeInTable');
                scrollToBottom('timeOutTable');

                function scrollToBottom(containerId) {
                    var container = document.getElementById(containerId);
                    container.scrollTop = container.scrollHeight;
                }

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



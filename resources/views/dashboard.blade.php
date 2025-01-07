
    <x-app-layout>
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
            margin-bottom: 2rem; /* Adds space between tables */
            height:90vh;
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
        </style>
        @if (Auth::user()->hasRole('admin'))
            <x-user-route-page-name :routeName="'admin.dashboard'" />
        @elseif(Auth::user()->hasRole('employee'))
            <x-user-route-page-name :routeName="'employee.dashboard'" />
        @elseif(Auth::user()->hasRole('admin_staff'))
            <x-user-route-page-name :routeName="'staff.dashboard'" />
        @else

        @endif
        <x-content-design>
            <!-- Content Area -->
            <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                window.addEventListener('resize', () => {
                    isFullScreen = (window.innerHeight === screen.height);
                });
                " class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
                <div class="relative">
                    <div class="container shadow-lg p-5 sm:p-6 md:p-7 lg:p-8 bg-white rounded-md text-black font-medium"
                        :style="{ 'width': isFullScreen ? 'calc(100vw - 16px)' : 'auto', 'margin-left': isFullScreen ? '-192px' : '0' }">
                        <h2 class="uppercase font-bold text-3xl">Current Date</h2>
                        <p>
                            <div class="mt-4">
                                <input wire:model.live="search" type="text" class="text-sm border text-black border-gray-300 rounded-md px-3 ml-2 py-1.5 w-full md:w-64" placeholder="Search Employee ..." autofocus>
                            </div>
                        </p>
                        @if($search && $attendanceTimeIn->isEmpty())
                        <p class="text-black mt-8 text-center">No employee found
                        @elseif (!$search && $attendanceTimeIn->isEmpty())
                            <p>No data avaible</p>
                        @else
                            <div class="flex  justify-around ">
                                <div class="table-container">
                                    <h2 class="font-bold text-2xl text-black uppercase mb-2 mt-4 tracking-widest text-center">Time - In List</h2>
                                    <table class="mr-10">
                                        <thead>
                                            <tr>
                                                <th class="tracking-wider uppercase">Employee Name</th>
                                                <th class="tracking-wider uppercase text-center">MM - DD :: TIME</th>
                                            </tr>
                                        </thead>
                                        <tbody  id="timeInTable" >
                                            @foreach($curdateDataIn as $data)
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
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- <div x-data="{ isFullScreen: (window.innerHeight === screen.height) }" x-init="
                window.addEventListener('resize', () => {
                    isFullScreen = (window.innerHeight === screen.height);
                });
                " class="rounded-md p-2 sm:p-2 md:p-2 lg:p-2 text-black font-medium">
                <div  class="container mx-auto p-1 max-h-full bg-white">
                    <div class="flex flex-row justify-center">
                        <div class="flex-1 bg-green-500">
                            <div class="text-center">
                                <div class="">TIME-IN LIST</div>
                            </div>
                        </div>
                        <div class="flex-1 bg-red-500">
                            <div class="text-center">
                                ddsd
                            </div>
                        </div>
                        <div class="flex-1 bg-green-500">
                            <div class="text-center font-bold">TIME-OUT LIST</div>
                        </div>
                    </div>
                </div>
            </div> -->
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
        </x-content-design>
    </x-app-layout>

    <x-show-hide-sidebar
        toggleButtonId="toggleButton"
        sidebarContainerId="sidebarContainer"
        dashboardContentId="dashboardContent"
        toggleIconId="toggleIcon"
    />

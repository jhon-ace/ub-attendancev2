<div class="mb-4">
    @if(Auth::user()->hasRole('admin'))
        @if (session('success'))
            <x-sweetalert type="success" :message="session('success')" />
        @endif

        @if (session('info'))
            <x-sweetalert type="info" :message="session('info')" />
        @endif

        @if (session('error'))
            <x-sweetalert type="error" :message="session('error')" />
        @endif
        <div class="flex justify-between mb-4 sm:-mt-4">
            <div class="font-bold text-md tracking-tight text-md text-black mt-2 uppercase">Admin / Employee Attendance Search</div>
        </div>

        <div class="flex justify-start">
            <div>
                <label for="search" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Search Employees:</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    wire:model.live="search" 
                    class="text-sm shadow appearance-none border rounded text-black leading-tight focus:outline-none focus:shadow-outline md:w-72"
                    placeholder="Enter Employee ID or name..."
                    autofocus
                />

            </div>
                
            <!-- cc -->
                        <!-- Modal -->
            <div x-data="{ open: false }" @keydown.window.escape="open = false" x-cloak>
                <!-- Modal Trigger Button -->
                <div class="flex justify-start">
                    <button @click="open = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2 mt-5"><i class="fa-solid fa-calendar-days"></i> View Work Details</button>
                    <div class="flex justify-center mb-2 mt-6 ml-4">
                        <div class="flex justify-center items-center space-x-2">
                            <div x-data="{ open: false }">
                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-md px-2 py-2.5 font-bold rounded hover:bg-blue-700">
                                    <i class="fa-solid fa-pen fa-md" style="color: #ffffff;"></i> Add Time In
                                </a>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                            <p class="text-xl font-bold">Add Time In</p>
                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                        </div>
                                        <div class="mb-4">
                                            <form action="{{ route('admin.attendance.employee_attendance.addIn') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time in?');">
                                                <x-caps-lock-detector />
                                                @csrf
                                                    <div class="mb-2">
                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Employee Name: </label>
                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                            @foreach($employees as $employee)
                                                                <option selected value="{{ $employee->id }}">{{ $employee->employee_id }} - {{ $employee->employee_lastname }}, {{ $employee->employee_firstname }} {{ $employee->employee_middlename }}</option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                    </div>
                                                <div class="flex mb-4 mt-10 justify-center">
                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                        Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mb-2 mt-6 ml-4">
                        <div class="flex justify-center items-center space-x-2">
                            <div x-data="{ open: false }">
                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-md px-2 py-2.5 font-bold rounded hover:bg-blue-700">
                                    <i class="fa-solid fa-pen fa-sm" style="color: #ffffff;"></i> Add Time Out
                                </a>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                            <p class="text-xl font-bold">Add Time Out</p>
                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2.5 rounded hover:text-red-500">X</a>
                                        </div>
                                        <div class="mb-4">
                                            <form action="{{ route('admin.attendance.employee_attendance.addOut') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time out?');">
                                                <x-caps-lock-detector />
                                                @csrf
                                                    <div class="mb-2">
                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Employee Name: </label>
                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                            @foreach($employees as $employee)
                                                                <option selected value="{{ $employee->id }}">{{ $employee->employee_id }} - {{ $employee->employee_lastname }}, {{ $employee->employee_firstname }} {{ $employee->employee_middlename }}</option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                    </div>
                                                <div class="flex mb-4 mt-10 justify-center">
                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                        Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Background -->
                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 z-50" @click="open = false"></div>

                <!-- Modal Content -->
                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white p-8 rounded-lg shadow-lg max-w-7xl w-full ">
                        <div class="mt-6 flex justify-between">
                            <h2 class="text-lg font-semibold mb-4">Work Details</h2>
                            <button @click="open = false" class="btn btn-secondary hover:text-blue-500">Close</button>
                        </div>
                        <!-- Modal Body -->
                        <div class="space-y-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day Of Week</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Morning Hours</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Afternoon Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        
                                        @foreach($departmentDisplayWorkingHour as $working_hour)
                                            <tr>
                                                @php
                                                    $daysOfWeek = [
                                                        0 => 'Sunday',
                                                        1 => 'Monday',
                                                        2 => 'Tuesday',
                                                        3 => 'Wednesday',
                                                        4 => 'Thursday',
                                                        5 => 'Friday',
                                                        6 => 'Saturday',
                                                    ];
                                                @endphp

                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $daysOfWeek[$working_hour->day_of_week] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ date('h:i A', strtotime($working_hour->morning_start_time)) }} - {{ date('h:i A', strtotime($working_hour->morning_end_time)) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ date('h:i A', strtotime($working_hour->afternoon_start_time)) }} - {{ date('h:i A', strtotime($working_hour->afternoon_end_time)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
                
        @if($selectedEmployeeToShow)
            @if($search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty() && !$selectedAttendanceByDate->isEmpty())
                <p class="text-black mt-8 text-center">No attendance/s found in <span class="text-red-500">{{ $selectedEmployeeToShow->employee_id }} | {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }} </span> for matching "{{ $search }}"</p>
                <p class="text-center mt-5"><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
            @elseif(!$search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty() && !$selectedAttendanceByDate->isEmpty())
                <p class="text-black mt-8 text-center uppercase">No data available in employee <text class="text-red-500">{{ $selectedEmployeeToShow->employee_id }} | {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text></p>
            @else
                <div class="flex justify-between mt-1 mb-2">
                    <div class="mt-2 text-sm font-bold ">
                        <text class="uppercase">Selected Employee: <text class="text-red-500">{{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text>
                    </div>
                    <div class="flex flex-col mt-11">
                        <div class="flex justify-between items-center mb-2">
                            <div class="grid grid-rows-2 grid-flow-col -mt-10">
                                
                                    <div class="text-center uppercase ml-16">
                                        Select Specific Date
                                    </div>
                                <div class="flex items-center space-x-4">
                                    <label for="startDate" class="text-gray-600">Start Date:</label>
                                    <input 
                                        id="startDate" 
                                        type="date" 
                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                        wire:model="startDate"
                                        wire:change="updateAttendanceByDateRange"
                                    >
                                    <label for="endDate" class="text-gray-600">End Date:</label>
                                    <input 
                                        id="endDate" 
                                        type="date" 
                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                        wire:model="endDate"
                                        wire:change="updateAttendanceByDateRange"
                                    >
                                </div>
                            </div>
                                <div class="flex flex-col -mt-10">
                                    <div class="flex justify-end mb-2 -mt-2">
                                        <a href="">
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"><i class="fa-solid fa-arrows-rotate"></i> Refresh</button>
                                        </a>
                                    </div>
                                        <button wire:click="generatePDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                                    <i class="fa-solid fa-file"></i> Generate Selected Employee's DTR
                                </button>
                                </div>               
                                
                        </div>
                    </div>
                </div>
                <div x-data="{ tab: 'time-in-time-out' }" class="mt-5 w-full">
                    <div class="overflow-x-auto">
                        <!-- Tab buttons -->
                        <div class="flex justify-between mb-4">
                            <div>
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'time-in-time-out'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'time-in-time-out', 'border border-gray-500': tab !== 'time-in-time-out' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Time In & Time Out
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        Select specific time-in dates to view details.
                                    </div>
                                </div>
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'computed-hours'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'computed-hours', 'border border-gray-500': tab !== 'computed-hours' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Detailed Calculation of Work Hours
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        View detailed calculations of work hours, including breakdowns and summaries.
                                    </div>
                                </div>

                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'reports'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'reports', 'border border-gray-500': tab !== 'reports' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Summary Report
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        View a summary of all attendance reports.
                                    </div>
                                </div>

                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'modify_date'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'modify_date', 'border border-gray-500': tab !== 'modify_date' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Modify Date for Approved Leave / Official Travel
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        Adjust dates for approved leave or official travel. Ensure to update these dates for accurate records.
                                    </div>
                                </div>

                                <!-- Button to Open Modal -->

                            </div>
                            
                            <!-- Modal Background -->
                            <div x-data="{ open: false }" @click.away="open = false">
                                <!-- Modal -->
                                <button 
                                    @click="open = true; tab = 'holidays'"
                                    :class="{ 'bg-blue-500 text-white': tab === 'holidays', 'border border-gray-500': tab !== 'holidays' }"
                                    class="px-4 py-2 mr-[80px] rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                >
                                    Holiday Dates
                                </button>

                                <div x-cloak x-show="open" 
                                    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
                                        <h2 class="text-xl font-semibold mb-4">Reminder</h2>
                                        <p class="mb-4">Please add holiday dates in settings before the actual dates to avoid system automatic absences for those dates.</p>
                                        <div class="flex justify-end">
                                            <button @click="open = false" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none">
                                                OK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab content -->
                        <div x-show="tab === 'time-in-time-out'" class="w-full">
                            <!-- Table for Time In -->
                            <div class="flex justify-between">
                                <div class="w-[49%]">
                                    <div class="flex justify-center mb-2 mt-2">
                                        <h3 class="text-center uppercase font-bold">Time In &nbsp;</h3> | &nbsp;
                                        <div class="flex justify-center items-center space-x-2">
                                            <div x-data="{ open: false }">
                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Add Time In
                                                </a>
                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                            <p class="text-xl font-bold">Add Time In</p>
                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                        </div>
                                                        <div class="mb-4">
                                                            <form action="{{ route('admin.attendance.employee_attendance.addIn') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time in?');">
                                                                <x-caps-lock-detector />
                                                                @csrf
                                                                    <div class="mb-2">
                                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name: </label>
                                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                                                <option selected value="{{ $selectedEmployeeToShow->id }}">{{ $selectedEmployeeToShow->employee_id }} - {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</option>
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                                    </div>
                                                                <div class="flex mb-4 mt-10 justify-center">
                                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Assuming $attendanceTimeIn is sorted by check_in_time descending -->
                                    @if ($attendanceTimeIn->isNotEmpty())
                                        @php
                                            $currentDate = null;
                                        @endphp
                                        @foreach ($attendanceTimeIn as $attendanceIn)
                                            @php
                                                $checkInTime = strtotime($attendanceIn->check_in_time);
                                                $date = date('m-d-Y', $checkInTime);
                                                $category = date('A', $checkInTime); // AM or PM
                                            @endphp
                                            @if ($date !== $currentDate)
                                                @php
                                                    $currentDate = $date;
                                                    $firstRow = true;
                                                @endphp
                                                <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                                                    <thead class="bg-gray-200 text-black">
                                                        <tr>
                                                            <th class="border border-gray-400 px-3">
                                                                Emp ID
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Date
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Time - In
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Status
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Action
                                                            </th>
                                                            <!-- Add other columns as needed -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                            @endif
                                            <tr class="hover:bg-gray-100">
                                                <td class="text-black border border-gray-400">{{ $attendanceIn->employee->employee_id }}</td>
                                                <td class="text-black border border-gray-400">
                                                    {{ date('m-d-Y (l)', strtotime($attendanceIn->check_in_time)) }}
                                                </td>
                                                <td class="text-black border border-gray-400 uppercase">
                                                    @php
                                                        $status = $attendanceIn->status;
                                                        $display = "";

                                                        if($status === "On Leave"){
                                                            $display = "On Leave";
                                                        } elseif($status === "Absent"){
                                                            $display = "Absent";
                                                        } elseif($status === "Weekend"){
                                                            $display = "Weekend";
                                                        } elseif($status === "awol"){
                                                            $display = "Absent without leave";
                                                        
                                                        } elseif($status === "Official Travel"){
                                                            $display = "Official Travel";
                                                        } else {
                                                            $display = date('g:i:s A', strtotime($attendanceIn->check_in_time));
                                                        }
                                                    @endphp

                                                    @if ($display === "On Leave")
                                                        <span style="color: red;">{{ $display }}</span>
                                                    @elseif ($display === "Official Travel")
                                                        <span style="color: red;" class="text-xs">{{ $display }}</span>
                                                    
                                                    @else
                                                        {{ $display }}
                                                    @endif
                                                </td>
                                                <td class="text-black border border-gray-400 px-1 py-1">
                                                    {{ ucfirst($attendanceIn->modification_status) }}
                                                </td>
                                                <td class="text-black border border-gray-400 px-1 py-1">
                                                    <div class="flex justify-center items-center">
                                                        <div x-data="{ open: false }" class="mr-2">
                                                            <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-[5.5px] rounded hover:bg-blue-700">
                                                                <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Edit
                                                            </a>
                                                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                                <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                                    <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                        <p class="text-xl font-bold">Edit Time In</p>
                                                                        <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                    </div>
                                                                    <div class="mb-4">
                                                                    @if (Auth::user()->hasRole('admin'))
                                                                        <form id="updateTimeInForm" action="{{ route('admin.attendanceIn.edit', $attendanceIn->id) }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to update?');">
                                                                    @else
                                                                        <form id="updateTimeInForm" action="{{ route('admin_staff.attendanceIn.edit', $attendanceIn->id) }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to update?');">
                                                                    @endif
                                                                        <x-caps-lock-detector />
                                                                            @csrf
                                                                            @method('PUT')
                                                                                <div class="mb-2 hidden">
                                                                                    <label for="attendanceIn_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Attendance ID: </label>
                                                                                    <select id="attendanceIn_id" name="attendanceIn_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('attendanceIn_id') is-invalid @enderror">
                                                                                            <option value="{{ $attendanceIn->id }}">{{ $attendanceIn->id }}</option>
                                                                                    </select>
                                                                                    <x-input-error :messages="$errors->get('attendanceIn_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee ID</label>
                                                                                    <input type="text" name="employee_id" id="employee_id" value="{{ $attendanceIn->employee->employee_id }}"  readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_name" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name</label>
                                                                                    <input type="text" name="employee_name" id="employee_name" value="{{ $attendanceIn->employee->employee_lastname }}, {{ $attendanceIn->employee->employee_firstname }}, {{ $attendanceIn->employee->employee_middlename }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_name') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_name')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_date" class="block text-gray-700 text-md font-bold mb-2 text-left">Date of Attendance</label>
                                                                                    <input type="text" name="check_in_date" id="check_in_date" value="{{ date('Y-m-d (l)', strtotime($attendanceIn->check_in_time)) }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('check_in_date')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_time" class="block text-gray-700 text-md font-bold mb-2 text-left">Time In</label>
                                                                                    
                                                                                    <!-- Hidden input for the date part -->
                                                                                    <input type="hidden" name="check_in_time_date" id="check_in_time_date"
                                                                                        value="{{ $attendanceIn->check_in_time ? date('Y-m-d', strtotime($attendanceIn->check_in_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_date') is-invalid @enderror"
                                                                                        autofocus>
                                                                                    
                                                                                    <!-- Visible input for time part with AM/PM formatting -->
                                                                                    <!-- <input type="time" name="check_in_time_time" id="check_in_time_time"
                                                                                        value="{{ $attendanceIn->check_in_time ? date('h:i:s A', strtotime($attendanceIn->check_in_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_time') is-invalid @enderror"
                                                                                        placeholder="hh:mm:ss AM/PM" required autofocus> -->
                                                                                    <input type="time" name="check_in_time_time" id="check_in_time_time"
                                                                                        value="{{ $attendanceIn->check_in_time ? date('H:i', strtotime($attendanceIn->check_in_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_time') is-invalid @enderror"
                                                                                        required autofocus>
                                                                                    
                                                                                    <!-- Error message container -->
                                                                                    <p id="time_error" class="text-red-500 text-sm mt-2 hidden">Invalid time input. Please ensure the hour is between 1-12, and minutes and seconds are between 0-59.</p>
                                                                                    
                                                                                    <x-input-error :messages="$errors->get('check_in_time_time')" class="mt-2" />
                                                                                </div>
                                                                            <div class="flex mb-4 mt-10 justify-center">
                                                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                                    Save Changes
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('admin.attendance.employee_attendance.deleteTimeIn', $attendanceIn->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this time in?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 text-white text-sm px-2 py-1 rounded hover:bg-red-700">
                                                                <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                
                                                <!-- Add other columns as needed -->
                                            </tr>
                                            @if ($loop->last)
                                                    </tbody>
                                                </table>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-center mt-8">No Time In records found.</p>
                                    @endif
                                    <div class="text-center font-bold uppercase">{{ $attendanceTimeIn->links() }}</div>
                                </div>
                                
                                <div class="w-[49%]">
                                    <div class="flex justify-center mb-2 mt-2">
                                        <h3 class="text-center uppercase font-bold">Time Out &nbsp;</h3> | &nbsp;
                                        <div class="flex justify-center items-center space-x-2">
                                            <div x-data="{ open: false }">
                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Add Time Out
                                                </a>
                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                            <p class="text-xl font-bold">Add Time Out</p>
                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                        </div>
                                                        <div class="mb-4">
                                                            <form action="{{ route('admin.attendance.employee_attendance.addOut') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time out?');">
                                                                <x-caps-lock-detector />
                                                                @csrf
                                                                    <div class="mb-2">
                                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name: </label>
                                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                                                <option selected value="{{ $selectedEmployeeToShow->id }}">{{ $selectedEmployeeToShow->employee_id }} - {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</option>
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                                    </div>
                                                                <div class="flex mb-4 mt-10 justify-center">
                                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($attendanceTimeOut->isNotEmpty())
                                        @php
                                            $currentDate = null;
                                            $firstRow = true;
                                        @endphp
                                        @foreach ($attendanceTimeOut as $attendanceOut)
                                            @php
                                                $checkOutTime = strtotime($attendanceOut->check_out_time);
                                                $date = date('m-d-Y', $checkOutTime);
                                                $isFirstRow = ($date !== $currentDate);
                                                $category = $isFirstRow ? 'AM' : date('A', $checkOutTime);
                                            @endphp
                                            @if ($isFirstRow)
                                                @if ($loop->index > 0)
                                                    </tbody></table>
                                                @endif
                                                <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                                                    <thead class="bg-gray-200 text-black">
                                                        <tr>
                                                            <th class="border border-gray-400 px-3">
                                                                Emp ID
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Date
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Time - Out
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Status
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Action
                                                            </th>
                                                            <!-- Add other columns as needed -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                @php
                                                    $currentDate = $date;
                                                @endphp
                                            @endif
                                            <tr class="hover:bg-gray-100">
                                                <td class="text-black border border-gray-400">{{ $attendanceOut->employee->employee_id }}</td>
                                                <td class="text-black border border-gray-400">
                                                    {{ date('m-d-Y (l)', $checkOutTime) }}
                                                </td>
                                                
                                                <td class="text-black border border-gray-400 uppercase">
                                                    <!-- {{ date('g:i:s A', $checkOutTime) }} -->
                                                    @php
                                                        $status = $attendanceOut->status;
                                                        $display = "";

                                                        if($status === "On Leave"){
                                                            $display = "On Leave";
                                                        } elseif($status === "Absent"){
                                                            $display = "Absent";
                                                        } elseif($status === "Weekend"){
                                                            $display = "Weekend";
                                                        } elseif($status === "awol"){
                                                            $display = "Absent without leave";
                                                        } elseif($status === "Official Travel"){
                                                            $display = "Official Travel";
                                                        } else {
                                                            $display = date('g:i:s A', strtotime($attendanceOut->check_out_time));
                                                        }
                                                    @endphp

                                                    @if ($display === "On Leave")
                                                        <span style="color: red;">{{ $display }}</span>
                                                    @elseif ($display === "Official Travel")
                                                        <span style="color: red;" class="text-xs">{{ $display }}</span>
                                                    @else
                                                        {{ $display }}
                                                    @endif
                                                </td>
                                                <td class="text-black border border-gray-400">
                                                    {{ ucfirst($attendanceOut->modification_status) }}
                                                </td>
                                                <td class="text-black border border-gray-400 px-1 py-1">
                                                    <div class="flex justify-center items-center space-x-2">
                                                        <div x-data="{ open: false }">
                                                            <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                                <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Edit
                                                            </a>
                                                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                                <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                                    <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                        <p class="text-xl font-bold">Edit Time Out</p>
                                                                        <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <form id="updateTimeOutForm" action="{{ route('admin.attendanceOut.edit', $attendanceOut->id) }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to update?');">
                                                                            <x-caps-lock-detector />
                                                                            @csrf
                                                                            @method('PUT')
                                                                                <div class="mb-2 hidden">
                                                                                    <label for="attendanceOut_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Attendance ID: </label>
                                                                                    <select id="attendanceOut_id" name="attendanceOut_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('attendanceIn_id') is-invalid @enderror">
                                                                                            <option value="{{ $attendanceOut->id }}">{{ $attendanceOut->id }}</option>
                                                                                    </select>
                                                                                    <x-input-error :messages="$errors->get('attendanceOut_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee ID</label>
                                                                                    <input type="text" name="employee_id" id="employee_id" value="{{ $attendanceOut->employee->employee_id }}"  readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_name" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name</label>
                                                                                    <input type="text" name="employee_name" id="employee_name" value="{{ $attendanceOut->employee->employee_lastname }}, {{ $attendanceOut->employee->employee_firstname }}, {{ $attendanceOut->employee->employee_middlename }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_name') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_name')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_date" class="block text-gray-700 text-md font-bold mb-2 text-left">Date of Attendance</label>
                                                                                    <input type="text" name="check_in_date" id="check_in_date" value="{{ date('Y-m-d (l)', strtotime($attendanceOut->check_out_time)) }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('check_in_date')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_time" class="block text-gray-700 text-md font-bold mb-2 text-left">Time Out</label>
                                                                                    
                                                                                    <!-- Hidden input for the date part -->
                                                                                    <input type="hidden" name="check_out_time_date" id="check_in_time_date"
                                                                                        value="{{ $attendanceOut->check_out_time ? date('Y-m-d', strtotime($attendanceOut->check_out_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_date') is-invalid @enderror"
                                                                                        autofocus>
                                                                                    <x-input-error :messages="$errors->get('check_out_time_date')" class="mt-2" />

                                                                                    <input type="time" name="check_out_time_time" id="check_in_time_time"
                                                                                        value="{{ $attendanceOut->check_out_time ? date('H:i', strtotime($attendanceOut->check_out_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_time') is-invalid @enderror"
                                                                                        required autofocus>
                                                                                    
                                                                                    <!-- Error message container -->
                                                                                    <p id="time_error" class="text-red-500 text-sm mt-2 hidden">Invalid time input. Please ensure the hour is between 1-12, and minutes and seconds are between 0-59.</p>
                                                                                    
                                                                                    <x-input-error :messages="$errors->get('check_out_time_time')" class="mt-2" />
                                                                                </div>
                                                                            <div class="flex mb-4 mt-10 justify-center">
                                                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                                    Save Changes
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('admin.attendance.employee_attendance.deleteTimeOut', $attendanceOut->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this time out?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 text-white text-sm px-2 py-1 rounded hover:bg-red-700">
                                                                <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <!-- Add other columns as needed -->
                                            </tr>
                                            @if ($loop->last)
                                                </tbody></table>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-center mt-8">No Time Out records found.</p>
                                    @endif
                                    <div class="text-center font-bold uppercase">{{ $attendanceTimeOut->links() }}</div>
                                </div>

                            </div>
                        </div>
                        
                        <div x-show="tab === 'computed-hours'" class="w-full">
                            <!-- Table for Computed Working Hours -->

                            <div class="w-full">
                                <h3 class="text-center text-lg font-semibold uppercase mb-2 mt-6">Calculation of Work Hours</h3>
                                <div class="flex justify-between">
                                    <p><text class="text-red-500">Note: </text> To assess time-in and time out duration, click working hour to verify.</p>
                                    <p><text class="text-red-500">Note: </text> Dates that are missing or excluded may be weekends or holidays.</p>
                                </div>
                                <table class="table-auto min-w-full text-center text-xs mb-4 divide-y divide-gray-200">
                                    <thead class="bg-gray-200 text-black">
                                        <tr>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Date</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase" >Time In</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Time Out</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Late AM | PM</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Late</th>
                                            <!-- <th class="border border-gray-400 px-2 py-1">PM Late</th> -->
                                            <th class="border border-gray-400 px-2 py-1 uppercase">UnderTime AM | PM</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Undertime</th>
                                            <!-- <th class="border border-gray-400 px-2 py-1">PM UnderTime</th> -->
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours AM & PM</th>
                                            <!-- <th class="border border-gray-400 px-2 py-1">Total PM Hours</th> -->
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours Rendered</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Deduction (late + undertime)</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Absent</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Required Hours</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupedAttendance = [];

                                            // Group check-in times
                                            foreach ($attendanceTimeIn as $attendanceIn) {
                                                $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                                                $employeeId = $attendanceIn->employee->employee_id;
                                                $status = $attendanceIn->status;

                                                if (!isset($groupedAttendance[$employeeId])) {
                                                    $groupedAttendance[$employeeId] = [];
                                                }

                                                if (!isset($groupedAttendance[$employeeId][$date])) {
                                                    $groupedAttendance[$employeeId][$date] = [
                                                        'date' => date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)),
                                                        'check_ins' => [],
                                                        'check_outs' => [],
                                                        'status' => $status,
                                                    ];
                                                }

                                                $groupedAttendance[$employeeId][$date]['check_ins'][] = date('g:i:s A', strtotime($attendanceIn->check_in_time));
                                            }

                                            // Group check-out times
                                            foreach ($attendanceTimeOut as $attendanceOut) {
                                                $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                                                $employeeId = $attendanceOut->employee->employee_id;
                                                $status = $attendanceOut->status;

                                                if (!isset($groupedAttendance[$employeeId])) {
                                                    $groupedAttendance[$employeeId] = [];
                                                }

                                                if (!isset($groupedAttendance[$employeeId][$date])) {
                                                    $groupedAttendance[$employeeId][$date] = [
                                                        'date' => date('m-d-Y, (l)', strtotime($attendanceOut->check_out_time)),
                                                        'check_ins' => [],
                                                        'check_outs' => [],
                                                        'status' => $status,
                                                    ];
                                                }

                                                $groupedAttendance[$employeeId][$date]['check_outs'][] = date('g:i:s A', strtotime($attendanceOut->check_out_time));
                                            }
                                        @endphp
                                        @foreach ($attendanceData as $attendance)
                                            @php
                                                $workedDate = date('Y-m-d', strtotime($attendance->worked_date));
                                            @endphp
                                        <tr class="hover:border hover:bg-gray-200">
                                            <td class="text-black border border-gray-400 px-2 py-1 font-bold">{{ date('M d, Y (D)', strtotime($attendance->worked_date)) }}</td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-28">
                                                @foreach ($groupedAttendance as $employeeId => $dates)
                                                    @foreach ($dates as $date => $attendance1)
                                                        @if ($date === $workedDate)
                                                            {{-- Handle 1st check-in --}}
                                                            @if (!empty($attendance1['check_ins'][0]))
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                <text class="text-red-500">1ST TIME IN:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $firstCheckIn = $attendance1['check_ins'][0];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($firstCheckIn)) === '00:00:00' || empty($firstCheckIn))
                                                                    <text class="text-red-500">No 1st Check-In</text>
                                                                @else
                                                                    {{ $firstCheckIn }}
                                                                @endif
                                                            @else
                                                                <p class="text-red-500">No 1st Check-In</p>
                                                            @endif

                                                            {{-- Handle 2nd check-in --}}
                                                            @if (!empty($attendance1['check_ins'][1]))
                                                                <br><br>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                <text class="text-blue-500">2ND TIME IN:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $secondCheckIn = $attendance1['check_ins'][1];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($secondCheckIn)) === '00:00:00' || empty($secondCheckIn))
                                                                    <text class="text-red-500">No 2nd Check-In</text>
                                                                @else
                                                                    {{ $secondCheckIn }}
                                                                @endif
                                                            @else
                                                                <p class="mt-10 text-red-500">No 2nd Check-In</p>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-32">
                                                @foreach ($groupedAttendance as $employeeId => $dates)
                                                    @foreach ($dates as $date => $attendance1)
                                                        @if ($date === $workedDate)
                                                            {{-- Handle 1st check-out --}}
                                                            @if (!empty($attendance1['check_outs'][0]))
                                                                <hr style="border: none; border-top: 1px solid #000;">
                                                                <text class="text-red-500">1ST TIME OUT:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $firstCheckOut = $attendance1['check_outs'][0];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($firstCheckOut)) === '00:00:00')
                                                                    <text class="text-red-500">NO TIME OUT</text>
                                                                @else
                                                                    {{ $firstCheckOut }}
                                                                @endif
                                                            @else
                                                                <p class="text-red-500">No 1st Check-Out</p>
                                                            @endif

                                                            {{-- Handle 2nd check-out --}}
                                                            @if (!empty($attendance1['check_outs'][1]))
                                                                <br><br>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                <text class="text-blue-500">2ND TIME OUT:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $secondCheckOut = $attendance1['check_outs'][1];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($secondCheckOut)) === '00:00:00')
                                                                    <text class="text-red-500">NO TIME OUT</text>
                                                                @else
                                                                    {{ $secondCheckOut }}
                                                                @endif
                                                            @else
                                                                <p class="mt-10 text-red-500">No 2nd Check-Out</p>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                <!-- THIS IS PM AND AM LATE DURATION -->
                                                @php
                                                    // Calculate late duration in minutes for AM
                                                    $lateDurationInMinutesAM = $attendance->late_duration;

                                                    // Calculate late hours, minutes, and seconds for AM
                                                    $lateHoursAM = intdiv($lateDurationInMinutesAM, 60);
                                                    $lateMinutesAM = $lateDurationInMinutesAM % 60;
                                                    $lateSecondsAM = ($lateDurationInMinutesAM - floor($lateDurationInMinutesAM)) * 60;

                                                    // Round seconds to avoid precision issues for AM
                                                    $lateSecondsAM = round($lateSecondsAM);

                                                    // Format the late duration string for AM
                                                    $lateDurationFormattedAM = ($lateHoursAM > 0 ? "{$lateHoursAM} hr " : '') 
                                                                            . ($lateMinutesAM > 0 ? "{$lateMinutesAM} min " : '')
                                                                            . ($lateSecondsAM > 0 ? "{$lateSecondsAM} sec" : '');

                                                    // If the formatted string is empty for AM, ensure we show "0"
                                                    $lateDurationFormattedAM = $lateDurationFormattedAM ?: '0 sec';

                                                    // Calculate late duration in minutes for PM
                                                    $lateDurationInMinutesPM = $attendance->late_durationPM;

                                                    // Calculate late hours, minutes, and seconds for PM
                                                    $lateHoursPM = intdiv($lateDurationInMinutesPM, 60);
                                                    $lateMinutesPM = $lateDurationInMinutesPM % 60;
                                                    $lateSecondsPM = ($lateDurationInMinutesPM - floor($lateDurationInMinutesPM)) * 60;

                                                    // Round seconds to avoid precision issues for PM
                                                    $lateSecondsPM = round($lateSecondsPM);

                                                    // Format the late duration string for PM
                                                    $lateDurationFormattedPM = ($lateHoursPM > 0 ? "{$lateHoursPM} hr " : '') 
                                                                            . ($lateMinutesPM > 0 ? "{$lateMinutesPM} min " : '')
                                                                            . ($lateSecondsPM > 0 ? "{$lateSecondsPM} sec" : '');

                                                    // If the formatted string is empty for PM, ensure we show "0"
                                                    $lateDurationFormattedPM = $lateDurationFormattedPM ?: '0 sec';
                                                @endphp

                                                @if (!empty($lateDurationInMinutesAM) && !empty($lateDurationInMinutesPM))
                                                    <div class="mt-2" >
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM LATE:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $lateDurationFormattedAM }}
                                                    </div>

                                                    <div class="mt-4">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM LATE</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $lateDurationFormattedPM }}
                                                    </div>
                                                @elseif (!empty($lateDurationInMinutesAM))
                                                    <div class="-mt-6">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM LATE:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <br>
                                                        {{ $lateDurationFormattedAM }}
                                                    </div>
                                                @elseif (!empty($lateDurationInMinutesPM))
                                                    <div class="mt-1">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM LATE:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <br>
                                                        {{ $lateDurationFormattedPM }}
                                                    </div>
                                                @else
                                                    <p>No Late</p>
                                                @endif
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                @php
                                                    // Total late time in minutes as a decimal
                                                    $totalLateMinutesDecimal = $attendance->total_late;

                                                    // Convert decimal minutes to total hours, minutes, and seconds
                                                    $totalLateHours = intdiv($totalLateMinutesDecimal, 60); // Total hours
                                                    $remainingMinutes = floor($totalLateMinutesDecimal % 60); // Remaining minutes
                                                    $totalLateSeconds = round(($totalLateMinutesDecimal - floor($totalLateMinutesDecimal)) * 60); // Total seconds

                                                    // Format the duration string
                                                    if ($totalLateMinutesDecimal > 0) {
                                                        $totalLateDurationFormatted = 
                                                            ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                            ($remainingMinutes > 0 ? "{$remainingMinutes} mins " : '0 mins ') .
                                                            ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                    } else {
                                                        $totalLateDurationFormatted = '0';
                                                    }
                                                @endphp

                                                {{ $totalLateDurationFormatted }}
                                            </td>
                                            <td class="text-black border border-gray-400 p-2 w-[134px]">
                                                @php
                                                    // Calculate undertime in minutes for AM
                                                    $undertimeInMinutesAM = $attendance->undertimeAM;

                                                    // Convert minutes to total seconds for AM
                                                    $undertimeInSecondsAM = $undertimeInMinutesAM * 60;

                                                    // Convert total seconds to hours, minutes, and seconds for AM
                                                    $undertimeHoursAM = intdiv($undertimeInSecondsAM, 3600); // Total hours
                                                    $remainingSecondsAM = $undertimeInSecondsAM % 3600; // Remaining seconds after hours
                                                    $undertimeMinutesAM = intdiv($remainingSecondsAM, 60); // Total minutes
                                                    $undertimeSecondsAM = $remainingSecondsAM % 60; // Remaining seconds after minutes

                                                    // Format the undertime string for AM
                                                    $undertimeFormattedAM = 
                                                        ($undertimeHoursAM > 0 ? "{$undertimeHoursAM} hr " : '') .
                                                        ($undertimeMinutesAM > 0 ? "{$undertimeMinutesAM} min " : '0 min ') .
                                                        ($undertimeSecondsAM > 0 ? "{$undertimeSecondsAM} sec" : '0 sec');

                                                    // Calculate undertime in minutes for PM
                                                    $undertimeInMinutesPM = $attendance->undertimePM;

                                                    // Convert minutes to total seconds for PM
                                                    $undertimeInSecondsPM = $undertimeInMinutesPM * 60;

                                                    // Convert total seconds to hours, minutes, and seconds for PM
                                                    $undertimeHoursPM = intdiv($undertimeInSecondsPM, 3600); // Total hours
                                                    $remainingSecondsPM = $undertimeInSecondsPM % 3600; // Remaining seconds after hours
                                                    $undertimeMinutesPM = intdiv($remainingSecondsPM, 60); // Total minutes
                                                    $undertimeSecondsPM = $remainingSecondsPM % 60; // Remaining seconds after minutes

                                                    // Format the undertime string for PM
                                                    $undertimeFormattedPM = 
                                                        ($undertimeHoursPM > 0 ? "{$undertimeHoursPM} hr " : '') .
                                                        ($undertimeMinutesPM > 0 ? "{$undertimeMinutesPM} min " : '0 min ') .
                                                        ($undertimeSecondsPM > 0 ? "{$undertimeSecondsPM} sec" : '0 sec');
                                                @endphp

                                                @if (!empty($undertimeInMinutesAM) && !empty($undertimeInMinutesPM))
                                                    <div class="">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $undertimeFormattedAM }}
                                                    </div>

                                                    <div class="mt-4">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $undertimeFormattedPM }}
                                                    </div>
                                                    <!-- <table class="p-0 w-full m-0">
                                                        <tr class="border border-red-500 h-full">
                                                            <td >
                                                                <div class="mt-3 ">
                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                    <span class="text-red-500">AM UNDERTIME:</span>
                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                    {{ $undertimeFormattedAM }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border border-red-500">
                                                            <td>
                                                                <div class="mt-4">
                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                    <span class="text-blue-500">PM UNDERTIME:</span>
                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                    {{ $undertimeFormattedPM }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table> -->

                                                @elseif (!empty($undertimeInMinutesAM))
                                                    <div>
                                                        <text class="text-red-500">AM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        {{ $undertimeFormattedAM }}
                                                    </div>
                                                @elseif (!empty($undertimeInMinutesPM))
                                                    <div class="mt-1">
                                                        <text class="text-blue-500">PM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        {{ $undertimeFormattedPM }}
                                                    </div>
                                                @else
                                                    <p>No Undertime</p>
                                                @endif
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1">
                                                <!-- Undertime Area Total -->
                                                @php
                                                    $am = $attendance->undertimeAM;
                                                    $pm = $attendance->undertimePM;
                                                    $totalUndertimeInMinutes = $am + $pm;

                                                    if ($totalUndertimeInMinutes > 0) {
                                                        // Convert total minutes to total seconds
                                                        $totalUndertimeInSeconds = $totalUndertimeInMinutes * 60;

                                                        // Convert total seconds to hours, minutes, and seconds
                                                        $totalLateHours = intdiv($totalUndertimeInSeconds, 3600); // Total hours
                                                        $remainingSeconds = $totalUndertimeInSeconds % 3600; // Remaining seconds after hours
                                                        $totalLateMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                        $totalLateSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                        // Format the duration string
                                                        $totalLateDurationFormatted = 
                                                            ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                            ($totalLateMinutes > 0 ? "{$totalLateMinutes} mins " : '0 mins ') .
                                                            ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                    } else {
                                                        $totalLateDurationFormatted = '0';
                                                    }
                                                @endphp

                                                {{ $totalLateDurationFormatted }}
                                            </td>
                                            
                                            <td class="text-black border border-gray-400 px-3 py-2 w-40">
                                                @php
                                                    // Total hours worked in AM shift
                                                    $totalHoursAM = floor($attendance->hours_workedAM);
                                                    $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                    $totalSecondsAM = ($totalMinutesAM - floor($totalMinutesAM)) * 60;
                                                    $totalMinutesAM = floor($totalMinutesAM);

                                                    $finalHoursAM = $totalHoursAM;
                                                    $roundedMinutesAM = round($totalMinutesAM + ($totalSecondsAM / 60));
                                                    $finalSecondsAM = round($totalSecondsAM % 60);

                                                    if ($finalSecondsAM >= 59) {
                                                        $finalSecondsAM = 0;
                                                        $roundedMinutesAM += 1;
                                                    } else {
                                                        $finalSecondsAM = 0;
                                                    }

                                                    if ($roundedMinutesAM >= 59) {
                                                        $roundedMinutesAM = 0;
                                                        $finalHoursAM += 1;
                                                    }

                                                    $finalMinutesAM = $roundedMinutesAM;

                                                    // Total hours worked in PM shift
                                                    $totalHoursPM = floor($attendance->hours_workedPM);
                                                    $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                    $totalSecondsPM = ($totalMinutesPM - floor($totalMinutesPM)) * 60;
                                                    $totalMinutesPM = floor($totalMinutesPM);

                                                    $finalHoursPM = $totalHoursPM + floor($totalMinutesPM / 60);
                                                    $finalMinutesPM = $totalMinutesPM % 60;
                                                    $finalSecondsPM = round($totalSecondsPM);

                                                    if ($finalSecondsPM == 60) {
                                                        $finalSecondsPM = 0;
                                                        $finalMinutesPM += 1;
                                                    }

                                                    if ($finalMinutesPM >= 60) {
                                                        $finalMinutesPM = 0;
                                                        $finalHoursPM += 1;
                                                    }
                                                @endphp

                                                @if ($attendance->hours_workedAM > 0 || $attendance->hours_workedPM > 0)
                                                    <div class="mt-2">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM WORKED:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $finalHoursAM }} hr/s. {{ $finalMinutesAM }} min. {{ $finalSecondsAM }} sec.
                                                    </div>

                                                    <div class="mt-4">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM WORKED:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $finalHoursPM }} hrs. {{ $finalMinutesPM }} min. {{ $finalSecondsPM }} sec.
                                                    </div>
                                                @else
                                                    <p>0</p>
                                                @endif
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 font-bold w-32">
                                                @php
                                                    // Total hours worked in decimal format
                                                    $totalHoursWorked = $attendance->total_hours_worked;
                                                    
                                                    // Calculate hours and minutes
                                                    $totalHours = floor($totalHoursWorked);
                                                    $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                    
                                                    // Calculate the final hours, minutes, and seconds
                                                    $finalMinutes = floor($totalMinutes);
                                                    $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                    $finalSeconds = round($totalSeconds);
                                                    
                                                    // Handle case where seconds is 60
                                                    if ($finalSeconds == 60) {
                                                        $finalSeconds = 0;
                                                        $finalMinutes += 1;
                                                    }
                                                    
                                                    // Handle case where minutes exceed 59
                                                    if ($finalMinutes >= 60) {
                                                        $finalMinutes = 0;
                                                        $totalHours += 1;
                                                    }

                                                    // Format the duration string
                                                    if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                        $totalHoursWorkedFormatted = '0';
                                                    } else {
                                                        $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                    }
                                                @endphp

                                                {{ $totalHoursWorkedFormatted }}

                                                        
                                            </td>
                                            <td class="text-black border border-gray-400 px-3 py-2">
                                                    <!-- total deduction -->
                                                @php
                                                    

                                                    $totalHoursWorked = $attendance->total_hours_worked;

                                                    if($totalHoursWorked == 0) {
                                                        

                                                        $am = $attendance->undertimeAM;
                                                        $pm = $attendance->undertimePM;
                                                        $totalUndertimeInMinutes = $am + $pm;

                                                        $undertimeHours = floor($totalUndertimeInMinutes / 60);
                                                        $undertimeMinutes = $totalUndertimeInMinutes % 60;
                                                        $undertimeSeconds = round(($totalUndertimeInMinutes * 60) % 60);

                                                        if($totalUndertimeInMinutes > 0){
                                                            $totalDurationFormatted = "{$undertimeHours} hr/s, {$undertimeMinutes} min/s, {$undertimeSeconds} sec";
                                                        } else{
                                                            $totalDurationFormatted = 0;
                                                        }
                                                    }
                                                    else {

                                                        // Total late time in minutes as a decimal
                                                        $totalLateMinutesDecimal = $attendance->total_late;

                                                        // Total undertime in minutes
                                                        $am = $attendance->undertimeAM;
                                                        $pm = $attendance->undertimePM;
                                                        $totalUndertimeInMinutes = $am + $pm;
                                                        
                                                        
                                                        // Combine late and undertime in minutes
                                                        $totalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                        // Convert total minutes to total seconds
                                                        $totalSeconds = $totalMinutes * 60;

                                                        // Convert total seconds to hours, minutes, and seconds
                                                        $totalHours = intdiv($totalSeconds, 3600); // Total hours
                                                        $remainingSeconds = $totalSeconds % 3600; // Remaining seconds after hours
                                                        $totalMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                        $totalSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                        // Format the duration string for total deduction
                                                        if ($totalMinutes > 0 || $totalLateMinutesDecimal > 0 || $totalUndertimeInMinutes > 0) {
                                                            $totalDurationFormatted = 
                                                                ($totalHours > 0 ? "{$totalHours} hr/s, " : '') .
                                                                ($totalMinutes > 0 ? "{$totalMinutes} min/s, " : '0 min/s ') .
                                                                ($totalSeconds > 0 ? "{$totalSeconds} sec" : '0 sec');
                                                        } else {
                                                            $totalDurationFormatted = '0';
                                                        }

                                                        // Total hours worked in decimal format
                                                        $totalHoursWorked = $attendance->total_hours_worked;
                                                        
                                                        // Calculate hours and minutes
                                                        $totalHours = floor($totalHoursWorked);
                                                        $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                        
                                                        // Calculate the final hours, minutes, and seconds
                                                        $finalMinutes = floor($totalMinutes);
                                                        $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                        $finalSeconds = round($totalSeconds);
                                                        
                                                        // Handle case where seconds is 60
                                                        if ($finalSeconds == 60) {
                                                            $finalSeconds = 0;
                                                            $finalMinutes += 1;
                                                        }
                                                        
                                                        // Handle case where minutes exceed 59
                                                        if ($finalMinutes >= 60) {
                                                            $finalMinutes = 0;
                                                            $totalHours += 1;
                                                        }

                                                        // Format the duration string for total hours worked
                                                        if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                            $totalHoursWorkedFormatted = 'No total hours';
                                                        } else {
                                                            $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                        }

                                                        // Use hours_perDay if totalHoursWorkedFormatted is 'No total hours'
                                                        if ($totalHoursWorkedFormatted === 'No total hours') {
                                                            $hoursPerDay = $attendance->hours_perDay;
                                                            $hours = floor($hoursPerDay);
                                                            $minutes = floor(($hoursPerDay - $hours) * 60);
                                                            $seconds = round((((($hoursPerDay - $hours) * 60) - $minutes) * 60));
                                                            
                                                            $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                            $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                            $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                            $totalDurationFormatted = "{$formattedHours}, {$formattedMinutes}, {$formattedSeconds}";
                                                        }
                                                    }
                                                @endphp
                                                {{ $totalDurationFormatted }}

                                            </td>
                                            <td class="text-black border border-gray-400 px-3 py-2">
                                                @php

                                                    $totalHours = $attendance->hours_perDay;
                                                    $hours = floor($totalHours);
                                                    $minutes = floor(($totalHours - $hours) * 60);
                                                    $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                    // Round minutes if seconds are 59
                                                    if ($seconds >= 59) {
                                                        $minutes += 1;
                                                        $seconds = 0;
                                                    }

                                                    // Format the result based on hours, minutes, and seconds
                                                    if ($hours === 0 && $minutes === 0 && $seconds === 0) {
                                                        $formattedTime = '0';
                                                    } elseif ($hours === 0 && $minutes === 0) {
                                                        $formattedTime = '0 sec';
                                                    } elseif ($hours === 0 && $seconds === 0) {
                                                        $formattedTime = "{$minutes} min";
                                                    } elseif ($hours === 0) {
                                                        $formattedTime = "{$minutes} min, {$seconds} sec";
                                                    } elseif ($minutes === 0 && $seconds === 0) {
                                                        $formattedTime = "{$hours} hr/s";
                                                    } elseif ($minutes === 0) {
                                                        $formattedTime = "{$hours} hr, {$seconds} sec";
                                                    } elseif ($seconds === 0) {
                                                        $formattedTime = "{$hours} hr, {$minutes} min";
                                                    } else {
                                                        $formattedTime = "{$hours} hr, {$minutes} min, {$seconds} sec";
                                                    }

                                                    // Time period 1 (formatted time)
                                                    $totalHours1 = $attendance->hours_perDay;
                                                    $hours1 = floor($totalHours1);
                                                    $minutes1 = floor(($totalHours1 - $hours1) * 60);
                                                    $seconds1 = round((((($totalHours1 - $hours1) * 60) - $minutes1) * 60));

                                                    // Convert time period 1 to total seconds
                                                    $timePeriod1Seconds = ($hours1 * 3600) + ($minutes1 * 60) + $seconds1;

                                                    // Time period 2 (total worked time)
                                                    $totalHoursWorked = $attendance->total_hours_worked;
                                                    $workedHours = floor($totalHoursWorked);
                                                    $totalMinutes = ($totalHoursWorked - $workedHours) * 60;
                                                    $workedMinutes = floor($totalMinutes);
                                                    $workedSeconds = round(($totalMinutes - $workedMinutes) * 60);

                                                    // Total late and undertime in minutes
                                                    $totalLateMinutesDecimal = $attendance->total_late;
                                                    $am = $attendance->undertimeAM;
                                                    $pm = $attendance->undertimePM;
                                                    $totalUndertimeInMinutes = $am + $pm;

                                                    // Combine late and undertime in minutes
                                                    $totalAdditionalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                    // Convert time period 2 to total seconds
                                                    $timePeriod2Seconds = ($workedHours * 3600) + ($workedMinutes * 60) + $workedSeconds + ($totalAdditionalMinutes * 60);

                                                    // Calculate the difference in seconds
                                                    $differenceSeconds = $timePeriod1Seconds - $timePeriod2Seconds;

                                                    // Convert the difference back to hours, minutes, and seconds
                                                    $differenceHours = floor($differenceSeconds / 3600);
                                                    $differenceMinutes = floor(($differenceSeconds % 3600) / 60);
                                                    $differenceSeconds = $differenceSeconds % 60;

                                                    $formattedDifferenceHours = $differenceHours > 0 ? "{$differenceHours} hr/s" : '';
                                                    $formattedDifferenceMinutes = $differenceMinutes > 0 ? "{$differenceMinutes} min" : '';
                                                    $formattedDifferenceSeconds = $differenceSeconds > 0 ? "{$differenceSeconds} sec" : '';

                                                    // Combine formatted parts for difference
                                                    $formattedDifference = trim("{$formattedDifferenceHours} {$formattedDifferenceMinutes} {$formattedDifferenceSeconds}");
                                                    $formattedDifference = empty($formattedDifference) ? '0' : $formattedDifference;
                                                @endphp

                                                {{ $formattedDifference}}
                                            </td>
                                            <td class="text-black border border-gray-400 text-xs">
                                                <!-- this is total hour required -->
                                                <!-- {{ $attendance->hours_perDay }} hr/s -->
                                                @php
                                                    // Assuming $attendance->hours_perDay is in decimal format
                                                    $totalHours = $attendance->hours_perDay;
                                                    $hours = floor($totalHours);
                                                    $minutes = floor(($totalHours - $hours) * 60);
                                                    $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                    $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                    $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                    $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                    $result = "{$formattedHours}, {$formattedMinutes}";
                                                @endphp

                                                {{ $result }}
                                            </td>
                                            <td class="text-red-500 border uppercase border-gray-400 text-xs font-bold w-32">
                                                @php
                                                    $lateDurationAM = $attendance->late_duration;
                                                    $lateDurationPM = $attendance->late_durationPM;
                                                    $am = $attendance->undertimeAM ?? 0;
                                                    $pm = $attendance->undertimePM ?? 0;

                                                    $totalHoursAM = floor($attendance->hours_workedAM);
                                                    $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                    $totalHoursPM = floor($attendance->hours_workedPM);
                                                    $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                    $totalHours = $totalHoursAM + $totalHoursPM;
                                                    $totalMinutes = $totalMinutesAM + $totalMinutesPM;
                                                    $modify_status = $attendance->modify_status;
                                                    $firstCheckInStatus = $attendance->firstCheckInStatus;
                                                    $firstCheckOutStatus = $attendance->firstCheckOutStatus;
                                                    $secondCheckInStatus = $attendance->secondCheckInStatus;
                                                    $secondCheckOutStatus = $attendance->secondCheckOutStatus;

                                                    $remarkss = '';

                                                    if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "Absent"
                                                    ) {
                                                        $remarkss = 'Absent';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "On Leave"
                                                    ) {
                                                        $remarkss = 'Leave';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM > 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        //$modify_status == "On Leave"
                                                        $firstCheckInStatus == "On Leave" &&
                                                        $firstCheckOutStatus == "On Leave" && 
                                                        $secondCheckInStatus == "On Leave" &&
                                                        $secondCheckOutStatus == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave Whole Day';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM > 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "Holiday"
                                                    ) {
                                                        $remarkss = 'Holiday';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM > 0 &&
                                                        $totalMinutesAM > 0 ||
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0) &&
                                                        $modify_status == "Official Travel"
                                                    ) {
                                                        $remarkss = 'Official Travel';
                                                    }
                                                    
                                                    
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM > 0 &&
                                                        $totalMinutesAM > 0 ||
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0) &&
                                                        $modify_status == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave';
                                                    }
                                                        else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 ||
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM > 0) &&
                                                        $modify_status == "Official Travel"
                                                    ) {
                                                        $remarkss = 'Official Travel';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 ||
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM > 0) &&
                                                        $modify_status == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave';
                                                    }
                                                    else if (
                                                        $firstCheckInStatus == "On Leave" &&
                                                        $firstCheckOutStatus == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave AM - Present PM';
                                                    }
                                                    else if (
                                                        $secondCheckInStatus == "On Leave" &&
                                                        $secondCheckOutStatus == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave PM - Present AM';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM > 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        //$modify_status == "Official Travel"
                                                        $firstCheckInStatus == "Official Travel" &&
                                                        $firstCheckOutStatus == "Official Travel" && 
                                                        $secondCheckInStatus == "Official Travel" &&
                                                        $secondCheckOutStatus == "Official Travel"
                                                    ) {
                                                        $remarkss = 'On Official Travel Whole Day';
                                                    }
                                                    else if (
                                                        $firstCheckInStatus == "Official Travel" &&
                                                        $firstCheckOutStatus == "Official Travel"
                                                    ) {
                                                        $remarkss = 'On Official Travel AM - Present PM';
                                                    }
                                                    else if (
                                                        $secondCheckInStatus == "Official Travel" &&
                                                        $secondCheckOutStatus == "Official Travel"
                                                    ) {
                                                        $remarkss = 'On Official Travel PM - Present AM';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        ($am == 0 || $am > 0) &&
                                                        ($pm == 0 || $pm > 0)  &&
                                                        $totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "On-campus"
                                                    ) {
                                                        $remarkss = 'Invalid Attendance';
                                                    }
                                                    else {
                                                            if ($totalHoursPM == null && $totalMinutesPM == null && $totalHoursAM == 0 && $totalMinutesAM == 0 && $modify_status == "Weekend") {
                                                                    $remarkss = "Absent";
                                                                } 
                                                                else if ($totalHoursAM == null && $totalMinutesAM == null && $modify_status == "On-campus") {
                                                                    $remarkss = "Present";
                                                                } 
                                                            else if ($totalHoursAM == 0 && $totalMinutesAM == 0) {
                                                                $remarkss = "Present Afternoon, Absent Morning";
                                                            }
                                                            else if ($totalHoursPM == 0 && $totalMinutesPM == 0) {
                                                                $remarkss = "Present Morning, Absent Afternoon";
                                                            }
                                                            else {
                                                                if ($lateDurationAM > 0 && $lateDurationPM > 0) {
                                                                    $remarkss = 'Present - Late AM & PM';
                                                                } elseif ($lateDurationAM > 0) {
                                                                    $remarkss = 'Present - Late AM';
                                                                } elseif ($lateDurationPM > 0) {
                                                                    $remarkss = 'Present - Late PM';
                                                                }
                                                                    else {
                                                                    $remarkss = "Present";
                                                                }
                                                            }

                                                        $undertimeRemark = '';
                                                        if ($am > 0) {
                                                            $undertimeRemark .= 'Undertime AM';
                                                        }
                                                        if ($pm > 0) {
                                                            if (!empty($undertimeRemark)) {
                                                                $undertimeRemark .= ' & PM';
                                                            } else {
                                                                $undertimeRemark .= 'Undertime PM';
                                                            }
                                                        }
                                                        if (!empty($undertimeRemark)) {
                                                            $remarkss .= ' - ' . $undertimeRemark;
                                                        }
                                                    }
                                                @endphp

                                                    @if ($remarkss === 'Present')
                                                        <span class="text-black">{{ $remarkss }}</span>
                                                    @else
                                                        <span class="text-red-500">{{ $remarkss }}</span>
                                                    @endif
                                                </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- end -->
                        </div>
                        <div  x-show="tab === 'modify_date'" class="w-full">
                            <!-- Table for Computed Working Hours -->
                            <div class="w-full">
                                <div x-data="{ activeTab: 'form2' }" class="w-[50%] mb-4 mx-auto mt-8">
                                    <!-- Tabs -->
                                    <div class="flex justify-between mb-4">
                                        <button @click="activeTab = 'form1'" :class="{'bg-blue-500 text-white': activeTab === 'form1'}" class="w-[48%] py-2 px-4 rounded-md text-center border border-black">
                                            For Half Day Leave
                                        </button>
                                        <button @click="activeTab = 'form2'" :class="{'bg-blue-500 text-white': activeTab === 'form2'}" class="w-[48%] py-2 px-4 rounded-md text-center border border-black">
                                            For Full Day Leave
                                        </button>
                                    </div>

                                    <!-- Form 1 -->
                                    <div x-show="activeTab === 'form1'" class="w-full">
                                        <form action="{{ route('admin.attendance.modify.halfDay') }}" method="POST" class="w-full">
                                            <x-caps-lock-detector />
                                            @csrf

                                            <br>
                                            <p class="text-[14px]">
                                                <text class="text-red-500">Note:</text> The half day leave is the same as a full day leave if the working hours are not half day.
                                            </p>
                                            <br>
                                            <div class="mb-2 hidden">
                                                <label for="selected-date" class="block mb-2 text-left">Employee:</label>
                                                <input type="text" name="employee_id" value="{{ $selectedEmployeeToShow->id }}" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full max-w-md">
                                            </div>

                                            <div x-data="{ selectedDate: '', dayOfWeekNumber: '' }" class="mb-2">
                                                <label for="selected-date" class="block mb-2 text-left">Select a Date:</label>
                                                <input 
                                                    type="date" 
                                                    id="selected-date" 
                                                    name="selected_date" 
                                                    class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full"
                                                    x-model="selectedDate"
                                                    @change="dayOfWeekNumber = new Date(selectedDate).getDay()"
                                                    wire:model="selected_date"
                                                >

                                                <div class="">
                                                    <label for="day-of-week" class="block mb-2 text-left">Day of the Week:</label>
                                                    <input 
                                                        type="text" 
                                                        id="day-of-week" 
                                                        name="day_of_week" 
                                                        class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full"
                                                        x-model="dayOfWeekNumber"
                                                        readonly
                                                        wire:model="dayOfTheWeek"
                                                    >
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-md font-bold mb-2 text-left">Select Shift:</label>
                                                <div class="flex space-x-4">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" id="am_shift" name="am_shift" class="mr-2" onchange="updateStatus()">
                                                        <label for="am_shift">AM Shift</label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input type="checkbox" id="pm_shift" name="pm_shift" class="mr-2" onchange="updateStatus()">
                                                        <label for="pm_shift">PM Shift</label>
                                                    </div>
                                                </div>
                                                <!-- Hidden inputs for checkboxes -->
                                                <input type="hidden" name="am_shift" id="am_shift_hidden" value="0">
                                                <input type="hidden" name="pm_shift" id="pm_shift_hidden" value="0">
                                            </div>

                                            <!-- Status Dropdown -->
                                            <div class="mb-4">
                                                <label for="status" class="block text-gray-700 text-md font-bold mb-2 text-left">Status:</label>
                                                <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" required>
                                                    <option value="On Leave">On Leave</option>
                                                    <option value="Official Travel">Official Travel</option>
                                                    <!-- <option value="Sick Leave">Sick Leave</option> -->
                                                    <!-- Add other options as needed -->
                                                </select>
                                            </div>

                                            <div class="flex mb-4 mt-10 justify-center">
                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Form 2 -->
                                    <div x-show="activeTab === 'form2'" class="w-full">
                                        <form action="{{ route('admin.attendance.modify') }}" method="POST" class="w-[78%] mx-auto">
                                            <x-caps-lock-detector />
                                            @csrf
                                            <br>
                                            <p class="text-[14px]">
                                                <text class="text-red-500">Note:</text> Full Day leave is based on the set working hour of employee's department.
                                            </p>
                                            <br>
                                            <div class="mb-2 hidden">
                                                <label for="selected-date" class="block mb-2 text-left">Employee:</label>
                                                <input type="text" name="employee_id" value="{{ $selectedEmployeeToShow->id }}" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full max-w-md">
                                            </div>
                                            <div class="mb-2">
                                                <label for="selected-date" class="block mb-2 text-left">Select a Date:</label>
                                                <input type="date" id="selected-date" name="selected_date" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full">
                                            </div>
                                            <div class="mb-2">
                                                <label for="school_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Status: </label>
                                                <select id="school_id" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" required>
                                                    <option value="">Select Status</option>
                                                    <option value="On Leave">On Leave</option>
                                                    <option value="Official Travel">Official Travel</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                            </div>
                                            <div class="flex mb-4 mt-10 justify-center">
                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                    Save Leave
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end -->
                        </div>
                        <div x-show="tab === 'reports'" class="w-full">
                            <div class="flex justify-center mt-8 w-full">
                                <div class="flex   justify-center w-full">
                                    <div class="flex flex-col w-full">
                                        <!-- <p>Overall Total Hours: {{ round($overallTotalHours,2) }}</p> -->
                                            @php
                                            // Group data by employee_id
                                            $employees = [];

                                            foreach ($attendanceData as $attendance) {

                                                
                                                $employeeId = $attendance->employee_id;
                                                $check = $attendance->check_in_time;
                                                if (!isset($employees[$employeeId])) {
                                                    $employees[$employeeId] = [
                                                        'totalHours' => 0,
                                                        'total_hours_worked' => 0,
                                                        'hours_late_overall' => 0,
                                                        'hours_undertime_overall' => 0,
                                                        'employee_idd' => $attendance->employee_idd,
                                                        'uniqueDays' => []
                                                    ];
                                                }

                                                // Accumulate totals for each employee
                                                $employees[$employeeId]['totalHours'] += $attendance->hours_perDay;
                                                $employees[$employeeId]['total_hours_worked'] += $attendance->total_hours_worked;
                                                $employees[$employeeId]['hours_late_overall'] += $attendance->hours_late_overall; // Replace with actual late hours field
                                                $employees[$employeeId]['hours_undertime_overall'] += $attendance->hours_undertime_overall; // Replace with actual undertime field
                                            
                                                $date = \Illuminate\Support\Carbon::parse($attendance->check_in_time)->toDateString();
                                                $employees[$employeeId]['uniqueDays'][$date] = true;
                                            }
                                        @endphp
                                        @foreach($employees as $employeeId => $employeeData)
                                            @php
                                                // Total hours
                                                $totalSeconds = $employeeData['totalHours'] * 3600;
                                                $hours = floor($totalSeconds / 3600);
                                                $minutes = floor(($totalSeconds % 3600) / 60);
                                                $seconds = $totalSeconds % 60;


                                                $totalSecondsWorked = $employeeData['total_hours_worked'] * 3600;
                                                $overallhours = floor($totalSecondsWorked / 3600);
                                                $overallminutes = floor(($totalSecondsWorked % 3600) / 60);
                                                $overallseconds = $totalSecondsWorked % 60;

                                                if ($overallseconds == 59) {
                                                    $overallminutes += 1;
                                                    $overallseconds = 0;
                                                }

                                                // If minutes exceed 59, convert to hours
                                                if ($overallminutes >= 60) {
                                                    $overallhours += floor($overallminutes / 60);
                                                    $overallminutes = $overallminutes % 60;
                                                }

                                                $formattedTimeWorked = 
                                                    ($overallhours > 0 ? "{$overallhours} hr/s, " : '0 hr/s, ') .
                                                    ($overallminutes > 0 ? "{$overallminutes} min/s " : '0 min/s, ') .
                                                    ($overallseconds > 0 ? "{$overallseconds} sec" : '0 sec');

                                                // Total late
                                                $totalSecondsM = $employeeData['hours_late_overall'] * 3600;
                                                $hoursM = floor($totalSecondsM / 3600);
                                                $minutesM = floor(($totalSecondsM % 3600) / 60);
                                                $secondsM = $totalSecondsM % 60;

                                                $totalLateSeconds = $totalSeconds - $totalSecondsWorked;
                                                $totalLateHours = floor($totalLateSeconds / 3600);
                                                $totalLateMinutes = floor(($totalLateSeconds % 3600) / 60);
                                                $totalLateSeconds = $totalLateSeconds % 60;

                                                $latee = 
                                                    ($totalLateHours > 0 ? "{$totalLateHours} hr/s, " : '0 hr/s, ') .
                                                    ($totalLateMinutes > 0 ? "{$totalLateMinutes} min/s " : '0 min/s, ') .
                                                    ($totalLateSeconds > 0 ? "{$totalLateSeconds} sec" : '0 sec');
                                                

                                                // Total undertime
                                                $undertimeInSeconds = $employeeData['hours_undertime_overall'] * 60;
                                                $undertimeHours = intdiv($undertimeInSeconds, 3600);
                                                $remainingSeconds = $undertimeInSeconds % 3600;
                                                $undertimeMinutes = intdiv($remainingSeconds, 60);
                                                $undertimeSeconds = $remainingSeconds % 60;

                                                // Format the undertime
                                                $undertimeFormatted = 
                                                    ($undertimeHours > 0 ? "{$undertimeHours} hr/s, " : '0 hr/s, ') .
                                                    ($undertimeMinutes > 0 ? "{$undertimeMinutes} min/s " : '0 min/s, ') .
                                                    ($undertimeSeconds > 0 ? "{$undertimeSeconds} sec" : '0 sec');

                                                // Format total hours
                                                //$totalFormatted = 
                                                    // ($hours > 0 ? "{$hours} hr/s, " : '0 hr/s, ') .
                                                    // ($minutes > 0 ? "{$minutes} min/s " : '0 min/s, ');

                                                $totalFormatted = '';

                                                if ($hours > 0) {
                                                    $totalFormatted .= "{$hours} hr/s";
                                                }

                                                if ($minutes > 0) {
                                                    $totalFormatted .= ($hours > 0 ? ', ' : '') . "{$minutes} min/s";
                                                } elseif ($hours > 0) {
                                                    // Include a comma if hours are present but no minutes
                                                    $totalFormatted .= '';
                                                } else {
                                                    // If there are no hours and no minutes, ensure the format is '0 hr/s, 0 min/s'
                                                    $totalFormatted = '0 hr/s, 0 min/s';
                                                }

                                                // Add seconds if needed
                                                $totalFormatted .= $seconds > 0 ? " {$seconds} sec" : '';

                                                // Format total late
                                                $lateFormatted = 
                                                    ($hoursM > 0 ? "{$hoursM} hr/s, " : '0 hr/s, ') .
                                                    ($minutesM > 0 ? "{$minutesM} min/s " : '0 min/s, ') .
                                                    ($secondsM > 0 ? "{$secondsM} sec" : '0 sec');

                                                    $attendanceDaysCount = count($employeeData['uniqueDays']);

                                                    $rtotal = $totalSecondsWorked + $totalSecondsM + $undertimeInSeconds;
                                                $absentSecondss = $totalSeconds - $rtotal;

                                                // Convert absence seconds to hours, minutes, and seconds
                                                $absentHours = floor($absentSecondss / 3600);
                                                $remainingSeconds = $absentSecondss % 3600;
                                                $absentMinutes = floor($remainingSeconds / 60);
                                                $absentSeconds = $remainingSeconds % 60;

                                                // If seconds are 59, round up the minutes
                                                if ($absentSeconds == 59) {
                                                    $absentMinutes += 1;
                                                    $absentSeconds = 0;
                                                }

                                                // If minutes are 60, convert them to an hour
                                                if ($absentMinutes == 60) {
                                                    $absentHours += 1;
                                                    $absentMinutes = 0;
                                                }

                                                // Format the absence time
                                                $absentFormatted = 
                                                    ($absentHours > 0 ? "{$absentHours} hr/s" : '') .
                                                    (($absentHours > 0 && $absentMinutes > 0) ? ", " : '') . 
                                                    ($absentMinutes > 0 ? "{$absentMinutes} min/s" : '') .
                                                    (($absentMinutes > 0 && $absentSeconds > 0) ? " " : '') . 
                                                    ($absentSeconds > 0 ? "{$absentSeconds} sec" : ($absentHours <= 0 && $absentMinutes <= 0 ? ' 0 ' : ''));

                                                // Add the comma and space between the values
                                                $absentFormatted = trim($absentFormatted, ', ');



                                                $finalDeduction = $totalSecondsM + $undertimeInSeconds + $absentSecondss;

                                                // Calculate final hour deduction
                                                $finalHourDeductionHours = floor($finalDeduction / 3600);
                                                $finalDeductionRemainingSeconds = $finalDeduction % 3600;
                                                $finalHourDeductionMinutes = floor($finalDeductionRemainingSeconds / 60);
                                                $finalHourDeductionSeconds = $finalDeductionRemainingSeconds % 60;

                                                // Format final hour deduction
                                                $finalHourDeductionFormatted = 
                                                    ($finalHourDeductionHours > 0 ? "{$finalHourDeductionHours} hr/s, " : '0 hr/s, ') .
                                                    ($finalHourDeductionMinutes > 0 ? "{$finalHourDeductionMinutes} min/s " : '0 min/s, ') .
                                                    ($finalHourDeductionSeconds > 0 ? "{$finalHourDeductionSeconds} sec" : '0 sec');
                                                

                                            @endphp

                                            <div x-data="{ loading: false, open: {{ session()->has('success') ? 'true' : 'false' }} }"
                                                x-init="() => {
                                                    if (open) {
                                                        loading = false;
                                                        setTimeout(() => open = false, 3000); // Automatically close the modal after 3 seconds
                                                    }
                                                }"
                                                @export-success.window="loading = false; open = true">

                                                
                                                <div x-cloak x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
                                                        <h2 class="text-xl font-semibold mb-4">Download Info</h2>
                                                        <p>{{ session()->get('success') }}</p>
                                                        <div class="flex justify-end mt-4">
                                                            <button @click="open = false" class="px-4 py-2 bg-blue-500 text-white rounded">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Loader -->
                                                <div x-show="loading && !open" 
                                                    x-transition:enter="transition ease-out duration-300" 
                                                    x-transition:enter-start="opacity-0" 
                                                    x-transition:enter-end="opacity-100" 
                                                    x-transition:leave="transition ease-in duration-200" 
                                                    x-transition:leave-start="opacity-100" 
                                                    x-transition:leave-end="opacity-0"
                                                    class="fixed inset-0 flex flex-col items-center justify-center bg-gray-800 bg-opacity-50 z-50">
                                                    
                                                    <!-- Container for the loader and text -->
                                                    <div class="flex flex-col items-center">
                                                        <!-- Rotating Spinner Loader -->
                                                        <div class="w-16 h-16 border-4 border-t-4 border-white border-solid rounded-full animate-spin"></div>
                                                        
                                                        <!-- Optional Loading Text -->
                                                        
                                                    </div>
                                                </div>

                                                @if ($startDate && $endDate)
                                                    <p>Selected Date Range:</p>
                                                    <div class="flex justify-between -mt-4">
                                                        <p class="py-4 text-red-500">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} &nbsp; to &nbsp; {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
                                                        <!-- <div class="">
                                                            <button 
                                                                x-on:click="loading = true" 
                                                                wire:click="generateExcel" 
                                                                wire:loading.attr="disabled" 
                                                                wire:loading.class="cursor-wait"
                                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                <i class="fa-solid fa-file"></i> Export Employee Attendance Report for Selected Date to Excel
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                @else
                                                    <p>Selected Date Range:</p>
                                                    <div class="flex justify-between -mt-4">
                                                        <p class="py-4">No selected Date</p>
                                                        <!-- <div class="">
                                                            <button 
                                                                x-on:click="loading = true" 
                                                                wire:click="generateExcel" 
                                                                wire:loading.attr="disabled" 
                                                                wire:loading.class="cursor-wait"
                                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                <i class="fa-solid fa-file"></i> Export All Dept. Employees Attendance Report to Excel
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                @endif
                                            </div>
                                            <table class="border border-black h-full" cellpadding="2">
                                                <tr class="text-sm">
                                                    <th class="border border-black text-center">Duty Hours To Be Rendered</th>
                                                    <th class="border border-black text-center">Total Time Rendered</th>
                                                    <th class="border border-black text-center">Total Time Deduction (late + undertime + absent)</th>
                                                    <th class="border border-black text-center">Total Late</th>
                                                    <th class="border border-black text-center">Total Undertime</th>
                                                    <th class="border border-black text-center">Total Absent</th>
                                                    <th class="border border-black text-center">Action</th>
                                                </tr>
                                                    <tr class="border border-black text-sm  hover:border hover:bg-gray-200">
                                                    <!-- <td class="text-black border border-black text-center">
                                                        {{ $employeeData['employee_idd'] }}
                                                    </td> -->
                                                    <td class="text-black border border-black">{{ $totalFormatted }}  from ({{ $attendanceDaysCount }} days worked)</td>
                                                    <td class="text-black border border-black">{{$formattedTimeWorked}}</td>
                                                    <td class="text-black border border-black">{{ $finalHourDeductionFormatted }}</td>
                                                    <td class="text-black border border-black">{{ $lateFormatted }}</td>
                                                    <td class="text-black border border-black">{{ $undertimeFormatted }}</td>
                                                    <td class="text-black border border-black text-center">{{ $absentFormatted }}</td>
                                                    <td class="text-black border border-black">
                                                        <div class="flex justify-center items-center space-x-2 p-2 z-50">
                                                            <div x-data="{ open: false }">
                                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> View Calculation
                                                                </a>
                                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                                                    <div @click.away="open = false" class=" w-[80%] max-h-[90vh] bg-white p-6 rounded-lg shadow-lg  mx-auto overflow-y-auto">
                                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                            <p class="text-xl font-bold">Detailed Calculation of Work Hours (<text class="text-red-500 text-sm">Dates that are missing or excluded may be weekends or holidays</text>)</p>
                                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                        </div>
                                                                        <div class="w-full">
                                                                            <!-- <h3 class="text-center text-lg font-semibold uppercase mb-2 mt-6">Calculation of Work Hours</h3> -->
                                                                                <p> Employee: <text class="text-red-500">{{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text></p>
                                                                            @if ($startDate && $endDate)
                                                                                <p>Selected Date Range:</p>
                                                                                <div class="flex justify-between -mt-4">
                                                                                    
                                                                                    <p class="py-4 text-red-500">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} &nbsp; to &nbsp; {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
                                                                                    <!-- <div class="">
                                                                                        <button wire:click="generateExcel" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Export to Excel
                                                                                        </button>
                                                                                        <button wire:click="generatePDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Generate DTR | PDF
                                                                                        </button>
                                                                                    </div> -->
                                                                                </div>
                                                                            @else
                                                                                <p>Selected Date Range:</p>
                                                                                <div class="flex justify-between -mt-4">
                                                                                    
                                                                                    <p class="py-4">Start Date: None selected &nbsp;&nbsp;End Date: None selected</p>
                                                                                    <!-- <div class="">
                                                                                        <button wire:click="generateExcel" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Export to Excel
                                                                                        </button>
                                                                                        <button wire:click="generatePDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Generate DTR | PDF
                                                                                        </button>
                                                                                    </div> -->
                                                                                </div>
                                                                            @endif
                                                                            <table class="table-auto min-w-full text-center text-xs mb-4 divide-y divide-gray-200">
                                                                                <thead class="bg-gray-200 text-black">
                                                                                    <tr>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Date</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase" >Time In</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Time Out</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Late AM | PM</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Late</th>
                                                                                        <!-- <th class="border border-gray-400 px-2 py-1">PM Late</th> -->
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">UnderTime AM | PM</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Undertime</th>
                                                                                        <!-- <th class="border border-gray-400 px-2 py-1">PM UnderTime</th> -->
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours AM & PM</th>
                                                                                        <!-- <th class="border border-gray-400 px-2 py-1">Total PM Hours</th> -->
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours Rendered</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Deduction (late + undertime)</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Absent</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Required Hours</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Remarks</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @php
                                                                                        $groupedAttendance = [];

                                                                                        // Group check-in times
                                                                                        foreach ($attendanceTimeIn as $attendanceIn) {
                                                                                            $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                                                                                            $employeeId = $attendanceIn->employee->employee_id;
                                                                                            $status = $attendanceIn->status;

                                                                                            if (!isset($groupedAttendance[$employeeId])) {
                                                                                                $groupedAttendance[$employeeId] = [];
                                                                                            }

                                                                                            if (!isset($groupedAttendance[$employeeId][$date])) {
                                                                                                $groupedAttendance[$employeeId][$date] = [
                                                                                                    'date' => date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)),
                                                                                                    'check_ins' => [],
                                                                                                    'check_outs' => [],
                                                                                                    'status' => $status,
                                                                                                ];
                                                                                            }

                                                                                            $groupedAttendance[$employeeId][$date]['check_ins'][] = date('g:i:s A', strtotime($attendanceIn->check_in_time));
                                                                                        }

                                                                                        // Group check-out times
                                                                                        foreach ($attendanceTimeOut as $attendanceOut) {
                                                                                            $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                                                                                            $employeeId = $attendanceOut->employee->employee_id;
                                                                                            $status = $attendanceOut->status;

                                                                                            if (!isset($groupedAttendance[$employeeId])) {
                                                                                                $groupedAttendance[$employeeId] = [];
                                                                                            }

                                                                                            if (!isset($groupedAttendance[$employeeId][$date])) {
                                                                                                $groupedAttendance[$employeeId][$date] = [
                                                                                                    'date' => date('m-d-Y, (l)', strtotime($attendanceOut->check_out_time)),
                                                                                                    'check_ins' => [],
                                                                                                    'check_outs' => [],
                                                                                                    'status' => $status,
                                                                                                ];
                                                                                            }

                                                                                            $groupedAttendance[$employeeId][$date]['check_outs'][] = date('g:i:s A', strtotime($attendanceOut->check_out_time));
                                                                                        }
                                                                                    @endphp
                                                                                    @foreach ($attendanceData as $attendance)
                                                                                        @php
                                                                                            $workedDate = date('Y-m-d', strtotime($attendance->worked_date));
                                                                                        @endphp
                                                                                    <tr class="hover:border hover:bg-gray-200">
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 font-bold">{{ date('M d, Y (D)', strtotime($attendance->worked_date)) }}</td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-28">
                                                                                            @foreach ($groupedAttendance as $employeeId => $dates)
                                                                                                @foreach ($dates as $date => $attendance1)
                                                                                                    @if ($date === $workedDate)
                                                                                                        {{-- Handle 1st check-in --}}
                                                                                                        @if (!empty($attendance1['check_ins'][0]))
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            <text class="text-red-500">1ST TIME IN:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $firstCheckIn = $attendance1['check_ins'][0];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($firstCheckIn)) === '00:00:00' || empty($firstCheckIn))
                                                                                                                <text class="text-red-500">No 1st Check-In</text>
                                                                                                            @else
                                                                                                                {{ $firstCheckIn }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="text-red-500">No 1st Check-In</p>
                                                                                                        @endif

                                                                                                        {{-- Handle 2nd check-in --}}
                                                                                                        @if (!empty($attendance1['check_ins'][1]))
                                                                                                            <br><br>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            <text class="text-blue-500">2ND TIME IN:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $secondCheckIn = $attendance1['check_ins'][1];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($secondCheckIn)) === '00:00:00' || empty($secondCheckIn))
                                                                                                                <text class="text-red-500">No 2nd Check-In</text>
                                                                                                            @else
                                                                                                                {{ $secondCheckIn }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="mt-10 text-red-500">No 2nd Check-In</p>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endforeach
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-32">
                                                                                            @foreach ($groupedAttendance as $employeeId => $dates)
                                                                                                @foreach ($dates as $date => $attendance1)
                                                                                                    @if ($date === $workedDate)
                                                                                                        {{-- Handle 1st check-out --}}
                                                                                                        @if (!empty($attendance1['check_outs'][0]))
                                                                                                            <hr style="border: none; border-top: 1px solid #000;">
                                                                                                            <text class="text-red-500">1ST TIME OUT:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $firstCheckOut = $attendance1['check_outs'][0];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($firstCheckOut)) === '00:00:00')
                                                                                                                <text class="text-red-500">NO TIME OUT</text>
                                                                                                            @else
                                                                                                                {{ $firstCheckOut }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="text-red-500">No 1st Check-Out</p>
                                                                                                        @endif

                                                                                                        {{-- Handle 2nd check-out --}}
                                                                                                        @if (!empty($attendance1['check_outs'][1]))
                                                                                                            <br><br>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            <text class="text-blue-500">2ND TIME OUT:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $secondCheckOut = $attendance1['check_outs'][1];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($secondCheckOut)) === '00:00:00')
                                                                                                                <text class="text-red-500">NO TIME OUT</text>
                                                                                                            @else
                                                                                                                {{ $secondCheckOut }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="mt-10 text-red-500">No 2nd Check-Out</p>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endforeach
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                                                            <!-- THIS IS PM AND AM LATE DURATION -->
                                                                                            @php
                                                                                                // Calculate late duration in minutes for AM
                                                                                                $lateDurationInMinutesAM = $attendance->late_duration;

                                                                                                // Calculate late hours, minutes, and seconds for AM
                                                                                                $lateHoursAM = intdiv($lateDurationInMinutesAM, 60);
                                                                                                $lateMinutesAM = $lateDurationInMinutesAM % 60;
                                                                                                $lateSecondsAM = ($lateDurationInMinutesAM - floor($lateDurationInMinutesAM)) * 60;

                                                                                                // Round seconds to avoid precision issues for AM
                                                                                                $lateSecondsAM = round($lateSecondsAM);

                                                                                                // Format the late duration string for AM
                                                                                                $lateDurationFormattedAM = ($lateHoursAM > 0 ? "{$lateHoursAM} hr " : '') 
                                                                                                                        . ($lateMinutesAM > 0 ? "{$lateMinutesAM} min " : '')
                                                                                                                        . ($lateSecondsAM > 0 ? "{$lateSecondsAM} sec" : '');

                                                                                                // If the formatted string is empty for AM, ensure we show "0"
                                                                                                $lateDurationFormattedAM = $lateDurationFormattedAM ?: '0 sec';

                                                                                                // Calculate late duration in minutes for PM
                                                                                                $lateDurationInMinutesPM = $attendance->late_durationPM;

                                                                                                // Calculate late hours, minutes, and seconds for PM
                                                                                                $lateHoursPM = intdiv($lateDurationInMinutesPM, 60);
                                                                                                $lateMinutesPM = $lateDurationInMinutesPM % 60;
                                                                                                $lateSecondsPM = ($lateDurationInMinutesPM - floor($lateDurationInMinutesPM)) * 60;

                                                                                                // Round seconds to avoid precision issues for PM
                                                                                                $lateSecondsPM = round($lateSecondsPM);

                                                                                                // Format the late duration string for PM
                                                                                                $lateDurationFormattedPM = ($lateHoursPM > 0 ? "{$lateHoursPM} hr " : '') 
                                                                                                                        . ($lateMinutesPM > 0 ? "{$lateMinutesPM} min " : '')
                                                                                                                        . ($lateSecondsPM > 0 ? "{$lateSecondsPM} sec" : '');

                                                                                                // If the formatted string is empty for PM, ensure we show "0"
                                                                                                $lateDurationFormattedPM = $lateDurationFormattedPM ?: '0 sec';
                                                                                            @endphp

                                                                                            @if (!empty($lateDurationInMinutesAM) && !empty($lateDurationInMinutesPM))
                                                                                                <div class="mt-2" >
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM LATE:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $lateDurationFormattedAM }}
                                                                                                </div>

                                                                                                <div class="mt-4">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM LATE</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $lateDurationFormattedPM }}
                                                                                                </div>
                                                                                            @elseif (!empty($lateDurationInMinutesAM))
                                                                                                <div class="-mt-6">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM LATE:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <br>
                                                                                                    {{ $lateDurationFormattedAM }}
                                                                                                </div>
                                                                                            @elseif (!empty($lateDurationInMinutesPM))
                                                                                                <div class="mt-1">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM LATE:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <br>
                                                                                                    {{ $lateDurationFormattedPM }}
                                                                                                </div>
                                                                                            @else
                                                                                                <p>No Late</p>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                                                            @php
                                                                                                // Total late time in minutes as a decimal
                                                                                                $totalLateMinutesDecimal = $attendance->total_late;

                                                                                                // Convert decimal minutes to total hours, minutes, and seconds
                                                                                                $totalLateHours = intdiv($totalLateMinutesDecimal, 60); // Total hours
                                                                                                $remainingMinutes = floor($totalLateMinutesDecimal % 60); // Remaining minutes
                                                                                                $totalLateSeconds = round(($totalLateMinutesDecimal - floor($totalLateMinutesDecimal)) * 60); // Total seconds

                                                                                                // Format the duration string
                                                                                                if ($totalLateMinutesDecimal > 0) {
                                                                                                    $totalLateDurationFormatted = 
                                                                                                        ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                                                                        ($remainingMinutes > 0 ? "{$remainingMinutes} mins " : '0 mins ') .
                                                                                                        ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                                                                } else {
                                                                                                    $totalLateDurationFormatted = '0';
                                                                                                }
                                                                                            @endphp

                                                                                            {{ $totalLateDurationFormatted }}
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 p-2 w-[134px]">
                                                                                            @php
                                                                                                // Calculate undertime in minutes for AM
                                                                                                $undertimeInMinutesAM = $attendance->undertimeAM;

                                                                                                // Convert minutes to total seconds for AM
                                                                                                $undertimeInSecondsAM = $undertimeInMinutesAM * 60;

                                                                                                // Convert total seconds to hours, minutes, and seconds for AM
                                                                                                $undertimeHoursAM = intdiv($undertimeInSecondsAM, 3600); // Total hours
                                                                                                $remainingSecondsAM = $undertimeInSecondsAM % 3600; // Remaining seconds after hours
                                                                                                $undertimeMinutesAM = intdiv($remainingSecondsAM, 60); // Total minutes
                                                                                                $undertimeSecondsAM = $remainingSecondsAM % 60; // Remaining seconds after minutes

                                                                                                // Format the undertime string for AM
                                                                                                $undertimeFormattedAM = 
                                                                                                    ($undertimeHoursAM > 0 ? "{$undertimeHoursAM} hr " : '') .
                                                                                                    ($undertimeMinutesAM > 0 ? "{$undertimeMinutesAM} min " : '0 min ') .
                                                                                                    ($undertimeSecondsAM > 0 ? "{$undertimeSecondsAM} sec" : '0 sec');

                                                                                                // Calculate undertime in minutes for PM
                                                                                                $undertimeInMinutesPM = $attendance->undertimePM;

                                                                                                // Convert minutes to total seconds for PM
                                                                                                $undertimeInSecondsPM = $undertimeInMinutesPM * 60;

                                                                                                // Convert total seconds to hours, minutes, and seconds for PM
                                                                                                $undertimeHoursPM = intdiv($undertimeInSecondsPM, 3600); // Total hours
                                                                                                $remainingSecondsPM = $undertimeInSecondsPM % 3600; // Remaining seconds after hours
                                                                                                $undertimeMinutesPM = intdiv($remainingSecondsPM, 60); // Total minutes
                                                                                                $undertimeSecondsPM = $remainingSecondsPM % 60; // Remaining seconds after minutes

                                                                                                // Format the undertime string for PM
                                                                                                $undertimeFormattedPM = 
                                                                                                    ($undertimeHoursPM > 0 ? "{$undertimeHoursPM} hr " : '') .
                                                                                                    ($undertimeMinutesPM > 0 ? "{$undertimeMinutesPM} min " : '0 min ') .
                                                                                                    ($undertimeSecondsPM > 0 ? "{$undertimeSecondsPM} sec" : '0 sec');
                                                                                            @endphp

                                                                                            @if (!empty($undertimeInMinutesAM) && !empty($undertimeInMinutesPM))
                                                                                                <div class="">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $undertimeFormattedAM }}
                                                                                                </div>

                                                                                                <div class="mt-4">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $undertimeFormattedPM }}
                                                                                                </div>
                                                                                                <!-- <table class="p-0 w-full m-0">
                                                                                                    <tr class="border border-red-500 h-full">
                                                                                                        <td >
                                                                                                            <div class="mt-3 ">
                                                                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                <span class="text-red-500">AM UNDERTIME:</span>
                                                                                                                <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                                {{ $undertimeFormattedAM }}
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr class="border border-red-500">
                                                                                                        <td>
                                                                                                            <div class="mt-4">
                                                                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                <span class="text-blue-500">PM UNDERTIME:</span>
                                                                                                                <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                                {{ $undertimeFormattedPM }}
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table> -->

                                                                                            @elseif (!empty($undertimeInMinutesAM))
                                                                                                <div>
                                                                                                    <text class="text-red-500">AM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    {{ $undertimeFormattedAM }}
                                                                                                </div>
                                                                                            @elseif (!empty($undertimeInMinutesPM))
                                                                                                <div class="mt-1">
                                                                                                    <text class="text-blue-500">PM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    {{ $undertimeFormattedPM }}
                                                                                                </div>
                                                                                            @else
                                                                                                <p>No Undertime</p>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1">
                                                                                            <!-- Undertime Area Total -->
                                                                                            @php
                                                                                                $am = $attendance->undertimeAM;
                                                                                                $pm = $attendance->undertimePM;
                                                                                                $totalUndertimeInMinutes = $am + $pm;

                                                                                                if ($totalUndertimeInMinutes > 0) {
                                                                                                    // Convert total minutes to total seconds
                                                                                                    $totalUndertimeInSeconds = $totalUndertimeInMinutes * 60;

                                                                                                    // Convert total seconds to hours, minutes, and seconds
                                                                                                    $totalLateHours = intdiv($totalUndertimeInSeconds, 3600); // Total hours
                                                                                                    $remainingSeconds = $totalUndertimeInSeconds % 3600; // Remaining seconds after hours
                                                                                                    $totalLateMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                                                                    $totalLateSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                                                                    // Format the duration string
                                                                                                    $totalLateDurationFormatted = 
                                                                                                        ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                                                                        ($totalLateMinutes > 0 ? "{$totalLateMinutes} mins " : '0 mins ') .
                                                                                                        ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                                                                } else {
                                                                                                    $totalLateDurationFormatted = '0';
                                                                                                }
                                                                                            @endphp

                                                                                            {{ $totalLateDurationFormatted }}
                                                                                        </td>
                                                                                        
                                                                                        <td class="text-black border border-gray-400 px-3 py-2 w-40">
                                                                                            @php
                                                                                                // Total hours worked in AM shift
                                                                                                $totalHoursAM = floor($attendance->hours_workedAM);
                                                                                                $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                                                                $totalSecondsAM = ($totalMinutesAM - floor($totalMinutesAM)) * 60;
                                                                                                $totalMinutesAM = floor($totalMinutesAM);

                                                                                                $finalHoursAM = $totalHoursAM;
                                                                                                $roundedMinutesAM = round($totalMinutesAM + ($totalSecondsAM / 60));
                                                                                                $finalSecondsAM = round($totalSecondsAM % 60);

                                                                                                if ($finalSecondsAM >= 59) {
                                                                                                    $finalSecondsAM = 0;
                                                                                                    $roundedMinutesAM += 1;
                                                                                                } else {
                                                                                                    $finalSecondsAM = 0;
                                                                                                }

                                                                                                if ($roundedMinutesAM >= 59) {
                                                                                                    $roundedMinutesAM = 0;
                                                                                                    $finalHoursAM += 1;
                                                                                                }

                                                                                                $finalMinutesAM = $roundedMinutesAM;

                                                                                                // Total hours worked in PM shift
                                                                                                $totalHoursPM = floor($attendance->hours_workedPM);
                                                                                                $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                                                                $totalSecondsPM = ($totalMinutesPM - floor($totalMinutesPM)) * 60;
                                                                                                $totalMinutesPM = floor($totalMinutesPM);

                                                                                                $finalHoursPM = $totalHoursPM + floor($totalMinutesPM / 60);
                                                                                                $finalMinutesPM = $totalMinutesPM % 60;
                                                                                                $finalSecondsPM = round($totalSecondsPM);

                                                                                                if ($finalSecondsPM == 60) {
                                                                                                    $finalSecondsPM = 0;
                                                                                                    $finalMinutesPM += 1;
                                                                                                }

                                                                                                if ($finalMinutesPM >= 60) {
                                                                                                    $finalMinutesPM = 0;
                                                                                                    $finalHoursPM += 1;
                                                                                                }
                                                                                            @endphp

                                                                                            @if ($attendance->hours_workedAM > 0 || $attendance->hours_workedPM > 0)
                                                                                                <div class="mt-2">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM WORKED:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $finalHoursAM }} hr/s. {{ $finalMinutesAM }} min. {{ $finalSecondsAM }} sec.
                                                                                                </div>

                                                                                                <div class="mt-4">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM WORKED:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $finalHoursPM }} hrs. {{ $finalMinutesPM }} min. {{ $finalSecondsPM }} sec.
                                                                                                </div>
                                                                                            @else
                                                                                                <p>0</p>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 font-bold w-32">
                                                                                            @php
                                                                                                // Total hours worked in decimal format
                                                                                                $totalHoursWorked = $attendance->total_hours_worked;
                                                                                                
                                                                                                // Calculate hours and minutes
                                                                                                $totalHours = floor($totalHoursWorked);
                                                                                                $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                                                                
                                                                                                // Calculate the final hours, minutes, and seconds
                                                                                                $finalMinutes = floor($totalMinutes);
                                                                                                $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                                                                $finalSeconds = round($totalSeconds);
                                                                                                
                                                                                                // Handle case where seconds is 60
                                                                                                if ($finalSeconds == 60) {
                                                                                                    $finalSeconds = 0;
                                                                                                    $finalMinutes += 1;
                                                                                                }
                                                                                                
                                                                                                // Handle case where minutes exceed 59
                                                                                                if ($finalMinutes >= 60) {
                                                                                                    $finalMinutes = 0;
                                                                                                    $totalHours += 1;
                                                                                                }

                                                                                                // Format the duration string
                                                                                                if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                                                                    $totalHoursWorkedFormatted = '0';
                                                                                                } else {
                                                                                                    $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                                                                }
                                                                                            @endphp

                                                                                            {{ $totalHoursWorkedFormatted }}

                                                                                                    
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-3 py-2">

                                                                                            @php
                                                                                                // Total late time in minutes as a decimal
                                                                                                $totalLateMinutesDecimal = $attendance->total_late;

                                                                                                // Total undertime in minutes
                                                                                                $am = $attendance->undertimeAM;
                                                                                                $pm = $attendance->undertimePM;
                                                                                                $totalUndertimeInMinutes = $am + $pm;

                                                                                                // Combine late and undertime in minutes
                                                                                                $totalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                                                                // Convert total minutes to total seconds
                                                                                                $totalSeconds = $totalMinutes * 60;

                                                                                                // Convert total seconds to hours, minutes, and seconds
                                                                                                $totalHours = intdiv($totalSeconds, 3600); // Total hours
                                                                                                $remainingSeconds = $totalSeconds % 3600; // Remaining seconds after hours
                                                                                                $totalMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                                                                $totalSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                                                                // Format the duration string for total deduction
                                                                                                if ($totalMinutes > 0 || $totalLateMinutesDecimal > 0 || $totalUndertimeInMinutes > 0) {
                                                                                                    $totalDurationFormatted = 
                                                                                                        ($totalHours > 0 ? "{$totalHours} hr/s, " : '') .
                                                                                                        ($totalMinutes > 0 ? "{$totalMinutes} min/s, " : '0 min/s ') .
                                                                                                        ($totalSeconds > 0 ? "{$totalSeconds} sec" : '0 sec');
                                                                                                } else {
                                                                                                    $totalDurationFormatted = '0';
                                                                                                }

                                                                                                // Total hours worked in decimal format
                                                                                                $totalHoursWorked = $attendance->total_hours_worked;
                                                                                                
                                                                                                // Calculate hours and minutes
                                                                                                $totalHours = floor($totalHoursWorked);
                                                                                                $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                                                                
                                                                                                // Calculate the final hours, minutes, and seconds
                                                                                                $finalMinutes = floor($totalMinutes);
                                                                                                $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                                                                $finalSeconds = round($totalSeconds);
                                                                                                
                                                                                                // Handle case where seconds is 60
                                                                                                if ($finalSeconds == 60) {
                                                                                                    $finalSeconds = 0;
                                                                                                    $finalMinutes += 1;
                                                                                                }
                                                                                                
                                                                                                // Handle case where minutes exceed 59
                                                                                                if ($finalMinutes >= 60) {
                                                                                                    $finalMinutes = 0;
                                                                                                    $totalHours += 1;
                                                                                                }

                                                                                                // Format the duration string for total hours worked
                                                                                                if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                                                                    $totalHoursWorkedFormatted = 'No total hours';
                                                                                                } else {
                                                                                                    $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                                                                }

                                                                                                // Use hours_perDay if totalHoursWorkedFormatted is 'No total hours'
                                                                                                if ($totalHoursWorkedFormatted === 'No total hours') {
                                                                                                    $hoursPerDay = $attendance->hours_perDay;
                                                                                                    $hours = floor($hoursPerDay);
                                                                                                    $minutes = floor(($hoursPerDay - $hours) * 60);
                                                                                                    $seconds = round((((($hoursPerDay - $hours) * 60) - $minutes) * 60));
                                                                                                    
                                                                                                    $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                                                                    $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                                                                    $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                                                                    $totalDurationFormatted = "{$formattedHours}, {$formattedMinutes}, {$formattedSeconds}";
                                                                                                }
                                                                                            @endphp
                                                                                            {{ $totalDurationFormatted }}

                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-3 py-2">
                                                                                            @php

                                                                                            $totalHours = $attendance->hours_perDay;
                                                                                                $hours = floor($totalHours);
                                                                                                $minutes = floor(($totalHours - $hours) * 60);
                                                                                                $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                                                                // Round minutes if seconds are 59
                                                                                                if ($seconds >= 59) {
                                                                                                    $minutes += 1;
                                                                                                    $seconds = 0;
                                                                                                }

                                                                                                // Format the result based on hours, minutes, and seconds
                                                                                                if ($hours === 0 && $minutes === 0 && $seconds === 0) {
                                                                                                    $formattedTime = '0';
                                                                                                } elseif ($hours === 0 && $minutes === 0) {
                                                                                                    $formattedTime = '0 sec';
                                                                                                } elseif ($hours === 0 && $seconds === 0) {
                                                                                                    $formattedTime = "{$minutes} min";
                                                                                                } elseif ($hours === 0) {
                                                                                                    $formattedTime = "{$minutes} min, {$seconds} sec";
                                                                                                } elseif ($minutes === 0 && $seconds === 0) {
                                                                                                    $formattedTime = "{$hours} hr/s";
                                                                                                } elseif ($minutes === 0) {
                                                                                                    $formattedTime = "{$hours} hr, {$seconds} sec";
                                                                                                } elseif ($seconds === 0) {
                                                                                                    $formattedTime = "{$hours} hr, {$minutes} min";
                                                                                                } else {
                                                                                                    $formattedTime = "{$hours} hr, {$minutes} min, {$seconds} sec";
                                                                                                }

                                                                                                // Time period 1 (formatted time)
                                                                                                $totalHours1 = $attendance->hours_perDay;
                                                                                                $hours1 = floor($totalHours1);
                                                                                                $minutes1 = floor(($totalHours1 - $hours1) * 60);
                                                                                                $seconds1 = round((((($totalHours1 - $hours1) * 60) - $minutes1) * 60));

                                                                                                // Convert time period 1 to total seconds
                                                                                                $timePeriod1Seconds = ($hours1 * 3600) + ($minutes1 * 60) + $seconds1;

                                                                                                // Time period 2 (total worked time)
                                                                                                $totalHoursWorked = $attendance->total_hours_worked;
                                                                                                $workedHours = floor($totalHoursWorked);
                                                                                                $totalMinutes = ($totalHoursWorked - $workedHours) * 60;
                                                                                                $workedMinutes = floor($totalMinutes);
                                                                                                $workedSeconds = round(($totalMinutes - $workedMinutes) * 60);

                                                                                                // Total late and undertime in minutes
                                                                                                $totalLateMinutesDecimal = $attendance->total_late;
                                                                                                $am = $attendance->undertimeAM;
                                                                                                $pm = $attendance->undertimePM;
                                                                                                $totalUndertimeInMinutes = $am + $pm;

                                                                                                // Combine late and undertime in minutes
                                                                                                $totalAdditionalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                                                                // Convert time period 2 to total seconds
                                                                                                $timePeriod2Seconds = ($workedHours * 3600) + ($workedMinutes * 60) + $workedSeconds + ($totalAdditionalMinutes * 60);

                                                                                                // Calculate the difference in seconds
                                                                                                $differenceSeconds = $timePeriod1Seconds - $timePeriod2Seconds;

                                                                                                // Convert the difference back to hours, minutes, and seconds
                                                                                                $differenceHours = floor($differenceSeconds / 3600);
                                                                                                $differenceMinutes = floor(($differenceSeconds % 3600) / 60);
                                                                                                $differenceSeconds = $differenceSeconds % 60;

                                                                                                $formattedDifferenceHours = $differenceHours > 0 ? "{$differenceHours} hr/s" : '';
                                                                                                $formattedDifferenceMinutes = $differenceMinutes > 0 ? "{$differenceMinutes} min" : '';
                                                                                                $formattedDifferenceSeconds = $differenceSeconds > 0 ? "{$differenceSeconds} sec" : '';

                                                                                                // Combine formatted parts for difference
                                                                                                $formattedDifference = trim("{$formattedDifferenceHours} {$formattedDifferenceMinutes} {$formattedDifferenceSeconds}");
                                                                                                $formattedDifference = empty($formattedDifference) ? '0' : $formattedDifference;
                                                                                            @endphp

                                                                                            {{ $formattedDifference}}
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 text-xs">
                                                                                            <!-- this is total hour required -->
                                                                                            <!-- {{ $attendance->hours_perDay }} hr/s -->
                                                                                            @php
                                                                                                // Assuming $attendance->hours_perDay is in decimal format
                                                                                                $totalHours = $attendance->hours_perDay;
                                                                                                $hours = floor($totalHours);
                                                                                                $minutes = floor(($totalHours - $hours) * 60);
                                                                                                $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                                                                $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                                                                $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                                                                $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                                                                $result = "{$formattedHours}, {$formattedMinutes}";
                                                                                            @endphp

                                                                                            {{ $result }}
                                                                                        </td>
                                                                                        <td class="text-red-500 border uppercase border-gray-400 text-xs font-bold w-32">
                                                                                            @php
                                                                                                $lateDurationAM = $attendance->late_duration;
                                                                                                $lateDurationPM = $attendance->late_durationPM;
                                                                                                $am = $attendance->undertimeAM ?? 0;
                                                                                                $pm = $attendance->undertimePM ?? 0;

                                                                                                $totalHoursAM = floor($attendance->hours_workedAM);
                                                                                                $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                                                                $totalHoursPM = floor($attendance->hours_workedPM);
                                                                                                $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                                                                $totalHours = $totalHoursAM + $totalHoursPM;
                                                                                                $totalMinutes = $totalMinutesAM + $totalMinutesPM;
                                                                                                $modify_status = $attendance->modify_status;
                                                                                                $firstCheckInStatus = $attendance->firstCheckInStatus;
                                                                                                $firstCheckOutStatus = $attendance->firstCheckOutStatus;
                                                                                                $secondCheckInStatus = $attendance->secondCheckInStatus;
                                                                                                $secondCheckOutStatus = $attendance->secondCheckOutStatus;

                                                                                                $remarkss = '';

                                                                                                if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "Absent"
                                                                                                ) {
                                                                                                    $remarkss = 'Absent';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'Leave';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    //$modify_status == "On Leave"
                                                                                                    $firstCheckInStatus == "On Leave" &&
                                                                                                    $firstCheckOutStatus == "On Leave" && 
                                                                                                    $secondCheckInStatus == "On Leave" &&
                                                                                                    $secondCheckOutStatus == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave Whole Day';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "Holiday"
                                                                                                ) {
                                                                                                    $remarkss = 'Holiday';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM > 0 ||
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0) &&
                                                                                                    $modify_status == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'Official Travel';
                                                                                                }
                                                                                                
                                                                                                
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM > 0 ||
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0) &&
                                                                                                    $modify_status == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 ||
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM > 0) &&
                                                                                                    $modify_status == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'Official Travel';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 ||
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM > 0) &&
                                                                                                    $modify_status == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave';
                                                                                                }
                                                                                                else if (
                                                                                                    $firstCheckInStatus == "On Leave" &&
                                                                                                    $firstCheckOutStatus == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave AM - Present PM';
                                                                                                }
                                                                                                else if (
                                                                                                    $secondCheckInStatus == "On Leave" &&
                                                                                                    $secondCheckOutStatus == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave PM - Present AM';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    //$modify_status == "Official Travel"
                                                                                                    $firstCheckInStatus == "Official Travel" &&
                                                                                                    $firstCheckOutStatus == "Official Travel" && 
                                                                                                    $secondCheckInStatus == "Official Travel" &&
                                                                                                    $secondCheckOutStatus == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'On Official Travel Whole Day';
                                                                                                }
                                                                                                else if (
                                                                                                    $firstCheckInStatus == "Official Travel" &&
                                                                                                    $firstCheckOutStatus == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'On Official Travel AM - Present PM';
                                                                                                }
                                                                                                else if (
                                                                                                    $secondCheckInStatus == "Official Travel" &&
                                                                                                    $secondCheckOutStatus == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'On Official Travel PM - Present AM';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    ($am == 0 || $am > 0) &&
                                                                                                    ($pm == 0 || $pm > 0)  &&
                                                                                                    $totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "On-campus"
                                                                                                ) {
                                                                                                    $remarkss = 'Invalid Attendance';
                                                                                                }
                                                                                                
                                                                                                else {
                                                                                                    if ($totalHoursPM == null && $totalMinutesPM == null && $totalHoursAM == 0 && $totalMinutesAM == 0 && $modify_status == "Weekend") {
                                                                                                        $remarkss = "Absent";
                                                                                                    } 
                                                                                                    else if ($totalHoursAM == null && $totalMinutesAM == null && $modify_status == "On-campus") {
                                                                                                        $remarkss = "Present";
                                                                                                    } 
                                                                                                    else if ($totalHoursAM == 0 && $totalMinutesAM == 0) {
                                                                                                        $remarkss = "Present Afternoon, Absent Morning";
                                                                                                    }
                                                                                                    else if ($totalHoursPM == 0 && $totalMinutesPM == 0) {
                                                                                                        $remarkss = "Present Morning, Absent Afternoon";
                                                                                                    }
                                                                                                    else {
                                                                                                        if ($lateDurationAM > 0 && $lateDurationPM > 0) {
                                                                                                            $remarkss = 'Present - Late AM & PM';
                                                                                                        } elseif ($lateDurationAM > 0) {
                                                                                                            $remarkss = 'Present - Late AM';
                                                                                                        } elseif ($lateDurationPM > 0) {
                                                                                                            $remarkss = 'Present - Late PM';
                                                                                                        }
                                                                                                        else {
                                                                                                            $remarkss = "Present";
                                                                                                        }
                                                                                                    }

                                                                                                    $undertimeRemark = '';
                                                                                                    if ($am > 0) {
                                                                                                        $undertimeRemark .= 'Undertime AM';
                                                                                                    }
                                                                                                    if ($pm > 0) {
                                                                                                        if (!empty($undertimeRemark)) {
                                                                                                            $undertimeRemark .= ' & PM';
                                                                                                        } else {
                                                                                                            $undertimeRemark .= 'Undertime PM';
                                                                                                        }
                                                                                                    }
                                                                                                    if (!empty($undertimeRemark)) {
                                                                                                        $remarkss .= ' - ' . $undertimeRemark;
                                                                                                    }
                                                                                                }
                                                                                            @endphp

                                                                                                @if ($remarkss === 'Present')
                                                                                                    <span class="text-black">{{ $remarkss }}</span>
                                                                                                @else
                                                                                                    <span class="text-red-500">{{ $remarkss }}</span>
                                                                                                @endif
                                                                                            </td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
        
                                                <!-- <tr>
                                                    <td class="border border-black text-red-500">{{ $totalFormatted }}   from ({{ $attendanceDaysCount }} days worked)</td>
                                                    <td class="border border-black text-red-500">{{ $hours }} hr/s, {{ $minutes }} min/s, {{ $seconds }} sec</td>
                                                    <td class="border border-black text-red-500">{{ $finalHourDeductionFormatted }}</td>
                                                    <td class="border border-black text-red-500">{{ $hoursM }} hr/s, {{ $minutesM }} min/s, {{ $secondsM }} sec</td>
                                                    <td class="border border-black text-red-500">{{ $undertimeFormatted }}</td>
                                                    <td class="border border-black text-red-500">{{ $absentFormatted }}</td>
                                                </tr> -->
                                            </table>
                                        @endforeach
                                    </div>                        
                                </div>
                            </div> 
                        </div>
                        <!-- HOLIDAYS  -->
                        <div x-show="tab === 'holidays'" class="w-full">
                            <div class="flex flex-col items-center mt-8 w-full mx-auto">
                                <p class="text-black text-xl text-center mb-2">LIST OF ADDED HOLIDAYS</p>
                                <p class="text-center mb-4">Holiday dates are excluded from calculations and are not included in the attendance or working hour computations.</p>
                                <div class="w-[40%] flex justify-center mb-4">
                                    @if($holidays->isNotEmpty())
                                        <table class="border border-collapse border-1 border-black w-full mb-4">
                                            <thead>
                                                <tr class="border border-collapse border-1 border-black">
                                                    <th class="border border-collapse border-1 border-black p-2">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($holidays as $holiday)
                                                    <tr class="border border-collapse border-1 border-black text-center">
                                                        <td class="border border-collapse border-1 border-black p-2">{{ \Carbon\Carbon::parse($holiday->check_in_date)->format('F j, Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="font-bold text-red-500 mb-4 text-center">No Holiday Dates Confirmed yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>                     
                    </div>
                </div>
                
            @endif
        @else
            @if($employees->isEmpty())
                <p class="text-black text-sm mt-11 mb-4 uppercase text-center">Add Employee first in the department</p>
            @else
                <p class="text-black text-sm mt-11 mb-4 uppercase text-center">No selected Employee</p>
            @endif
        @endif













                                                                                            



     <!-- STAFF AREA -->


    @elseif(Auth::user()->hasRole('admin_staff'))

         @if (session('success'))
            <x-sweetalert type="success" :message="session('success')" />
        @endif

        @if (session('info'))
            <x-sweetalert type="info" :message="session('info')" />
        @endif

        @if (session('error'))
            <x-sweetalert type="error" :message="session('error')" />
        @endif
        <div class="flex justify-between mb-4 sm:-mt-4">
            <div class="font-bold text-md tracking-tight text-md text-black mt-2 uppercase">Employee Attendance Search</div>
        </div>
        <div>
            @if($selectedSchoolDisplay)
                <p class="text-black mt-2 text-sm mb-1">School: 
                    <span class="text-red-500 ml-2 mb-10 font-bold uppercase">{{ $selectedSchoolDisplay }}</span>
                </p>
            @endif
        </div>                                                                                   
        <div class="flex justify-start">
            <div>
                <label for="search" class="block text-sm text-gray-700 font-bold md:mr-4 mb-2 mt-4 truncate uppercase">Search Employees:</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    wire:model.live="search" 
                    class="text-sm shadow appearance-none border rounded text-black leading-tight focus:outline-none focus:shadow-outline md:w-72"
                    placeholder="Enter Employee ID or name..."
                    autofocus
                />

            </div>
            
            <!-- cc -->
                        <!-- Modal -->
            <div x-data="{ open: false }" @keydown.window.escape="open = false" x-cloak>
                <!-- Modal Trigger Button -->
                <div class="flex justify-start mt-6">
                    <button @click="open = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2 mt-5"><i class="fa-solid fa-calendar-days"></i> View Work Details</button>
                    <div class="flex justify-center mb-2 mt-6 ml-4">
                        <div class="flex justify-center items-center space-x-2">
                            <div x-data="{ open: false }">
                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-md px-2 py-2.5 font-bold rounded hover:bg-blue-700">
                                    <i class="fa-solid fa-pen fa-md" style="color: #ffffff;"></i> Add Time In
                                </a>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                            <p class="text-xl font-bold">Add Time In</p>
                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                        </div>
                                        <div class="mb-4">
                                            <form action="{{ route('admin_staff.attendance.employee_attendance.addIn') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time in?');">
                                                <x-caps-lock-detector />
                                                @csrf
                                                    <div class="mb-2">
                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Employee Name: </label>
                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                            @foreach($employees as $employee)
                                                                <option selected value="{{ $employee->id }}">{{ $employee->employee_id }} - {{ $employee->employee_lastname }}, {{ $employee->employee_firstname }} {{ $employee->employee_middlename }}</option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                    </div>
                                                <div class="flex mb-4 mt-10 justify-center">
                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                        Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mb-2 mt-6 ml-4">
                        <div class="flex justify-center items-center space-x-2">
                            <div x-data="{ open: false }">
                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-md px-2 py-2.5 font-bold rounded hover:bg-blue-700">
                                    <i class="fa-solid fa-pen fa-sm" style="color: #ffffff;"></i> Add Time Out
                                </a>
                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                            <p class="text-xl font-bold">Add Time Out</p>
                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2.5 rounded hover:text-red-500">X</a>
                                        </div>
                                        <div class="mb-4">
                                            <form action="{{ route('admin_staff.attendance.employee_attendance.addOut') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time out?');">
                                                <x-caps-lock-detector />
                                                @csrf
                                                    <div class="mb-2">
                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Employee Name: </label>
                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                            @foreach($employees as $employee)
                                                                <option selected value="{{ $employee->id }}">{{ $employee->employee_id }} - {{ $employee->employee_lastname }}, {{ $employee->employee_firstname }} {{ $employee->employee_middlename }}</option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                    </div>
                                                <div class="flex mb-4 mt-10 justify-center">
                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                        Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Background -->
                <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 z-50" @click="open = false"></div>

                <!-- Modal Content -->
                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white p-8 rounded-lg shadow-lg max-w-7xl w-full ">
                        <div class="mt-6 flex justify-between">
                            <h2 class="text-lg font-semibold mb-4">Work Details</h2>
                            <button @click="open = false" class="btn btn-secondary hover:text-blue-500">Close</button>
                        </div>
                        <!-- Modal Body -->
                        <div class="space-y-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day Of Week</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Morning Hours</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Afternoon Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        
                                        @foreach($departmentDisplayWorkingHour as $working_hour)
                                            <tr>
                                                @php
                                                    $daysOfWeek = [
                                                        0 => 'Sunday',
                                                        1 => 'Monday',
                                                        2 => 'Tuesday',
                                                        3 => 'Wednesday',
                                                        4 => 'Thursday',
                                                        5 => 'Friday',
                                                        6 => 'Saturday',
                                                    ];
                                                @endphp

                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $daysOfWeek[$working_hour->day_of_week] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ date('h:i A', strtotime($working_hour->morning_start_time)) }} - {{ date('h:i A', strtotime($working_hour->morning_end_time)) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ date('h:i A', strtotime($working_hour->afternoon_start_time)) }} - {{ date('h:i A', strtotime($working_hour->afternoon_end_time)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
                
        @if($selectedEmployeeToShow)
            @if($search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty() && !$selectedAttendanceByDate->isEmpty())
                <p class="text-black mt-8 text-center">No attendance/s found in <span class="text-red-500">{{ $selectedEmployeeToShow->employee_id }} | {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }} </span> for matching "{{ $search }}"</p>
                <p class="text-center mt-5"><button class="ml-2 border border-gray-600 px-3 py-2 text-black hover:border-red-500 hover:text-red-500" wire:click="$set('search', '')"><i class="fa-solid fa-remove"></i> Clear Search</button></p>
            @elseif(!$search && $attendanceTimeIn->isEmpty() && $attendanceTimeOut->isEmpty() && !$selectedAttendanceByDate->isEmpty())
                <p class="text-black mt-8 text-center uppercase">No data available in employee <text class="text-red-500">{{ $selectedEmployeeToShow->employee_id }} | {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text></p>
            @else
                <div class="flex justify-between mt-1 mb-2">
                    <div class="mt-2 text-sm font-bold ">
                        <text class="uppercase">Selected Employee: <text class="text-red-500">{{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text><br>
                        <text class="uppercase">Employee ID: <text class="text-red-500">{{ $selectedEmployeeToShow->employee_id }}</text>
                    </div>
                    <div class="flex flex-col mt-11">
                        <div class="flex justify-between items-center mb-2">
                            <div class="grid grid-rows-2 grid-flow-col -mt-10">
                                
                                    <div class="text-center uppercase ml-16">
                                        Select Specific Date
                                    </div>
                                <div class="flex items-center space-x-4">
                                    <label for="startDate" class="text-gray-600">Start Date:</label>
                                    <input 
                                        id="startDate" 
                                        type="date" 
                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                        wire:model="startDate"
                                        wire:change="updateAttendanceByDateRange"
                                    >
                                    <label for="endDate" class="text-gray-600">End Date:</label>
                                    <input 
                                        id="endDate" 
                                        type="date" 
                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                        wire:model="endDate"
                                        wire:change="updateAttendanceByDateRange"
                                    >
                                </div>
                            </div>
                                <div class="flex flex-col -mt-10">
                                    <div class="flex justify-end mb-2 -mt-2">
                                        <a href="">
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"><i class="fa-solid fa-arrows-rotate"></i> Refresh</button>
                                        </a>
                                    </div>
                                        <button wire:click="generatePDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                                    <i class="fa-solid fa-file"></i> Generate Selected Employee's DTR
                                </button>
                                </div>               
                                
                        </div>
                    </div>
                </div>
                <div x-data="{ tab: 'time-in-time-out' }" class="mt-5 w-full">
                    <div class="overflow-x-auto">
                        <!-- Tab buttons -->
                        <div class="flex justify-between mb-4">
                            <div>
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'time-in-time-out'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'time-in-time-out', 'border border-gray-500': tab !== 'time-in-time-out' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Time In & Time Out
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        Select specific time-in dates to view details.
                                    </div>
                                </div>
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'computed-hours'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'computed-hours', 'border border-gray-500': tab !== 'computed-hours' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Detailed Calculation of Work Hours
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        View detailed calculations of work hours, including breakdowns and summaries.
                                    </div>
                                </div>

                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'reports'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'reports', 'border border-gray-500': tab !== 'reports' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Summary Report
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        View a summary of all attendance reports.
                                    </div>
                                </div>

                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button 
                                        @click="tab = 'modify_date'"
                                        :class="{ 'bg-blue-500 text-white': tab === 'modify_date', 'border border-gray-500': tab !== 'modify_date' }"
                                        class="px-4 py-2 mr-2 rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                        @mouseover="open = true"
                                        @mouseleave="open = false"
                                    >
                                        Modify Date for Approved Leave / Official Travel
                                    </button>
                                    <div 
                                        x-show="open"
                                        class="w-full absolute left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 mt-2"
                                        style="display: none;"
                                    >
                                        Adjust dates for approved leave or official travel. Ensure to update these dates for accurate records.
                                    </div>
                                </div>

                                <!-- Button to Open Modal -->

                            </div>
                            
                            <!-- Modal Background -->
                            <div x-data="{ open: false }" @click.away="open = false">
                                <!-- Modal -->
                                <button 
                                    @click="open = true; tab = 'holidays'"
                                    :class="{ 'bg-blue-500 text-white': tab === 'holidays', 'border border-gray-500': tab !== 'holidays' }"
                                    class="px-4 py-2 mr-[80px] rounded hover:bg-blue-600 hover:text-white focus:outline-none"
                                >
                                    Holiday Dates
                                </button>

                                <div x-cloak x-show="open" 
                                    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
                                        <h2 class="text-xl font-semibold mb-4">Reminder</h2>
                                        <p class="mb-4">Please add holiday dates in settings before the actual dates to avoid system automatic absences for those dates.</p>
                                        <div class="flex justify-end">
                                            <button @click="open = false" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none">
                                                OK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab content -->
                        <div x-show="tab === 'time-in-time-out'" class="w-full">
                            <!-- Table for Time In -->
                            <div class="flex justify-between">
                                <div class="w-[49%]">
                                    <div class="flex justify-center mb-2 mt-2">
                                        <h3 class="text-center uppercase font-bold">Time In &nbsp;</h3> | &nbsp;
                                        <div class="flex justify-center items-center space-x-2">
                                            <div x-data="{ open: false }">
                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Add Time In
                                                </a>
                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                            <p class="text-xl font-bold">Add Time In</p>
                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                        </div>
                                                        <div class="mb-4">
                                                            <form action="{{ route('admin_staff.attendance.employee_attendance.addIn') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time in?');">
                                                                <x-caps-lock-detector />
                                                                @csrf
                                                                    <div class="mb-2">
                                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name: </label>
                                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                                                <option selected value="{{ $selectedEmployeeToShow->id }}">{{ $selectedEmployeeToShow->employee_id }} - {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</option>
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                                    </div>
                                                                <div class="flex mb-4 mt-10 justify-center">
                                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Assuming $attendanceTimeIn is sorted by check_in_time descending -->
                                    @if ($attendanceTimeIn->isNotEmpty())
                                        @php
                                            $currentDate = null;
                                        @endphp
                                        @foreach ($attendanceTimeIn as $attendanceIn)
                                            @php
                                                $checkInTime = strtotime($attendanceIn->check_in_time);
                                                $date = date('m-d-Y', $checkInTime);
                                                $category = date('A', $checkInTime); // AM or PM
                                            @endphp
                                            @if ($date !== $currentDate)
                                                @php
                                                    $currentDate = $date;
                                                    $firstRow = true;
                                                @endphp
                                                <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                                                    <thead class="bg-gray-200 text-black">
                                                        <tr>
                                                            <th class="border border-gray-400 px-3">
                                                                Emp ID
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Date
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Time - In
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Status
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Action
                                                            </th>
                                                            <!-- Add other columns as needed -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                            @endif
                                            <tr class="hover:bg-gray-100">
                                                <td class="text-black border border-gray-400">{{ $attendanceIn->employee->employee_id }}</td>
                                                <td class="text-black border border-gray-400">
                                                    {{ date('m-d-Y (l)', strtotime($attendanceIn->check_in_time)) }}
                                                </td>
                                                <td class="text-black border border-gray-400 uppercase">
                                                    @php
                                                        $status = $attendanceIn->status;
                                                        $display = "";

                                                        if($status === "On Leave"){
                                                            $display = "On Leave";
                                                        } elseif($status === "Absent"){
                                                            $display = "Absent";
                                                        } elseif($status === "Weekend"){
                                                            $display = "Weekend";
                                                        } elseif($status === "awol"){
                                                            $display = "Absent without leave";
                                                        
                                                        } elseif($status === "Official Travel"){
                                                            $display = "Official Travel";
                                                        } else {
                                                            $display = date('g:i:s A', strtotime($attendanceIn->check_in_time));
                                                        }
                                                    @endphp

                                                    @if ($display === "On Leave")
                                                        <span style="color: red;">{{ $display }}</span>
                                                    @elseif ($display === "Official Travel")
                                                        <span style="color: red;" class="text-xs">{{ $display }}</span>
                                                    
                                                    @else
                                                        {{ $display }}
                                                    @endif
                                                </td>
                                                <td class="text-black border border-gray-400 px-1 py-1">
                                                    {{ ucfirst($attendanceIn->modification_status) }}
                                                </td>
                                                <td class="text-black border border-gray-400 px-1 py-1">
                                                    <div class="flex justify-center items-center">
                                                        <div x-data="{ open: false }" class="mr-2">
                                                            <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-[5.5px] rounded hover:bg-blue-700">
                                                                <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Edit
                                                            </a>
                                                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                                <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                                    <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                        <p class="text-xl font-bold">Edit Time In</p>
                                                                        <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <form id="updateTimeInForm" action="{{ route('admin_staff.attendanceIn.edit', $attendanceIn->id) }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to update?');">
                                                                            <x-caps-lock-detector />
                                                                            @csrf
                                                                            @method('PUT')
                                                                                <div class="mb-2 hidden">
                                                                                    <label for="attendanceIn_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Attendance ID: </label>
                                                                                    <select id="attendanceIn_id" name="attendanceIn_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('attendanceIn_id') is-invalid @enderror">
                                                                                            <option value="{{ $attendanceIn->id }}">{{ $attendanceIn->id }}</option>
                                                                                    </select>
                                                                                    <x-input-error :messages="$errors->get('attendanceIn_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee ID</label>
                                                                                    <input type="text" name="employee_id" id="employee_id" value="{{ $attendanceIn->employee->employee_id }}"  readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_name" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name</label>
                                                                                    <input type="text" name="employee_name" id="employee_name" value="{{ $attendanceIn->employee->employee_lastname }}, {{ $attendanceIn->employee->employee_firstname }}, {{ $attendanceIn->employee->employee_middlename }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_name') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_name')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_date" class="block text-gray-700 text-md font-bold mb-2 text-left">Date of Attendance</label>
                                                                                    <input type="text" name="check_in_date" id="check_in_date" value="{{ date('Y-m-d (l)', strtotime($attendanceIn->check_in_time)) }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('check_in_date')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_time" class="block text-gray-700 text-md font-bold mb-2 text-left">Time In</label>
                                                                                    
                                                                                    <!-- Hidden input for the date part -->
                                                                                    <input type="hidden" name="check_in_time_date" id="check_in_time_date"
                                                                                        value="{{ $attendanceIn->check_in_time ? date('Y-m-d', strtotime($attendanceIn->check_in_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_date') is-invalid @enderror"
                                                                                        autofocus>
                                                                                    
                                                                                    <!-- Visible input for time part with AM/PM formatting -->
                                                                                    <!-- <input type="time" name="check_in_time_time" id="check_in_time_time"
                                                                                        value="{{ $attendanceIn->check_in_time ? date('h:i:s A', strtotime($attendanceIn->check_in_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_time') is-invalid @enderror"
                                                                                        placeholder="hh:mm:ss AM/PM" required autofocus> -->
                                                                                    <input type="time" name="check_in_time_time" id="check_in_time_time"
                                                                                        value="{{ $attendanceIn->check_in_time ? date('H:i', strtotime($attendanceIn->check_in_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_time') is-invalid @enderror"
                                                                                        required autofocus>
                                                                                    
                                                                                    <!-- Error message container -->
                                                                                    <p id="time_error" class="text-red-500 text-sm mt-2 hidden">Invalid time input. Please ensure the hour is between 1-12, and minutes and seconds are between 0-59.</p>
                                                                                    
                                                                                    <x-input-error :messages="$errors->get('check_in_time_time')" class="mt-2" />
                                                                                </div>
                                                                            <div class="flex mb-4 mt-10 justify-center">
                                                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                                    Save Changes
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('admin.attendance.employee_attendance.deleteTimeIn', $attendanceIn->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this time in?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 text-white text-sm px-2 py-1 rounded hover:bg-red-700">
                                                                <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                
                                                <!-- Add other columns as needed -->
                                            </tr>
                                            @if ($loop->last)
                                                    </tbody>
                                                </table>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-center mt-8">No Time In records found.</p>
                                    @endif
                                    <div class="text-center font-bold uppercase">{{ $attendanceTimeIn->links() }}</div>
                                </div>
                                
                                <div class="w-[49%]">
                                    <div class="flex justify-center mb-2 mt-2">
                                        <h3 class="text-center uppercase font-bold">Time Out &nbsp;</h3> | &nbsp;
                                        <div class="flex justify-center items-center space-x-2">
                                            <div x-data="{ open: false }">
                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Add Time Out
                                                </a>
                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                    <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                            <p class="text-xl font-bold">Add Time Out</p>
                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                        </div>
                                                        <div class="mb-4">
                                                            <form action="{{ route('admin_staff.attendance.employee_attendance.addOut') }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to add time out?');">
                                                                <x-caps-lock-detector />
                                                                @csrf
                                                                    <div class="mb-2">
                                                                        <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name: </label>
                                                                        <select id="employee_id" name="employee_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror">
                                                                                <option selected value="{{ $selectedEmployeeToShow->id }}">{{ $selectedEmployeeToShow->employee_id }} - {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</option>
                                                                        </select>
                                                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                    </div>
                                                                    <div class="mb-2">
                                                                        <label for="selected-date-time" class="block text-gray-700 text-md font-bold mb-2 text-left">Select Date & Time:</label>
                                                                        <input type="datetime-local" id="selected-date-time"  name="selected-date-time" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('selected-date-time') is-invalid @enderror" required>
                                                                        <x-input-error :messages="$errors->get('selected-date-time')" class="mt-2" />
                                                                    </div>
                                                                <div class="flex mb-4 mt-10 justify-center">
                                                                    <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($attendanceTimeOut->isNotEmpty())
                                        @php
                                            $currentDate = null;
                                            $firstRow = true;
                                        @endphp
                                        @foreach ($attendanceTimeOut as $attendanceOut)
                                            @php
                                                $checkOutTime = strtotime($attendanceOut->check_out_time);
                                                $date = date('m-d-Y', $checkOutTime);
                                                $isFirstRow = ($date !== $currentDate);
                                                $category = $isFirstRow ? 'AM' : date('A', $checkOutTime);
                                            @endphp
                                            @if ($isFirstRow)
                                                @if ($loop->index > 0)
                                                    </tbody></table>
                                                @endif
                                                <table class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
                                                    <thead class="bg-gray-200 text-black">
                                                        <tr>
                                                            <th class="border border-gray-400 px-3">
                                                                Emp ID
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Date
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Time - Out
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Status
                                                            </th>
                                                            <th class="border border-gray-400 px-3">
                                                                Action
                                                            </th>
                                                            <!-- Add other columns as needed -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                @php
                                                    $currentDate = $date;
                                                @endphp
                                            @endif
                                            <tr class="hover:bg-gray-100">
                                                <td class="text-black border border-gray-400">{{ $attendanceOut->employee->employee_id }}</td>
                                                <td class="text-black border border-gray-400">
                                                    {{ date('m-d-Y (l)', $checkOutTime) }}
                                                </td>
                                                
                                                <td class="text-black border border-gray-400 uppercase">
                                                    <!-- {{ date('g:i:s A', $checkOutTime) }} -->
                                                    @php
                                                        $status = $attendanceOut->status;
                                                        $display = "";

                                                        if($status === "On Leave"){
                                                            $display = "On Leave";
                                                        } elseif($status === "Absent"){
                                                            $display = "Absent";
                                                        } elseif($status === "Weekend"){
                                                            $display = "Weekend";
                                                        } elseif($status === "awol"){
                                                            $display = "Absent without leave";
                                                        } elseif($status === "Official Travel"){
                                                            $display = "Official Travel";
                                                        } else {
                                                            $display = date('g:i:s A', strtotime($attendanceOut->check_out_time));
                                                        }
                                                    @endphp

                                                    @if ($display === "On Leave")
                                                        <span style="color: red;">{{ $display }}</span>
                                                    @elseif ($display === "Official Travel")
                                                        <span style="color: red;" class="text-xs">{{ $display }}</span>
                                                    @else
                                                        {{ $display }}
                                                    @endif
                                                </td>
                                                <td class="text-black border border-gray-400">
                                                    {{ ucfirst($attendanceOut->modification_status) }}
                                                </td>
                                                <td class="text-black border border-gray-400 px-1 py-1">
                                                    <div class="flex justify-center items-center space-x-2">
                                                        <div x-data="{ open: false }">
                                                            <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                                <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> Edit
                                                            </a>
                                                            <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                                <div @click.away="open = true" class="w-[35%] bg-white p-6 rounded-lg shadow-lg  mx-auto">
                                                                    <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                        <p class="text-xl font-bold">Edit Time Out</p>
                                                                        <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <form id="updateTimeOutForm" action="{{ route('admin_staff.attendanceOut.edit', $attendanceOut->id) }}" method="POST" class="" onsubmit="return confirm('Are you sure you want to update?');">
                                                                            <x-caps-lock-detector />
                                                                            @csrf
                                                                            @method('PUT')
                                                                                <div class="mb-2 hidden">
                                                                                    <label for="attendanceOut_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Attendance ID: </label>
                                                                                    <select id="attendanceOut_id" name="attendanceOut_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline @error('attendanceIn_id') is-invalid @enderror">
                                                                                            <option value="{{ $attendanceOut->id }}">{{ $attendanceOut->id }}</option>
                                                                                    </select>
                                                                                    <x-input-error :messages="$errors->get('attendanceOut_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee ID</label>
                                                                                    <input type="text" name="employee_id" id="employee_id" value="{{ $attendanceOut->employee->employee_id }}"  readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_id') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="employee_name" class="block text-gray-700 text-md font-bold mb-2 text-left">Employee Name</label>
                                                                                    <input type="text" name="employee_name" id="employee_name" value="{{ $attendanceOut->employee->employee_lastname }}, {{ $attendanceOut->employee->employee_firstname }}, {{ $attendanceOut->employee->employee_middlename }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_name') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('employee_name')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_date" class="block text-gray-700 text-md font-bold mb-2 text-left">Date of Attendance</label>
                                                                                    <input type="text" name="check_in_date" id="check_in_date" value="{{ date('Y-m-d (l)', strtotime($attendanceOut->check_out_time)) }}" readonly class="cursor-pointer shadow appearance-none  rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time') is-invalid @enderror" autofocus>
                                                                                    <x-input-error :messages="$errors->get('check_in_date')" class="mt-2" />
                                                                                </div>
                                                                                <div class="mb-4">
                                                                                    <label for="check_in_time" class="block text-gray-700 text-md font-bold mb-2 text-left">Time Out</label>
                                                                                    
                                                                                    <!-- Hidden input for the date part -->
                                                                                    <input type="hidden" name="check_out_time_date" id="check_in_time_date"
                                                                                        value="{{ $attendanceOut->check_out_time ? date('Y-m-d', strtotime($attendanceOut->check_out_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_date') is-invalid @enderror"
                                                                                        autofocus>
                                                                                    <x-input-error :messages="$errors->get('check_out_time_date')" class="mt-2" />

                                                                                    <input type="time" name="check_out_time_time" id="check_in_time_time"
                                                                                        value="{{ $attendanceOut->check_out_time ? date('H:i', strtotime($attendanceOut->check_out_time)) : '' }}"
                                                                                        class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_time_time') is-invalid @enderror"
                                                                                        required autofocus>
                                                                                    
                                                                                    <!-- Error message container -->
                                                                                    <p id="time_error" class="text-red-500 text-sm mt-2 hidden">Invalid time input. Please ensure the hour is between 1-12, and minutes and seconds are between 0-59.</p>
                                                                                    
                                                                                    <x-input-error :messages="$errors->get('check_out_time_time')" class="mt-2" />
                                                                                </div>
                                                                            <div class="flex mb-4 mt-10 justify-center">
                                                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                                                    Save Changes
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('admin.attendance.employee_attendance.deleteTimeOut', $attendanceOut->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this time out?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 text-white text-sm px-2 py-1 rounded hover:bg-red-700">
                                                                <i class="fa-solid fa-trash fa-xs" style="color: #ffffff;"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <!-- Add other columns as needed -->
                                            </tr>
                                            @if ($loop->last)
                                                </tbody></table>
                                            @endif
                                        @endforeach
                                    @else
                                        <p class="text-center mt-8">No Time Out records found.</p>
                                    @endif
                                    <div class="text-center font-bold uppercase">{{ $attendanceTimeOut->links() }}</div>
                                </div>

                            </div>
                        </div>
                        
                        <div x-show="tab === 'computed-hours'" class="w-full">
                            <!-- Table for Computed Working Hours -->

                            <div class="w-full">
                                <h3 class="text-center text-lg font-semibold uppercase mb-2 mt-6">Calculation of Work Hours</h3>
                                <div class="flex justify-between">
                                    <p><text class="text-red-500">Note: </text> To assess time-in and time out duration, click working hour to verify.</p>
                                    <p><text class="text-red-500">Note: </text> Dates that are missing or excluded may be weekends or holidays.</p>
                                </div>
                                <table class="table-auto min-w-full text-center text-xs mb-4 divide-y divide-gray-200">
                                    <thead class="bg-gray-200 text-black">
                                        <tr>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Date</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase" >Time In</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Time Out</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Late AM | PM</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Late</th>
                                            <!-- <th class="border border-gray-400 px-2 py-1">PM Late</th> -->
                                            <th class="border border-gray-400 px-2 py-1 uppercase">UnderTime AM | PM</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Undertime</th>
                                            <!-- <th class="border border-gray-400 px-2 py-1">PM UnderTime</th> -->
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours AM & PM</th>
                                            <!-- <th class="border border-gray-400 px-2 py-1">Total PM Hours</th> -->
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours Rendered</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Deduction (late + undertime)</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Total Absent</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Required Hours</th>
                                            <th class="border border-gray-400 px-2 py-1 uppercase">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupedAttendance = [];

                                            // Group check-in times
                                            foreach ($attendanceTimeIn as $attendanceIn) {
                                                $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                                                $employeeId = $attendanceIn->employee->employee_id;
                                                $status = $attendanceIn->status;

                                                if (!isset($groupedAttendance[$employeeId])) {
                                                    $groupedAttendance[$employeeId] = [];
                                                }

                                                if (!isset($groupedAttendance[$employeeId][$date])) {
                                                    $groupedAttendance[$employeeId][$date] = [
                                                        'date' => date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)),
                                                        'check_ins' => [],
                                                        'check_outs' => [],
                                                        'status' => $status,
                                                    ];
                                                }

                                                $groupedAttendance[$employeeId][$date]['check_ins'][] = date('g:i:s A', strtotime($attendanceIn->check_in_time));
                                            }

                                            // Group check-out times
                                            foreach ($attendanceTimeOut as $attendanceOut) {
                                                $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                                                $employeeId = $attendanceOut->employee->employee_id;
                                                $status = $attendanceOut->status;

                                                if (!isset($groupedAttendance[$employeeId])) {
                                                    $groupedAttendance[$employeeId] = [];
                                                }

                                                if (!isset($groupedAttendance[$employeeId][$date])) {
                                                    $groupedAttendance[$employeeId][$date] = [
                                                        'date' => date('m-d-Y, (l)', strtotime($attendanceOut->check_out_time)),
                                                        'check_ins' => [],
                                                        'check_outs' => [],
                                                        'status' => $status,
                                                    ];
                                                }

                                                $groupedAttendance[$employeeId][$date]['check_outs'][] = date('g:i:s A', strtotime($attendanceOut->check_out_time));
                                            }
                                        @endphp
                                        @foreach ($attendanceData as $attendance)
                                            @php
                                                $workedDate = date('Y-m-d', strtotime($attendance->worked_date));
                                            @endphp
                                        <tr class="hover:border hover:bg-gray-200">
                                            <td class="text-black border border-gray-400 px-2 py-1 font-bold">{{ date('M d, Y (D)', strtotime($attendance->worked_date)) }}</td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-28">
                                                @foreach ($groupedAttendance as $employeeId => $dates)
                                                    @foreach ($dates as $date => $attendance1)
                                                        @if ($date === $workedDate)
                                                            {{-- Handle 1st check-in --}}
                                                            @if (!empty($attendance1['check_ins'][0]))
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                <text class="text-red-500">1ST TIME IN:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $firstCheckIn = $attendance1['check_ins'][0];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($firstCheckIn)) === '00:00:00' || empty($firstCheckIn))
                                                                    <text class="text-red-500">No 1st Check-In</text>
                                                                @else
                                                                    {{ $firstCheckIn }}
                                                                @endif
                                                            @else
                                                                <p class="text-red-500">No 1st Check-In</p>
                                                            @endif

                                                            {{-- Handle 2nd check-in --}}
                                                            @if (!empty($attendance1['check_ins'][1]))
                                                                <br><br>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                <text class="text-blue-500">2ND TIME IN:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $secondCheckIn = $attendance1['check_ins'][1];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($secondCheckIn)) === '00:00:00' || empty($secondCheckIn))
                                                                    <text class="text-red-500">No 2nd Check-In</text>
                                                                @else
                                                                    {{ $secondCheckIn }}
                                                                @endif
                                                            @else
                                                                <p class="mt-10 text-red-500">No 2nd Check-In</p>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-32">
                                                @foreach ($groupedAttendance as $employeeId => $dates)
                                                    @foreach ($dates as $date => $attendance1)
                                                        @if ($date === $workedDate)
                                                            {{-- Handle 1st check-out --}}
                                                            @if (!empty($attendance1['check_outs'][0]))
                                                                <hr style="border: none; border-top: 1px solid #000;">
                                                                <text class="text-red-500">1ST TIME OUT:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $firstCheckOut = $attendance1['check_outs'][0];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($firstCheckOut)) === '00:00:00')
                                                                    <text class="text-red-500">NO TIME OUT</text>
                                                                @else
                                                                    {{ $firstCheckOut }}
                                                                @endif
                                                            @else
                                                                <p class="text-red-500">No 1st Check-Out</p>
                                                            @endif

                                                            {{-- Handle 2nd check-out --}}
                                                            @if (!empty($attendance1['check_outs'][1]))
                                                                <br><br>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                <text class="text-blue-500">2ND TIME OUT:</text>
                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                
                                                                @php
                                                                    $secondCheckOut = $attendance1['check_outs'][1];
                                                                @endphp

                                                                @if (date('H:i:s', strtotime($secondCheckOut)) === '00:00:00')
                                                                    <text class="text-red-500">NO TIME OUT</text>
                                                                @else
                                                                    {{ $secondCheckOut }}
                                                                @endif
                                                            @else
                                                                <p class="mt-10 text-red-500">No 2nd Check-Out</p>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                <!-- THIS IS PM AND AM LATE DURATION -->
                                                @php
                                                    // Calculate late duration in minutes for AM
                                                    $lateDurationInMinutesAM = $attendance->late_duration;

                                                    // Calculate late hours, minutes, and seconds for AM
                                                    $lateHoursAM = intdiv($lateDurationInMinutesAM, 60);
                                                    $lateMinutesAM = $lateDurationInMinutesAM % 60;
                                                    $lateSecondsAM = ($lateDurationInMinutesAM - floor($lateDurationInMinutesAM)) * 60;

                                                    // Round seconds to avoid precision issues for AM
                                                    $lateSecondsAM = round($lateSecondsAM);

                                                    // Format the late duration string for AM
                                                    $lateDurationFormattedAM = ($lateHoursAM > 0 ? "{$lateHoursAM} hr " : '') 
                                                                            . ($lateMinutesAM > 0 ? "{$lateMinutesAM} min " : '')
                                                                            . ($lateSecondsAM > 0 ? "{$lateSecondsAM} sec" : '');

                                                    // If the formatted string is empty for AM, ensure we show "0"
                                                    $lateDurationFormattedAM = $lateDurationFormattedAM ?: '0 sec';

                                                    // Calculate late duration in minutes for PM
                                                    $lateDurationInMinutesPM = $attendance->late_durationPM;

                                                    // Calculate late hours, minutes, and seconds for PM
                                                    $lateHoursPM = intdiv($lateDurationInMinutesPM, 60);
                                                    $lateMinutesPM = $lateDurationInMinutesPM % 60;
                                                    $lateSecondsPM = ($lateDurationInMinutesPM - floor($lateDurationInMinutesPM)) * 60;

                                                    // Round seconds to avoid precision issues for PM
                                                    $lateSecondsPM = round($lateSecondsPM);

                                                    // Format the late duration string for PM
                                                    $lateDurationFormattedPM = ($lateHoursPM > 0 ? "{$lateHoursPM} hr " : '') 
                                                                            . ($lateMinutesPM > 0 ? "{$lateMinutesPM} min " : '')
                                                                            . ($lateSecondsPM > 0 ? "{$lateSecondsPM} sec" : '');

                                                    // If the formatted string is empty for PM, ensure we show "0"
                                                    $lateDurationFormattedPM = $lateDurationFormattedPM ?: '0 sec';
                                                @endphp

                                                @if (!empty($lateDurationInMinutesAM) && !empty($lateDurationInMinutesPM))
                                                    <div class="mt-2" >
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM LATE:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $lateDurationFormattedAM }}
                                                    </div>

                                                    <div class="mt-4">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM LATE</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $lateDurationFormattedPM }}
                                                    </div>
                                                @elseif (!empty($lateDurationInMinutesAM))
                                                    <div class="-mt-6">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM LATE:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <br>
                                                        {{ $lateDurationFormattedAM }}
                                                    </div>
                                                @elseif (!empty($lateDurationInMinutesPM))
                                                    <div class="mt-1">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM LATE:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <br>
                                                        {{ $lateDurationFormattedPM }}
                                                    </div>
                                                @else
                                                    <p>No Late</p>
                                                @endif
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                @php
                                                    // Total late time in minutes as a decimal
                                                    $totalLateMinutesDecimal = $attendance->total_late;

                                                    // Convert decimal minutes to total hours, minutes, and seconds
                                                    $totalLateHours = intdiv($totalLateMinutesDecimal, 60); // Total hours
                                                    $remainingMinutes = floor($totalLateMinutesDecimal % 60); // Remaining minutes
                                                    $totalLateSeconds = round(($totalLateMinutesDecimal - floor($totalLateMinutesDecimal)) * 60); // Total seconds

                                                    // Format the duration string
                                                    if ($totalLateMinutesDecimal > 0) {
                                                        $totalLateDurationFormatted = 
                                                            ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                            ($remainingMinutes > 0 ? "{$remainingMinutes} mins " : '0 mins ') .
                                                            ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                    } else {
                                                        $totalLateDurationFormatted = '0';
                                                    }
                                                @endphp

                                                {{ $totalLateDurationFormatted }}
                                            </td>
                                            <td class="text-black border border-gray-400 p-2 w-[134px]">
                                                @php
                                                    // Calculate undertime in minutes for AM
                                                    $undertimeInMinutesAM = $attendance->undertimeAM;

                                                    // Convert minutes to total seconds for AM
                                                    $undertimeInSecondsAM = $undertimeInMinutesAM * 60;

                                                    // Convert total seconds to hours, minutes, and seconds for AM
                                                    $undertimeHoursAM = intdiv($undertimeInSecondsAM, 3600); // Total hours
                                                    $remainingSecondsAM = $undertimeInSecondsAM % 3600; // Remaining seconds after hours
                                                    $undertimeMinutesAM = intdiv($remainingSecondsAM, 60); // Total minutes
                                                    $undertimeSecondsAM = $remainingSecondsAM % 60; // Remaining seconds after minutes

                                                    // Format the undertime string for AM
                                                    $undertimeFormattedAM = 
                                                        ($undertimeHoursAM > 0 ? "{$undertimeHoursAM} hr " : '') .
                                                        ($undertimeMinutesAM > 0 ? "{$undertimeMinutesAM} min " : '0 min ') .
                                                        ($undertimeSecondsAM > 0 ? "{$undertimeSecondsAM} sec" : '0 sec');

                                                    // Calculate undertime in minutes for PM
                                                    $undertimeInMinutesPM = $attendance->undertimePM;

                                                    // Convert minutes to total seconds for PM
                                                    $undertimeInSecondsPM = $undertimeInMinutesPM * 60;

                                                    // Convert total seconds to hours, minutes, and seconds for PM
                                                    $undertimeHoursPM = intdiv($undertimeInSecondsPM, 3600); // Total hours
                                                    $remainingSecondsPM = $undertimeInSecondsPM % 3600; // Remaining seconds after hours
                                                    $undertimeMinutesPM = intdiv($remainingSecondsPM, 60); // Total minutes
                                                    $undertimeSecondsPM = $remainingSecondsPM % 60; // Remaining seconds after minutes

                                                    // Format the undertime string for PM
                                                    $undertimeFormattedPM = 
                                                        ($undertimeHoursPM > 0 ? "{$undertimeHoursPM} hr " : '') .
                                                        ($undertimeMinutesPM > 0 ? "{$undertimeMinutesPM} min " : '0 min ') .
                                                        ($undertimeSecondsPM > 0 ? "{$undertimeSecondsPM} sec" : '0 sec');
                                                @endphp

                                                @if (!empty($undertimeInMinutesAM) && !empty($undertimeInMinutesPM))
                                                    <div class="">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $undertimeFormattedAM }}
                                                    </div>

                                                    <div class="mt-4">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $undertimeFormattedPM }}
                                                    </div>
                                                    <!-- <table class="p-0 w-full m-0">
                                                        <tr class="border border-red-500 h-full">
                                                            <td >
                                                                <div class="mt-3 ">
                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                    <span class="text-red-500">AM UNDERTIME:</span>
                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                    {{ $undertimeFormattedAM }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="border border-red-500">
                                                            <td>
                                                                <div class="mt-4">
                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                    <span class="text-blue-500">PM UNDERTIME:</span>
                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                    {{ $undertimeFormattedPM }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table> -->

                                                @elseif (!empty($undertimeInMinutesAM))
                                                    <div>
                                                        <text class="text-red-500">AM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        {{ $undertimeFormattedAM }}
                                                    </div>
                                                @elseif (!empty($undertimeInMinutesPM))
                                                    <div class="mt-1">
                                                        <text class="text-blue-500">PM UNDERTIME:</text>
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        {{ $undertimeFormattedPM }}
                                                    </div>
                                                @else
                                                    <p>No Undertime</p>
                                                @endif
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1">
                                                <!-- Undertime Area Total -->
                                                @php
                                                    $am = $attendance->undertimeAM;
                                                    $pm = $attendance->undertimePM;
                                                    $totalUndertimeInMinutes = $am + $pm;

                                                    if ($totalUndertimeInMinutes > 0) {
                                                        // Convert total minutes to total seconds
                                                        $totalUndertimeInSeconds = $totalUndertimeInMinutes * 60;

                                                        // Convert total seconds to hours, minutes, and seconds
                                                        $totalLateHours = intdiv($totalUndertimeInSeconds, 3600); // Total hours
                                                        $remainingSeconds = $totalUndertimeInSeconds % 3600; // Remaining seconds after hours
                                                        $totalLateMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                        $totalLateSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                        // Format the duration string
                                                        $totalLateDurationFormatted = 
                                                            ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                            ($totalLateMinutes > 0 ? "{$totalLateMinutes} mins " : '0 mins ') .
                                                            ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                    } else {
                                                        $totalLateDurationFormatted = '0';
                                                    }
                                                @endphp

                                                {{ $totalLateDurationFormatted }}
                                            </td>
                                            
                                            <td class="text-black border border-gray-400 px-3 py-2 w-40">
                                                @php
                                                    // Total hours worked in AM shift
                                                    $totalHoursAM = floor($attendance->hours_workedAM);
                                                    $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                    $totalSecondsAM = ($totalMinutesAM - floor($totalMinutesAM)) * 60;
                                                    $totalMinutesAM = floor($totalMinutesAM);

                                                    $finalHoursAM = $totalHoursAM;
                                                    $roundedMinutesAM = round($totalMinutesAM + ($totalSecondsAM / 60));
                                                    $finalSecondsAM = round($totalSecondsAM % 60);

                                                    if ($finalSecondsAM >= 59) {
                                                        $finalSecondsAM = 0;
                                                        $roundedMinutesAM += 1;
                                                    } else {
                                                        $finalSecondsAM = 0;
                                                    }

                                                    if ($roundedMinutesAM >= 59) {
                                                        $roundedMinutesAM = 0;
                                                        $finalHoursAM += 1;
                                                    }

                                                    $finalMinutesAM = $roundedMinutesAM;

                                                    // Total hours worked in PM shift
                                                    $totalHoursPM = floor($attendance->hours_workedPM);
                                                    $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                    $totalSecondsPM = ($totalMinutesPM - floor($totalMinutesPM)) * 60;
                                                    $totalMinutesPM = floor($totalMinutesPM);

                                                    $finalHoursPM = $totalHoursPM + floor($totalMinutesPM / 60);
                                                    $finalMinutesPM = $totalMinutesPM % 60;
                                                    $finalSecondsPM = round($totalSecondsPM);

                                                    if ($finalSecondsPM == 60) {
                                                        $finalSecondsPM = 0;
                                                        $finalMinutesPM += 1;
                                                    }

                                                    if ($finalMinutesPM >= 60) {
                                                        $finalMinutesPM = 0;
                                                        $finalHoursPM += 1;
                                                    }
                                                @endphp

                                                @if ($attendance->hours_workedAM > 0 || $attendance->hours_workedPM > 0)
                                                    <div class="mt-2">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-red-500">AM WORKED:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $finalHoursAM }} hr/s. {{ $finalMinutesAM }} min. {{ $finalSecondsAM }} sec.
                                                    </div>

                                                    <div class="mt-4">
                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                        <text class="text-blue-500">PM WORKED:</text>
                                                        <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                        {{ $finalHoursPM }} hrs. {{ $finalMinutesPM }} min. {{ $finalSecondsPM }} sec.
                                                    </div>
                                                @else
                                                    <p>0</p>
                                                @endif
                                            </td>
                                            <td class="text-black border border-gray-400 px-2 py-1 font-bold w-32">
                                                @php
                                                    // Total hours worked in decimal format
                                                    $totalHoursWorked = $attendance->total_hours_worked;
                                                    
                                                    // Calculate hours and minutes
                                                    $totalHours = floor($totalHoursWorked);
                                                    $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                    
                                                    // Calculate the final hours, minutes, and seconds
                                                    $finalMinutes = floor($totalMinutes);
                                                    $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                    $finalSeconds = round($totalSeconds);
                                                    
                                                    // Handle case where seconds is 60
                                                    if ($finalSeconds == 60) {
                                                        $finalSeconds = 0;
                                                        $finalMinutes += 1;
                                                    }
                                                    
                                                    // Handle case where minutes exceed 59
                                                    if ($finalMinutes >= 60) {
                                                        $finalMinutes = 0;
                                                        $totalHours += 1;
                                                    }

                                                    // Format the duration string
                                                    if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                        $totalHoursWorkedFormatted = '0';
                                                    } else {
                                                        $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                    }
                                                @endphp

                                                {{ $totalHoursWorkedFormatted }}

                                                        
                                            </td>
                                            <td class="text-black border border-gray-400 px-3 py-2">
                                                    <!-- total deduction -->
                                                @php
                                                    

                                                    $totalHoursWorked = $attendance->total_hours_worked;

                                                    if($totalHoursWorked == 0) {
                                                        

                                                        $am = $attendance->undertimeAM;
                                                        $pm = $attendance->undertimePM;
                                                        $totalUndertimeInMinutes = $am + $pm;

                                                        $undertimeHours = floor($totalUndertimeInMinutes / 60);
                                                        $undertimeMinutes = $totalUndertimeInMinutes % 60;
                                                        $undertimeSeconds = round(($totalUndertimeInMinutes * 60) % 60);

                                                        if($totalUndertimeInMinutes > 0){
                                                            $totalDurationFormatted = "{$undertimeHours} hr/s, {$undertimeMinutes} min/s, {$undertimeSeconds} sec";
                                                        } else{
                                                            $totalDurationFormatted = 0;
                                                        }
                                                    }
                                                    else {

                                                        // Total late time in minutes as a decimal
                                                        $totalLateMinutesDecimal = $attendance->total_late;

                                                        // Total undertime in minutes
                                                        $am = $attendance->undertimeAM;
                                                        $pm = $attendance->undertimePM;
                                                        $totalUndertimeInMinutes = $am + $pm;
                                                        
                                                        
                                                        // Combine late and undertime in minutes
                                                        $totalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                        // Convert total minutes to total seconds
                                                        $totalSeconds = $totalMinutes * 60;

                                                        // Convert total seconds to hours, minutes, and seconds
                                                        $totalHours = intdiv($totalSeconds, 3600); // Total hours
                                                        $remainingSeconds = $totalSeconds % 3600; // Remaining seconds after hours
                                                        $totalMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                        $totalSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                        // Format the duration string for total deduction
                                                        if ($totalMinutes > 0 || $totalLateMinutesDecimal > 0 || $totalUndertimeInMinutes > 0) {
                                                            $totalDurationFormatted = 
                                                                ($totalHours > 0 ? "{$totalHours} hr/s, " : '') .
                                                                ($totalMinutes > 0 ? "{$totalMinutes} min/s, " : '0 min/s ') .
                                                                ($totalSeconds > 0 ? "{$totalSeconds} sec" : '0 sec');
                                                        } else {
                                                            $totalDurationFormatted = '0';
                                                        }

                                                        // Total hours worked in decimal format
                                                        $totalHoursWorked = $attendance->total_hours_worked;
                                                        
                                                        // Calculate hours and minutes
                                                        $totalHours = floor($totalHoursWorked);
                                                        $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                        
                                                        // Calculate the final hours, minutes, and seconds
                                                        $finalMinutes = floor($totalMinutes);
                                                        $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                        $finalSeconds = round($totalSeconds);
                                                        
                                                        // Handle case where seconds is 60
                                                        if ($finalSeconds == 60) {
                                                            $finalSeconds = 0;
                                                            $finalMinutes += 1;
                                                        }
                                                        
                                                        // Handle case where minutes exceed 59
                                                        if ($finalMinutes >= 60) {
                                                            $finalMinutes = 0;
                                                            $totalHours += 1;
                                                        }

                                                        // Format the duration string for total hours worked
                                                        if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                            $totalHoursWorkedFormatted = 'No total hours';
                                                        } else {
                                                            $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                        }

                                                        // Use hours_perDay if totalHoursWorkedFormatted is 'No total hours'
                                                        if ($totalHoursWorkedFormatted === 'No total hours') {
                                                            $hoursPerDay = $attendance->hours_perDay;
                                                            $hours = floor($hoursPerDay);
                                                            $minutes = floor(($hoursPerDay - $hours) * 60);
                                                            $seconds = round((((($hoursPerDay - $hours) * 60) - $minutes) * 60));
                                                            
                                                            $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                            $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                            $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                            $totalDurationFormatted = "{$formattedHours}, {$formattedMinutes}, {$formattedSeconds}";
                                                        }
                                                    }
                                                @endphp
                                                {{ $totalDurationFormatted }}

                                            </td>
                                            <td class="text-black border border-gray-400 px-3 py-2">
                                                @php

                                                    $totalHours = $attendance->hours_perDay;
                                                    $hours = floor($totalHours);
                                                    $minutes = floor(($totalHours - $hours) * 60);
                                                    $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                    // Round minutes if seconds are 59
                                                    if ($seconds >= 59) {
                                                        $minutes += 1;
                                                        $seconds = 0;
                                                    }

                                                    // Format the result based on hours, minutes, and seconds
                                                    if ($hours === 0 && $minutes === 0 && $seconds === 0) {
                                                        $formattedTime = '0';
                                                    } elseif ($hours === 0 && $minutes === 0) {
                                                        $formattedTime = '0 sec';
                                                    } elseif ($hours === 0 && $seconds === 0) {
                                                        $formattedTime = "{$minutes} min";
                                                    } elseif ($hours === 0) {
                                                        $formattedTime = "{$minutes} min, {$seconds} sec";
                                                    } elseif ($minutes === 0 && $seconds === 0) {
                                                        $formattedTime = "{$hours} hr/s";
                                                    } elseif ($minutes === 0) {
                                                        $formattedTime = "{$hours} hr, {$seconds} sec";
                                                    } elseif ($seconds === 0) {
                                                        $formattedTime = "{$hours} hr, {$minutes} min";
                                                    } else {
                                                        $formattedTime = "{$hours} hr, {$minutes} min, {$seconds} sec";
                                                    }

                                                    // Time period 1 (formatted time)
                                                    $totalHours1 = $attendance->hours_perDay;
                                                    $hours1 = floor($totalHours1);
                                                    $minutes1 = floor(($totalHours1 - $hours1) * 60);
                                                    $seconds1 = round((((($totalHours1 - $hours1) * 60) - $minutes1) * 60));

                                                    // Convert time period 1 to total seconds
                                                    $timePeriod1Seconds = ($hours1 * 3600) + ($minutes1 * 60) + $seconds1;

                                                    // Time period 2 (total worked time)
                                                    $totalHoursWorked = $attendance->total_hours_worked;
                                                    $workedHours = floor($totalHoursWorked);
                                                    $totalMinutes = ($totalHoursWorked - $workedHours) * 60;
                                                    $workedMinutes = floor($totalMinutes);
                                                    $workedSeconds = round(($totalMinutes - $workedMinutes) * 60);

                                                    // Total late and undertime in minutes
                                                    $totalLateMinutesDecimal = $attendance->total_late;
                                                    $am = $attendance->undertimeAM;
                                                    $pm = $attendance->undertimePM;
                                                    $totalUndertimeInMinutes = $am + $pm;

                                                    // Combine late and undertime in minutes
                                                    $totalAdditionalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                    // Convert time period 2 to total seconds
                                                    $timePeriod2Seconds = ($workedHours * 3600) + ($workedMinutes * 60) + $workedSeconds + ($totalAdditionalMinutes * 60);

                                                    // Calculate the difference in seconds
                                                    $differenceSeconds = $timePeriod1Seconds - $timePeriod2Seconds;

                                                    // Convert the difference back to hours, minutes, and seconds
                                                    $differenceHours = floor($differenceSeconds / 3600);
                                                    $differenceMinutes = floor(($differenceSeconds % 3600) / 60);
                                                    $differenceSeconds = $differenceSeconds % 60;

                                                    $formattedDifferenceHours = $differenceHours > 0 ? "{$differenceHours} hr/s" : '';
                                                    $formattedDifferenceMinutes = $differenceMinutes > 0 ? "{$differenceMinutes} min" : '';
                                                    $formattedDifferenceSeconds = $differenceSeconds > 0 ? "{$differenceSeconds} sec" : '';

                                                    // Combine formatted parts for difference
                                                    $formattedDifference = trim("{$formattedDifferenceHours} {$formattedDifferenceMinutes} {$formattedDifferenceSeconds}");
                                                    $formattedDifference = empty($formattedDifference) ? '0' : $formattedDifference;
                                                @endphp

                                                {{ $formattedDifference}}
                                            </td>
                                            <td class="text-black border border-gray-400 text-xs">
                                                <!-- this is total hour required -->
                                                <!-- {{ $attendance->hours_perDay }} hr/s -->
                                                @php
                                                    // Assuming $attendance->hours_perDay is in decimal format
                                                    $totalHours = $attendance->hours_perDay;
                                                    $hours = floor($totalHours);
                                                    $minutes = floor(($totalHours - $hours) * 60);
                                                    $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                    $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                    $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                    $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                    $result = "{$formattedHours}, {$formattedMinutes}";
                                                @endphp

                                                {{ $result }}
                                            </td>
                                            <td class="text-red-500 border uppercase border-gray-400 text-xs font-bold w-32">
                                                @php
                                                    $lateDurationAM = $attendance->late_duration;
                                                    $lateDurationPM = $attendance->late_durationPM;
                                                    $am = $attendance->undertimeAM ?? 0;
                                                    $pm = $attendance->undertimePM ?? 0;

                                                    $totalHoursAM = floor($attendance->hours_workedAM);
                                                    $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                    $totalHoursPM = floor($attendance->hours_workedPM);
                                                    $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                    $totalHours = $totalHoursAM + $totalHoursPM;
                                                    $totalMinutes = $totalMinutesAM + $totalMinutesPM;
                                                    $modify_status = $attendance->modify_status;
                                                    $firstCheckInStatus = $attendance->firstCheckInStatus;
                                                    $firstCheckOutStatus = $attendance->firstCheckOutStatus;
                                                    $secondCheckInStatus = $attendance->secondCheckInStatus;
                                                    $secondCheckOutStatus = $attendance->secondCheckOutStatus;

                                                    $remarkss = '';

                                                    if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "Absent"
                                                    ) {
                                                        $remarkss = 'Absent';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "On Leave"
                                                    ) {
                                                        $remarkss = 'Leave';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM > 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        //$modify_status == "On Leave"
                                                        $firstCheckInStatus == "On Leave" &&
                                                        $firstCheckOutStatus == "On Leave" && 
                                                        $secondCheckInStatus == "On Leave" &&
                                                        $secondCheckOutStatus == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave Whole Day';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM > 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "Holiday"
                                                    ) {
                                                        $remarkss = 'Holiday';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM > 0 &&
                                                        $totalMinutesAM > 0 ||
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0) &&
                                                        $modify_status == "Official Travel"
                                                    ) {
                                                        $remarkss = 'Official Travel';
                                                    }
                                                    
                                                    
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM > 0 &&
                                                        $totalMinutesAM > 0 ||
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0) &&
                                                        $modify_status == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave';
                                                    }
                                                        else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 ||
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM > 0) &&
                                                        $modify_status == "Official Travel"
                                                    ) {
                                                        $remarkss = 'Official Travel';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        ($totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 ||
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM > 0) &&
                                                        $modify_status == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave';
                                                    }
                                                    else if (
                                                        $firstCheckInStatus == "On Leave" &&
                                                        $firstCheckOutStatus == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave AM - Present PM';
                                                    }
                                                    else if (
                                                        $secondCheckInStatus == "On Leave" &&
                                                        $secondCheckOutStatus == "On Leave"
                                                    ) {
                                                        $remarkss = 'On Leave PM - Present AM';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        $am == 0 &&
                                                        $pm == 0 &&
                                                        $totalHoursAM > 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM > 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        //$modify_status == "Official Travel"
                                                        $firstCheckInStatus == "Official Travel" &&
                                                        $firstCheckOutStatus == "Official Travel" && 
                                                        $secondCheckInStatus == "Official Travel" &&
                                                        $secondCheckOutStatus == "Official Travel"
                                                    ) {
                                                        $remarkss = 'On Official Travel Whole Day';
                                                    }
                                                    else if (
                                                        $firstCheckInStatus == "Official Travel" &&
                                                        $firstCheckOutStatus == "Official Travel"
                                                    ) {
                                                        $remarkss = 'On Official Travel AM - Present PM';
                                                    }
                                                    else if (
                                                        $secondCheckInStatus == "Official Travel" &&
                                                        $secondCheckOutStatus == "Official Travel"
                                                    ) {
                                                        $remarkss = 'On Official Travel PM - Present AM';
                                                    }
                                                    else if (
                                                        $lateDurationAM == 0 &&
                                                        $lateDurationPM == 0 &&
                                                        ($am == 0 || $am > 0) &&
                                                        ($pm == 0 || $pm > 0)  &&
                                                        $totalHoursAM == 0 &&
                                                        $totalMinutesAM == 0 &&
                                                        $totalHoursPM == 0 &&
                                                        $totalMinutesPM == 0 &&
                                                        $modify_status == "On-campus"
                                                    ) {
                                                        $remarkss = 'Invalid Attendance';
                                                    }
                                                    else {
                                                            if ($totalHoursPM == null && $totalMinutesPM == null && $totalHoursAM == 0 && $totalMinutesAM == 0 && $modify_status == "Weekend") {
                                                                    $remarkss = "Absent";
                                                                } 
                                                                else if ($totalHoursAM == null && $totalMinutesAM == null && $modify_status == "On-campus") {
                                                                    $remarkss = "Present";
                                                                } 
                                                            else if ($totalHoursAM == 0 && $totalMinutesAM == 0) {
                                                                $remarkss = "Present Afternoon, Absent Morning";
                                                            }
                                                            else if ($totalHoursPM == 0 && $totalMinutesPM == 0) {
                                                                $remarkss = "Present Morning, Absent Afternoon";
                                                            }
                                                            else {
                                                                if ($lateDurationAM > 0 && $lateDurationPM > 0) {
                                                                    $remarkss = 'Present - Late AM & PM';
                                                                } elseif ($lateDurationAM > 0) {
                                                                    $remarkss = 'Present - Late AM';
                                                                } elseif ($lateDurationPM > 0) {
                                                                    $remarkss = 'Present - Late PM';
                                                                }
                                                                    else {
                                                                    $remarkss = "Present";
                                                                }
                                                            }

                                                        $undertimeRemark = '';
                                                        if ($am > 0) {
                                                            $undertimeRemark .= 'Undertime AM';
                                                        }
                                                        if ($pm > 0) {
                                                            if (!empty($undertimeRemark)) {
                                                                $undertimeRemark .= ' & PM';
                                                            } else {
                                                                $undertimeRemark .= 'Undertime PM';
                                                            }
                                                        }
                                                        if (!empty($undertimeRemark)) {
                                                            $remarkss .= ' - ' . $undertimeRemark;
                                                        }
                                                    }
                                                @endphp

                                                    @if ($remarkss === 'Present')
                                                        <span class="text-black">{{ $remarkss }}</span>
                                                    @else
                                                        <span class="text-red-500">{{ $remarkss }}</span>
                                                    @endif
                                                </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- end -->
                        </div>
                        <div  x-show="tab === 'modify_date'" class="w-full">
                            <!-- Table for Computed Working Hours -->
                            <div class="w-full">
                                <div x-data="{ activeTab: 'form2' }" class="w-[50%] mb-4 mx-auto mt-8">
                                    <!-- Tabs -->
                                    <div class="flex justify-between mb-4">
                                        <button @click="activeTab = 'form1'" :class="{'bg-blue-500 text-white': activeTab === 'form1'}" class="w-[48%] py-2 px-4 rounded-md text-center border border-black">
                                            For Half Day Leave
                                        </button>
                                        <button @click="activeTab = 'form2'" :class="{'bg-blue-500 text-white': activeTab === 'form2'}" class="w-[48%] py-2 px-4 rounded-md text-center border border-black">
                                            For Full Day Leave
                                        </button>
                                    </div>

                                    <!-- Form 1 -->
                                    <div x-show="activeTab === 'form1'" class="w-full">
                                        <form action="{{ route('admin.attendance.modify.halfDay') }}" method="POST" class="w-full">
                                            <x-caps-lock-detector />
                                            @csrf

                                            <br>
                                            <p class="text-[14px]">
                                                <text class="text-red-500">Note:</text> The half day leave is the same as a full day leave if the working hours are not half day.
                                            </p>
                                            <br>
                                            <div class="mb-2 hidden">
                                                <label for="selected-date" class="block mb-2 text-left">Employee:</label>
                                                <input type="text" name="employee_id" value="{{ $selectedEmployeeToShow->id }}" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full max-w-md">
                                            </div>

                                            <div x-data="{ selectedDate: '', dayOfWeekNumber: '' }" class="mb-2">
                                                <label for="selected-date" class="block mb-2 text-left">Select a Date:</label>
                                                <input 
                                                    type="date" 
                                                    id="selected-date" 
                                                    name="selected_date" 
                                                    class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full"
                                                    x-model="selectedDate"
                                                    @change="dayOfWeekNumber = new Date(selectedDate).getDay()"
                                                    wire:model="selected_date"
                                                >

                                                <div class="">
                                                    <label for="day-of-week" class="block mb-2 text-left">Day of the Week:</label>
                                                    <input 
                                                        type="text" 
                                                        id="day-of-week" 
                                                        name="day_of_week" 
                                                        class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full"
                                                        x-model="dayOfWeekNumber"
                                                        readonly
                                                        wire:model="dayOfTheWeek"
                                                    >
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-gray-700 text-md font-bold mb-2 text-left">Select Shift:</label>
                                                <div class="flex space-x-4">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" id="am_shift" name="am_shift" class="mr-2" onchange="updateStatus()">
                                                        <label for="am_shift">AM Shift</label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input type="checkbox" id="pm_shift" name="pm_shift" class="mr-2" onchange="updateStatus()">
                                                        <label for="pm_shift">PM Shift</label>
                                                    </div>
                                                </div>
                                                <!-- Hidden inputs for checkboxes -->
                                                <input type="hidden" name="am_shift" id="am_shift_hidden" value="0">
                                                <input type="hidden" name="pm_shift" id="pm_shift_hidden" value="0">
                                            </div>

                                            <!-- Status Dropdown -->
                                            <div class="mb-4">
                                                <label for="status" class="block text-gray-700 text-md font-bold mb-2 text-left">Status:</label>
                                                <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" required>
                                                    <option value="On Leave">On Leave</option>
                                                    <option value="Official Travel">Official Travel</option>
                                                    <!-- <option value="Sick Leave">Sick Leave</option> -->
                                                    <!-- Add other options as needed -->
                                                </select>
                                            </div>

                                            <div class="flex mb-4 mt-10 justify-center">
                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Form 2 -->
                                    <div x-show="activeTab === 'form2'" class="w-full">
                                        <form action="{{ route('admin.attendance.modify') }}" method="POST" class="w-[78%] mx-auto">
                                            <x-caps-lock-detector />
                                            @csrf
                                            <br>
                                            <p class="text-[14px]">
                                                <text class="text-red-500">Note:</text> Full Day leave is based on the set working hour of employee's department.
                                            </p>
                                            <br>
                                            <div class="mb-2 hidden">
                                                <label for="selected-date" class="block mb-2 text-left">Employee:</label>
                                                <input type="text" name="employee_id" value="{{ $selectedEmployeeToShow->id }}" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full max-w-md">
                                            </div>
                                            <div class="mb-2">
                                                <label for="selected-date" class="block mb-2 text-left">Select a Date:</label>
                                                <input type="date" id="selected-date" name="selected_date" class="block mx-auto mb-4 p-2 border border-gray-300 rounded w-full">
                                            </div>
                                            <div class="mb-2">
                                                <label for="school_id" class="block text-gray-700 text-md font-bold mb-2 text-left">Status: </label>
                                                <select id="school_id" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:shadow-outline" required>
                                                    <option value="">Select Status</option>
                                                    <option value="On Leave">On Leave</option>
                                                    <option value="Official Travel">Official Travel</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                                            </div>
                                            <div class="flex mb-4 mt-10 justify-center">
                                                <button type="submit" class="w-80 bg-blue-500 text-white px-4 py-2 rounded-md">
                                                    Save Leave
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end -->
                        </div>
                        <div x-show="tab === 'reports'" class="w-full">
                            <div class="flex justify-center mt-8 w-full">
                                <div class="flex   justify-center w-full">
                                    <div class="flex flex-col w-full">
                                        <!-- <p>Overall Total Hours: {{ round($overallTotalHours,2) }}</p> -->
                                            @php
                                            // Group data by employee_id
                                            $employees = [];

                                            foreach ($attendanceData as $attendance) {

                                                
                                                $employeeId = $attendance->employee_id;
                                                $check = $attendance->check_in_time;
                                                if (!isset($employees[$employeeId])) {
                                                    $employees[$employeeId] = [
                                                        'totalHours' => 0,
                                                        'total_hours_worked' => 0,
                                                        'hours_late_overall' => 0,
                                                        'hours_undertime_overall' => 0,
                                                        'employee_idd' => $attendance->employee_idd,
                                                        'uniqueDays' => []
                                                    ];
                                                }

                                                // Accumulate totals for each employee
                                                $employees[$employeeId]['totalHours'] += $attendance->hours_perDay;
                                                $employees[$employeeId]['total_hours_worked'] += $attendance->total_hours_worked;
                                                $employees[$employeeId]['hours_late_overall'] += $attendance->hours_late_overall; // Replace with actual late hours field
                                                $employees[$employeeId]['hours_undertime_overall'] += $attendance->hours_undertime_overall; // Replace with actual undertime field
                                            
                                                $date = \Illuminate\Support\Carbon::parse($attendance->check_in_time)->toDateString();
                                                $employees[$employeeId]['uniqueDays'][$date] = true;
                                            }
                                        @endphp
                                        @foreach($employees as $employeeId => $employeeData)
                                            @php
                                                // Total hours
                                                $totalSeconds = $employeeData['totalHours'] * 3600;
                                                $hours = floor($totalSeconds / 3600);
                                                $minutes = floor(($totalSeconds % 3600) / 60);
                                                $seconds = $totalSeconds % 60;


                                                $totalSecondsWorked = $employeeData['total_hours_worked'] * 3600;
                                                $overallhours = floor($totalSecondsWorked / 3600);
                                                $overallminutes = floor(($totalSecondsWorked % 3600) / 60);
                                                $overallseconds = $totalSecondsWorked % 60;

                                                if ($overallseconds == 59) {
                                                    $overallminutes += 1;
                                                    $overallseconds = 0;
                                                }

                                                // If minutes exceed 59, convert to hours
                                                if ($overallminutes >= 60) {
                                                    $overallhours += floor($overallminutes / 60);
                                                    $overallminutes = $overallminutes % 60;
                                                }

                                                $formattedTimeWorked = 
                                                    ($overallhours > 0 ? "{$overallhours} hr/s, " : '0 hr/s, ') .
                                                    ($overallminutes > 0 ? "{$overallminutes} min/s " : '0 min/s, ') .
                                                    ($overallseconds > 0 ? "{$overallseconds} sec" : '0 sec');

                                                // Total late
                                                $totalSecondsM = $employeeData['hours_late_overall'] * 3600;
                                                $hoursM = floor($totalSecondsM / 3600);
                                                $minutesM = floor(($totalSecondsM % 3600) / 60);
                                                $secondsM = $totalSecondsM % 60;

                                                $totalLateSeconds = $totalSeconds - $totalSecondsWorked;
                                                $totalLateHours = floor($totalLateSeconds / 3600);
                                                $totalLateMinutes = floor(($totalLateSeconds % 3600) / 60);
                                                $totalLateSeconds = $totalLateSeconds % 60;

                                                $latee = 
                                                    ($totalLateHours > 0 ? "{$totalLateHours} hr/s, " : '0 hr/s, ') .
                                                    ($totalLateMinutes > 0 ? "{$totalLateMinutes} min/s " : '0 min/s, ') .
                                                    ($totalLateSeconds > 0 ? "{$totalLateSeconds} sec" : '0 sec');
                                                

                                                // Total undertime
                                                $undertimeInSeconds = $employeeData['hours_undertime_overall'] * 60;
                                                $undertimeHours = intdiv($undertimeInSeconds, 3600);
                                                $remainingSeconds = $undertimeInSeconds % 3600;
                                                $undertimeMinutes = intdiv($remainingSeconds, 60);
                                                $undertimeSeconds = $remainingSeconds % 60;

                                                // Format the undertime
                                                $undertimeFormatted = 
                                                    ($undertimeHours > 0 ? "{$undertimeHours} hr/s, " : '0 hr/s, ') .
                                                    ($undertimeMinutes > 0 ? "{$undertimeMinutes} min/s " : '0 min/s, ') .
                                                    ($undertimeSeconds > 0 ? "{$undertimeSeconds} sec" : '0 sec');

                                                // Format total hours
                                                //$totalFormatted = 
                                                    // ($hours > 0 ? "{$hours} hr/s, " : '0 hr/s, ') .
                                                    // ($minutes > 0 ? "{$minutes} min/s " : '0 min/s, ');

                                                $totalFormatted = '';

                                                if ($hours > 0) {
                                                    $totalFormatted .= "{$hours} hr/s";
                                                }

                                                if ($minutes > 0) {
                                                    $totalFormatted .= ($hours > 0 ? ', ' : '') . "{$minutes} min/s";
                                                } elseif ($hours > 0) {
                                                    // Include a comma if hours are present but no minutes
                                                    $totalFormatted .= '';
                                                } else {
                                                    // If there are no hours and no minutes, ensure the format is '0 hr/s, 0 min/s'
                                                    $totalFormatted = '0 hr/s, 0 min/s';
                                                }

                                                // Add seconds if needed
                                                $totalFormatted .= $seconds > 0 ? " {$seconds} sec" : '';

                                                // Format total late
                                                $lateFormatted = 
                                                    ($hoursM > 0 ? "{$hoursM} hr/s, " : '0 hr/s, ') .
                                                    ($minutesM > 0 ? "{$minutesM} min/s " : '0 min/s, ') .
                                                    ($secondsM > 0 ? "{$secondsM} sec" : '0 sec');

                                                    $attendanceDaysCount = count($employeeData['uniqueDays']);

                                                    $rtotal = $totalSecondsWorked + $totalSecondsM + $undertimeInSeconds;
                                                $absentSecondss = $totalSeconds - $rtotal;

                                                // Convert absence seconds to hours, minutes, and seconds
                                                $absentHours = floor($absentSecondss / 3600);
                                                $remainingSeconds = $absentSecondss % 3600;
                                                $absentMinutes = floor($remainingSeconds / 60);
                                                $absentSeconds = $remainingSeconds % 60;

                                                // If seconds are 59, round up the minutes
                                                if ($absentSeconds == 59) {
                                                    $absentMinutes += 1;
                                                    $absentSeconds = 0;
                                                }

                                                // If minutes are 60, convert them to an hour
                                                if ($absentMinutes == 60) {
                                                    $absentHours += 1;
                                                    $absentMinutes = 0;
                                                }

                                                // Format the absence time
                                                $absentFormatted = 
                                                    ($absentHours > 0 ? "{$absentHours} hr/s" : '') .
                                                    (($absentHours > 0 && $absentMinutes > 0) ? ", " : '') . 
                                                    ($absentMinutes > 0 ? "{$absentMinutes} min/s" : '') .
                                                    (($absentMinutes > 0 && $absentSeconds > 0) ? " " : '') . 
                                                    ($absentSeconds > 0 ? "{$absentSeconds} sec" : ($absentHours <= 0 && $absentMinutes <= 0 ? ' 0 ' : ''));

                                                // Add the comma and space between the values
                                                $absentFormatted = trim($absentFormatted, ', ');



                                                $finalDeduction = $totalSecondsM + $undertimeInSeconds + $absentSecondss;

                                                // Calculate final hour deduction
                                                $finalHourDeductionHours = floor($finalDeduction / 3600);
                                                $finalDeductionRemainingSeconds = $finalDeduction % 3600;
                                                $finalHourDeductionMinutes = floor($finalDeductionRemainingSeconds / 60);
                                                $finalHourDeductionSeconds = $finalDeductionRemainingSeconds % 60;

                                                // Format final hour deduction
                                                $finalHourDeductionFormatted = 
                                                    ($finalHourDeductionHours > 0 ? "{$finalHourDeductionHours} hr/s, " : '0 hr/s, ') .
                                                    ($finalHourDeductionMinutes > 0 ? "{$finalHourDeductionMinutes} min/s " : '0 min/s, ') .
                                                    ($finalHourDeductionSeconds > 0 ? "{$finalHourDeductionSeconds} sec" : '0 sec');
                                                

                                            @endphp

                                            <div x-data="{ loading: false, open: {{ session()->has('success') ? 'true' : 'false' }} }"
                                                x-init="() => {
                                                    if (open) {
                                                        loading = false;
                                                        setTimeout(() => open = false, 3000); // Automatically close the modal after 3 seconds
                                                    }
                                                }"
                                                @export-success.window="loading = false; open = true">

                                                
                                                <div x-cloak x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
                                                        <h2 class="text-xl font-semibold mb-4">Download Info</h2>
                                                        <p>{{ session()->get('success') }}</p>
                                                        <div class="flex justify-end mt-4">
                                                            <button @click="open = false" class="px-4 py-2 bg-blue-500 text-white rounded">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Loader -->
                                                <div x-show="loading && !open" 
                                                    x-transition:enter="transition ease-out duration-300" 
                                                    x-transition:enter-start="opacity-0" 
                                                    x-transition:enter-end="opacity-100" 
                                                    x-transition:leave="transition ease-in duration-200" 
                                                    x-transition:leave-start="opacity-100" 
                                                    x-transition:leave-end="opacity-0"
                                                    class="fixed inset-0 flex flex-col items-center justify-center bg-gray-800 bg-opacity-50 z-50">
                                                    
                                                    <!-- Container for the loader and text -->
                                                    <div class="flex flex-col items-center">
                                                        <!-- Rotating Spinner Loader -->
                                                        <div class="w-16 h-16 border-4 border-t-4 border-white border-solid rounded-full animate-spin"></div>
                                                        
                                                        <!-- Optional Loading Text -->
                                                        
                                                    </div>
                                                </div>

                                                @if ($startDate && $endDate)
                                                    <p>Selected Date Range:</p>
                                                    <div class="flex justify-between -mt-4">
                                                        <p class="py-4 text-red-500">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} &nbsp; to &nbsp; {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
                                                        <!-- <div class="">
                                                            <button 
                                                                x-on:click="loading = true" 
                                                                wire:click="generateExcel" 
                                                                wire:loading.attr="disabled" 
                                                                wire:loading.class="cursor-wait"
                                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                <i class="fa-solid fa-file"></i> Export Employee Attendance Report for Selected Date to Excel
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                @else
                                                    <p>Selected Date Range:</p>
                                                    <div class="flex justify-between -mt-4">
                                                        <p class="py-4">No selected Date</p>
                                                        <!-- <div class="">
                                                            <button 
                                                                x-on:click="loading = true" 
                                                                wire:click="generateExcel" 
                                                                wire:loading.attr="disabled" 
                                                                wire:loading.class="cursor-wait"
                                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                <i class="fa-solid fa-file"></i> Export All Dept. Employees Attendance Report to Excel
                                                            </button>
                                                        </div> -->
                                                    </div>
                                                @endif
                                            </div>
                                            <table class="border border-black h-full" cellpadding="2">
                                                <tr class="text-sm">
                                                    <th class="border border-black text-center">Duty Hours To Be Rendered</th>
                                                    <th class="border border-black text-center">Total Time Rendered</th>
                                                    <th class="border border-black text-center">Total Time Deduction (late + undertime + absent)</th>
                                                    <th class="border border-black text-center">Total Late</th>
                                                    <th class="border border-black text-center">Total Undertime</th>
                                                    <th class="border border-black text-center">Total Absent</th>
                                                    <th class="border border-black text-center">Action</th>
                                                </tr>
                                                    <tr class="border border-black text-sm  hover:border hover:bg-gray-200">
                                                    <!-- <td class="text-black border border-black text-center">
                                                        {{ $employeeData['employee_idd'] }}
                                                    </td> -->
                                                    <td class="text-black border border-black">{{ $totalFormatted }}  from ({{ $attendanceDaysCount }} days worked)</td>
                                                    <td class="text-black border border-black">{{$formattedTimeWorked}}</td>
                                                    <td class="text-black border border-black">{{ $finalHourDeductionFormatted }}</td>
                                                    <td class="text-black border border-black">{{ $lateFormatted }}</td>
                                                    <td class="text-black border border-black">{{ $undertimeFormatted }}</td>
                                                    <td class="text-black border border-black text-center">{{ $absentFormatted }}</td>
                                                    <td class="text-black border border-black">
                                                        <div class="flex justify-center items-center space-x-2 p-2 z-50">
                                                            <div x-data="{ open: false }">
                                                                <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-sm px-2 py-1 rounded hover:bg-blue-700">
                                                                    <i class="fa-solid fa-pen fa-xs" style="color: #ffffff;"></i> View Calculation
                                                                </a>
                                                                <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                                                    <div @click.away="open = false" class=" w-[80%] max-h-[90vh] bg-white p-6 rounded-lg shadow-lg  mx-auto overflow-y-auto">
                                                                        <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                            <p class="text-xl font-bold">Detailed Calculation of Work Hours (<text class="text-red-500 text-sm">Dates that are missing or excluded may be weekends or holidays</text>)</p>
                                                                            <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                        </div>
                                                                        <div class="w-full">
                                                                            <!-- <h3 class="text-center text-lg font-semibold uppercase mb-2 mt-6">Calculation of Work Hours</h3> -->
                                                                                <p> Employee: <text class="text-red-500">{{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text></p>
                                                                            @if ($startDate && $endDate)
                                                                                <p>Selected Date Range:</p>
                                                                                <div class="flex justify-between -mt-4">
                                                                                    
                                                                                    <p class="py-4 text-red-500">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} &nbsp; to &nbsp; {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
                                                                                    <!-- <div class="">
                                                                                        <button wire:click="generateExcel" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Export to Excel
                                                                                        </button>
                                                                                        <button wire:click="generatePDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Generate DTR | PDF
                                                                                        </button>
                                                                                    </div> -->
                                                                                </div>
                                                                            @else
                                                                                <p>Selected Date Range:</p>
                                                                                <div class="flex justify-between -mt-4">
                                                                                    
                                                                                    <p class="py-4">Start Date: None selected &nbsp;&nbsp;End Date: None selected</p>
                                                                                    <!-- <div class="">
                                                                                        <button wire:click="generateExcel" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Export to Excel
                                                                                        </button>
                                                                                        <button wire:click="generatePDF" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                                                            <i class="fa-solid fa-file"></i> Generate DTR | PDF
                                                                                        </button>
                                                                                    </div> -->
                                                                                </div>
                                                                            @endif
                                                                            <table class="table-auto min-w-full text-center text-xs mb-4 divide-y divide-gray-200">
                                                                                <thead class="bg-gray-200 text-black">
                                                                                    <tr>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Date</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase" >Time In</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Time Out</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Late AM | PM</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Late</th>
                                                                                        <!-- <th class="border border-gray-400 px-2 py-1">PM Late</th> -->
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">UnderTime AM | PM</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Undertime</th>
                                                                                        <!-- <th class="border border-gray-400 px-2 py-1">PM UnderTime</th> -->
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours AM & PM</th>
                                                                                        <!-- <th class="border border-gray-400 px-2 py-1">Total PM Hours</th> -->
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Hours Rendered</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Deduction (late + undertime)</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Total Absent</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Required Hours</th>
                                                                                        <th class="border border-gray-400 px-2 py-1 uppercase">Remarks</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @php
                                                                                        $groupedAttendance = [];

                                                                                        // Group check-in times
                                                                                        foreach ($attendanceTimeIn as $attendanceIn) {
                                                                                            $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                                                                                            $employeeId = $attendanceIn->employee->employee_id;
                                                                                            $status = $attendanceIn->status;

                                                                                            if (!isset($groupedAttendance[$employeeId])) {
                                                                                                $groupedAttendance[$employeeId] = [];
                                                                                            }

                                                                                            if (!isset($groupedAttendance[$employeeId][$date])) {
                                                                                                $groupedAttendance[$employeeId][$date] = [
                                                                                                    'date' => date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)),
                                                                                                    'check_ins' => [],
                                                                                                    'check_outs' => [],
                                                                                                    'status' => $status,
                                                                                                ];
                                                                                            }

                                                                                            $groupedAttendance[$employeeId][$date]['check_ins'][] = date('g:i:s A', strtotime($attendanceIn->check_in_time));
                                                                                        }

                                                                                        // Group check-out times
                                                                                        foreach ($attendanceTimeOut as $attendanceOut) {
                                                                                            $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                                                                                            $employeeId = $attendanceOut->employee->employee_id;
                                                                                            $status = $attendanceOut->status;

                                                                                            if (!isset($groupedAttendance[$employeeId])) {
                                                                                                $groupedAttendance[$employeeId] = [];
                                                                                            }

                                                                                            if (!isset($groupedAttendance[$employeeId][$date])) {
                                                                                                $groupedAttendance[$employeeId][$date] = [
                                                                                                    'date' => date('m-d-Y, (l)', strtotime($attendanceOut->check_out_time)),
                                                                                                    'check_ins' => [],
                                                                                                    'check_outs' => [],
                                                                                                    'status' => $status,
                                                                                                ];
                                                                                            }

                                                                                            $groupedAttendance[$employeeId][$date]['check_outs'][] = date('g:i:s A', strtotime($attendanceOut->check_out_time));
                                                                                        }
                                                                                    @endphp
                                                                                    @foreach ($attendanceData as $attendance)
                                                                                        @php
                                                                                            $workedDate = date('Y-m-d', strtotime($attendance->worked_date));
                                                                                        @endphp
                                                                                    <tr class="hover:border hover:bg-gray-200">
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 font-bold">{{ date('M d, Y (D)', strtotime($attendance->worked_date)) }}</td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-28">
                                                                                            @foreach ($groupedAttendance as $employeeId => $dates)
                                                                                                @foreach ($dates as $date => $attendance1)
                                                                                                    @if ($date === $workedDate)
                                                                                                        {{-- Handle 1st check-in --}}
                                                                                                        @if (!empty($attendance1['check_ins'][0]))
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            <text class="text-red-500">1ST TIME IN:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $firstCheckIn = $attendance1['check_ins'][0];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($firstCheckIn)) === '00:00:00' || empty($firstCheckIn))
                                                                                                                <text class="text-red-500">No 1st Check-In</text>
                                                                                                            @else
                                                                                                                {{ $firstCheckIn }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="text-red-500">No 1st Check-In</p>
                                                                                                        @endif

                                                                                                        {{-- Handle 2nd check-in --}}
                                                                                                        @if (!empty($attendance1['check_ins'][1]))
                                                                                                            <br><br>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            <text class="text-blue-500">2ND TIME IN:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $secondCheckIn = $attendance1['check_ins'][1];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($secondCheckIn)) === '00:00:00' || empty($secondCheckIn))
                                                                                                                <text class="text-red-500">No 2nd Check-In</text>
                                                                                                            @else
                                                                                                                {{ $secondCheckIn }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="mt-10 text-red-500">No 2nd Check-In</p>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endforeach
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-32">
                                                                                            @foreach ($groupedAttendance as $employeeId => $dates)
                                                                                                @foreach ($dates as $date => $attendance1)
                                                                                                    @if ($date === $workedDate)
                                                                                                        {{-- Handle 1st check-out --}}
                                                                                                        @if (!empty($attendance1['check_outs'][0]))
                                                                                                            <hr style="border: none; border-top: 1px solid #000;">
                                                                                                            <text class="text-red-500">1ST TIME OUT:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $firstCheckOut = $attendance1['check_outs'][0];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($firstCheckOut)) === '00:00:00')
                                                                                                                <text class="text-red-500">NO TIME OUT</text>
                                                                                                            @else
                                                                                                                {{ $firstCheckOut }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="text-red-500">No 1st Check-Out</p>
                                                                                                        @endif

                                                                                                        {{-- Handle 2nd check-out --}}
                                                                                                        @if (!empty($attendance1['check_outs'][1]))
                                                                                                            <br><br>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            <text class="text-blue-500">2ND TIME OUT:</text>
                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                            
                                                                                                            @php
                                                                                                                $secondCheckOut = $attendance1['check_outs'][1];
                                                                                                            @endphp

                                                                                                            @if (date('H:i:s', strtotime($secondCheckOut)) === '00:00:00')
                                                                                                                <text class="text-red-500">NO TIME OUT</text>
                                                                                                            @else
                                                                                                                {{ $secondCheckOut }}
                                                                                                            @endif
                                                                                                        @else
                                                                                                            <p class="mt-10 text-red-500">No 2nd Check-Out</p>
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endforeach
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                                                            <!-- THIS IS PM AND AM LATE DURATION -->
                                                                                            @php
                                                                                                // Calculate late duration in minutes for AM
                                                                                                $lateDurationInMinutesAM = $attendance->late_duration;

                                                                                                // Calculate late hours, minutes, and seconds for AM
                                                                                                $lateHoursAM = intdiv($lateDurationInMinutesAM, 60);
                                                                                                $lateMinutesAM = $lateDurationInMinutesAM % 60;
                                                                                                $lateSecondsAM = ($lateDurationInMinutesAM - floor($lateDurationInMinutesAM)) * 60;

                                                                                                // Round seconds to avoid precision issues for AM
                                                                                                $lateSecondsAM = round($lateSecondsAM);

                                                                                                // Format the late duration string for AM
                                                                                                $lateDurationFormattedAM = ($lateHoursAM > 0 ? "{$lateHoursAM} hr " : '') 
                                                                                                                        . ($lateMinutesAM > 0 ? "{$lateMinutesAM} min " : '')
                                                                                                                        . ($lateSecondsAM > 0 ? "{$lateSecondsAM} sec" : '');

                                                                                                // If the formatted string is empty for AM, ensure we show "0"
                                                                                                $lateDurationFormattedAM = $lateDurationFormattedAM ?: '0 sec';

                                                                                                // Calculate late duration in minutes for PM
                                                                                                $lateDurationInMinutesPM = $attendance->late_durationPM;

                                                                                                // Calculate late hours, minutes, and seconds for PM
                                                                                                $lateHoursPM = intdiv($lateDurationInMinutesPM, 60);
                                                                                                $lateMinutesPM = $lateDurationInMinutesPM % 60;
                                                                                                $lateSecondsPM = ($lateDurationInMinutesPM - floor($lateDurationInMinutesPM)) * 60;

                                                                                                // Round seconds to avoid precision issues for PM
                                                                                                $lateSecondsPM = round($lateSecondsPM);

                                                                                                // Format the late duration string for PM
                                                                                                $lateDurationFormattedPM = ($lateHoursPM > 0 ? "{$lateHoursPM} hr " : '') 
                                                                                                                        . ($lateMinutesPM > 0 ? "{$lateMinutesPM} min " : '')
                                                                                                                        . ($lateSecondsPM > 0 ? "{$lateSecondsPM} sec" : '');

                                                                                                // If the formatted string is empty for PM, ensure we show "0"
                                                                                                $lateDurationFormattedPM = $lateDurationFormattedPM ?: '0 sec';
                                                                                            @endphp

                                                                                            @if (!empty($lateDurationInMinutesAM) && !empty($lateDurationInMinutesPM))
                                                                                                <div class="mt-2" >
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM LATE:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $lateDurationFormattedAM }}
                                                                                                </div>

                                                                                                <div class="mt-4">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM LATE</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $lateDurationFormattedPM }}
                                                                                                </div>
                                                                                            @elseif (!empty($lateDurationInMinutesAM))
                                                                                                <div class="-mt-6">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM LATE:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <br>
                                                                                                    {{ $lateDurationFormattedAM }}
                                                                                                </div>
                                                                                            @elseif (!empty($lateDurationInMinutesPM))
                                                                                                <div class="mt-1">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM LATE:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <br>
                                                                                                    {{ $lateDurationFormattedPM }}
                                                                                                </div>
                                                                                            @else
                                                                                                <p>No Late</p>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 w-24">
                                                                                            @php
                                                                                                // Total late time in minutes as a decimal
                                                                                                $totalLateMinutesDecimal = $attendance->total_late;

                                                                                                // Convert decimal minutes to total hours, minutes, and seconds
                                                                                                $totalLateHours = intdiv($totalLateMinutesDecimal, 60); // Total hours
                                                                                                $remainingMinutes = floor($totalLateMinutesDecimal % 60); // Remaining minutes
                                                                                                $totalLateSeconds = round(($totalLateMinutesDecimal - floor($totalLateMinutesDecimal)) * 60); // Total seconds

                                                                                                // Format the duration string
                                                                                                if ($totalLateMinutesDecimal > 0) {
                                                                                                    $totalLateDurationFormatted = 
                                                                                                        ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                                                                        ($remainingMinutes > 0 ? "{$remainingMinutes} mins " : '0 mins ') .
                                                                                                        ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                                                                } else {
                                                                                                    $totalLateDurationFormatted = '0';
                                                                                                }
                                                                                            @endphp

                                                                                            {{ $totalLateDurationFormatted }}
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 p-2 w-[134px]">
                                                                                            @php
                                                                                                // Calculate undertime in minutes for AM
                                                                                                $undertimeInMinutesAM = $attendance->undertimeAM;

                                                                                                // Convert minutes to total seconds for AM
                                                                                                $undertimeInSecondsAM = $undertimeInMinutesAM * 60;

                                                                                                // Convert total seconds to hours, minutes, and seconds for AM
                                                                                                $undertimeHoursAM = intdiv($undertimeInSecondsAM, 3600); // Total hours
                                                                                                $remainingSecondsAM = $undertimeInSecondsAM % 3600; // Remaining seconds after hours
                                                                                                $undertimeMinutesAM = intdiv($remainingSecondsAM, 60); // Total minutes
                                                                                                $undertimeSecondsAM = $remainingSecondsAM % 60; // Remaining seconds after minutes

                                                                                                // Format the undertime string for AM
                                                                                                $undertimeFormattedAM = 
                                                                                                    ($undertimeHoursAM > 0 ? "{$undertimeHoursAM} hr " : '') .
                                                                                                    ($undertimeMinutesAM > 0 ? "{$undertimeMinutesAM} min " : '0 min ') .
                                                                                                    ($undertimeSecondsAM > 0 ? "{$undertimeSecondsAM} sec" : '0 sec');

                                                                                                // Calculate undertime in minutes for PM
                                                                                                $undertimeInMinutesPM = $attendance->undertimePM;

                                                                                                // Convert minutes to total seconds for PM
                                                                                                $undertimeInSecondsPM = $undertimeInMinutesPM * 60;

                                                                                                // Convert total seconds to hours, minutes, and seconds for PM
                                                                                                $undertimeHoursPM = intdiv($undertimeInSecondsPM, 3600); // Total hours
                                                                                                $remainingSecondsPM = $undertimeInSecondsPM % 3600; // Remaining seconds after hours
                                                                                                $undertimeMinutesPM = intdiv($remainingSecondsPM, 60); // Total minutes
                                                                                                $undertimeSecondsPM = $remainingSecondsPM % 60; // Remaining seconds after minutes

                                                                                                // Format the undertime string for PM
                                                                                                $undertimeFormattedPM = 
                                                                                                    ($undertimeHoursPM > 0 ? "{$undertimeHoursPM} hr " : '') .
                                                                                                    ($undertimeMinutesPM > 0 ? "{$undertimeMinutesPM} min " : '0 min ') .
                                                                                                    ($undertimeSecondsPM > 0 ? "{$undertimeSecondsPM} sec" : '0 sec');
                                                                                            @endphp

                                                                                            @if (!empty($undertimeInMinutesAM) && !empty($undertimeInMinutesPM))
                                                                                                <div class="">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $undertimeFormattedAM }}
                                                                                                </div>

                                                                                                <div class="mt-4">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $undertimeFormattedPM }}
                                                                                                </div>
                                                                                                <!-- <table class="p-0 w-full m-0">
                                                                                                    <tr class="border border-red-500 h-full">
                                                                                                        <td >
                                                                                                            <div class="mt-3 ">
                                                                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                <span class="text-red-500">AM UNDERTIME:</span>
                                                                                                                <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                                {{ $undertimeFormattedAM }}
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr class="border border-red-500">
                                                                                                        <td>
                                                                                                            <div class="mt-4">
                                                                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                <span class="text-blue-500">PM UNDERTIME:</span>
                                                                                                                <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                                {{ $undertimeFormattedPM }}
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table> -->

                                                                                            @elseif (!empty($undertimeInMinutesAM))
                                                                                                <div>
                                                                                                    <text class="text-red-500">AM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    {{ $undertimeFormattedAM }}
                                                                                                </div>
                                                                                            @elseif (!empty($undertimeInMinutesPM))
                                                                                                <div class="mt-1">
                                                                                                    <text class="text-blue-500">PM UNDERTIME:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    {{ $undertimeFormattedPM }}
                                                                                                </div>
                                                                                            @else
                                                                                                <p>No Undertime</p>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1">
                                                                                            <!-- Undertime Area Total -->
                                                                                            @php
                                                                                                $am = $attendance->undertimeAM;
                                                                                                $pm = $attendance->undertimePM;
                                                                                                $totalUndertimeInMinutes = $am + $pm;

                                                                                                if ($totalUndertimeInMinutes > 0) {
                                                                                                    // Convert total minutes to total seconds
                                                                                                    $totalUndertimeInSeconds = $totalUndertimeInMinutes * 60;

                                                                                                    // Convert total seconds to hours, minutes, and seconds
                                                                                                    $totalLateHours = intdiv($totalUndertimeInSeconds, 3600); // Total hours
                                                                                                    $remainingSeconds = $totalUndertimeInSeconds % 3600; // Remaining seconds after hours
                                                                                                    $totalLateMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                                                                    $totalLateSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                                                                    // Format the duration string
                                                                                                    $totalLateDurationFormatted = 
                                                                                                        ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                                                                        ($totalLateMinutes > 0 ? "{$totalLateMinutes} mins " : '0 mins ') .
                                                                                                        ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                                                                } else {
                                                                                                    $totalLateDurationFormatted = '0';
                                                                                                }
                                                                                            @endphp

                                                                                            {{ $totalLateDurationFormatted }}
                                                                                        </td>
                                                                                        
                                                                                        <td class="text-black border border-gray-400 px-3 py-2 w-40">
                                                                                            @php
                                                                                                // Total hours worked in AM shift
                                                                                                $totalHoursAM = floor($attendance->hours_workedAM);
                                                                                                $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                                                                $totalSecondsAM = ($totalMinutesAM - floor($totalMinutesAM)) * 60;
                                                                                                $totalMinutesAM = floor($totalMinutesAM);

                                                                                                $finalHoursAM = $totalHoursAM;
                                                                                                $roundedMinutesAM = round($totalMinutesAM + ($totalSecondsAM / 60));
                                                                                                $finalSecondsAM = round($totalSecondsAM % 60);

                                                                                                if ($finalSecondsAM >= 59) {
                                                                                                    $finalSecondsAM = 0;
                                                                                                    $roundedMinutesAM += 1;
                                                                                                } else {
                                                                                                    $finalSecondsAM = 0;
                                                                                                }

                                                                                                if ($roundedMinutesAM >= 59) {
                                                                                                    $roundedMinutesAM = 0;
                                                                                                    $finalHoursAM += 1;
                                                                                                }

                                                                                                $finalMinutesAM = $roundedMinutesAM;

                                                                                                // Total hours worked in PM shift
                                                                                                $totalHoursPM = floor($attendance->hours_workedPM);
                                                                                                $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                                                                $totalSecondsPM = ($totalMinutesPM - floor($totalMinutesPM)) * 60;
                                                                                                $totalMinutesPM = floor($totalMinutesPM);

                                                                                                $finalHoursPM = $totalHoursPM + floor($totalMinutesPM / 60);
                                                                                                $finalMinutesPM = $totalMinutesPM % 60;
                                                                                                $finalSecondsPM = round($totalSecondsPM);

                                                                                                if ($finalSecondsPM == 60) {
                                                                                                    $finalSecondsPM = 0;
                                                                                                    $finalMinutesPM += 1;
                                                                                                }

                                                                                                if ($finalMinutesPM >= 60) {
                                                                                                    $finalMinutesPM = 0;
                                                                                                    $finalHoursPM += 1;
                                                                                                }
                                                                                            @endphp

                                                                                            @if ($attendance->hours_workedAM > 0 || $attendance->hours_workedPM > 0)
                                                                                                <div class="mt-2">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-red-500">AM WORKED:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $finalHoursAM }} hr/s. {{ $finalMinutesAM }} min. {{ $finalSecondsAM }} sec.
                                                                                                </div>

                                                                                                <div class="mt-4">
                                                                                                    <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                    <text class="text-blue-500">PM WORKED:</text>
                                                                                                    <hr style="border: none; border-top: 1px solid #000;" class="mb-2">
                                                                                                    {{ $finalHoursPM }} hrs. {{ $finalMinutesPM }} min. {{ $finalSecondsPM }} sec.
                                                                                                </div>
                                                                                            @else
                                                                                                <p>0</p>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-2 py-1 font-bold w-32">
                                                                                            @php
                                                                                                // Total hours worked in decimal format
                                                                                                $totalHoursWorked = $attendance->total_hours_worked;
                                                                                                
                                                                                                // Calculate hours and minutes
                                                                                                $totalHours = floor($totalHoursWorked);
                                                                                                $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                                                                
                                                                                                // Calculate the final hours, minutes, and seconds
                                                                                                $finalMinutes = floor($totalMinutes);
                                                                                                $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                                                                $finalSeconds = round($totalSeconds);
                                                                                                
                                                                                                // Handle case where seconds is 60
                                                                                                if ($finalSeconds == 60) {
                                                                                                    $finalSeconds = 0;
                                                                                                    $finalMinutes += 1;
                                                                                                }
                                                                                                
                                                                                                // Handle case where minutes exceed 59
                                                                                                if ($finalMinutes >= 60) {
                                                                                                    $finalMinutes = 0;
                                                                                                    $totalHours += 1;
                                                                                                }

                                                                                                // Format the duration string
                                                                                                if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                                                                    $totalHoursWorkedFormatted = '0';
                                                                                                } else {
                                                                                                    $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                                                                }
                                                                                            @endphp

                                                                                            {{ $totalHoursWorkedFormatted }}

                                                                                                    
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-3 py-2">

                                                                                            @php
                                                                                                // Total late time in minutes as a decimal
                                                                                                $totalLateMinutesDecimal = $attendance->total_late;

                                                                                                // Total undertime in minutes
                                                                                                $am = $attendance->undertimeAM;
                                                                                                $pm = $attendance->undertimePM;
                                                                                                $totalUndertimeInMinutes = $am + $pm;

                                                                                                // Combine late and undertime in minutes
                                                                                                $totalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                                                                // Convert total minutes to total seconds
                                                                                                $totalSeconds = $totalMinutes * 60;

                                                                                                // Convert total seconds to hours, minutes, and seconds
                                                                                                $totalHours = intdiv($totalSeconds, 3600); // Total hours
                                                                                                $remainingSeconds = $totalSeconds % 3600; // Remaining seconds after hours
                                                                                                $totalMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                                                                $totalSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                                                                // Format the duration string for total deduction
                                                                                                if ($totalMinutes > 0 || $totalLateMinutesDecimal > 0 || $totalUndertimeInMinutes > 0) {
                                                                                                    $totalDurationFormatted = 
                                                                                                        ($totalHours > 0 ? "{$totalHours} hr/s, " : '') .
                                                                                                        ($totalMinutes > 0 ? "{$totalMinutes} min/s, " : '0 min/s ') .
                                                                                                        ($totalSeconds > 0 ? "{$totalSeconds} sec" : '0 sec');
                                                                                                } else {
                                                                                                    $totalDurationFormatted = '0';
                                                                                                }

                                                                                                // Total hours worked in decimal format
                                                                                                $totalHoursWorked = $attendance->total_hours_worked;
                                                                                                
                                                                                                // Calculate hours and minutes
                                                                                                $totalHours = floor($totalHoursWorked);
                                                                                                $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                                                                
                                                                                                // Calculate the final hours, minutes, and seconds
                                                                                                $finalMinutes = floor($totalMinutes);
                                                                                                $totalSeconds = ($totalMinutes - $finalMinutes) * 60;
                                                                                                $finalSeconds = round($totalSeconds);
                                                                                                
                                                                                                // Handle case where seconds is 60
                                                                                                if ($finalSeconds == 60) {
                                                                                                    $finalSeconds = 0;
                                                                                                    $finalMinutes += 1;
                                                                                                }
                                                                                                
                                                                                                // Handle case where minutes exceed 59
                                                                                                if ($finalMinutes >= 60) {
                                                                                                    $finalMinutes = 0;
                                                                                                    $totalHours += 1;
                                                                                                }

                                                                                                // Format the duration string for total hours worked
                                                                                                if ($totalHours == 0 && $finalMinutes == 0 && $finalSeconds == 0) {
                                                                                                    $totalHoursWorkedFormatted = 'No total hours';
                                                                                                } else {
                                                                                                    $totalHoursWorkedFormatted = "{$totalHours} hrs. {$finalMinutes} min. {$finalSeconds} sec.";
                                                                                                }

                                                                                                // Use hours_perDay if totalHoursWorkedFormatted is 'No total hours'
                                                                                                if ($totalHoursWorkedFormatted === 'No total hours') {
                                                                                                    $hoursPerDay = $attendance->hours_perDay;
                                                                                                    $hours = floor($hoursPerDay);
                                                                                                    $minutes = floor(($hoursPerDay - $hours) * 60);
                                                                                                    $seconds = round((((($hoursPerDay - $hours) * 60) - $minutes) * 60));
                                                                                                    
                                                                                                    $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                                                                    $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                                                                    $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                                                                    $totalDurationFormatted = "{$formattedHours}, {$formattedMinutes}, {$formattedSeconds}";
                                                                                                }
                                                                                            @endphp
                                                                                            {{ $totalDurationFormatted }}

                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 px-3 py-2">
                                                                                            @php

                                                                                            $totalHours = $attendance->hours_perDay;
                                                                                                $hours = floor($totalHours);
                                                                                                $minutes = floor(($totalHours - $hours) * 60);
                                                                                                $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                                                                // Round minutes if seconds are 59
                                                                                                if ($seconds >= 59) {
                                                                                                    $minutes += 1;
                                                                                                    $seconds = 0;
                                                                                                }

                                                                                                // Format the result based on hours, minutes, and seconds
                                                                                                if ($hours === 0 && $minutes === 0 && $seconds === 0) {
                                                                                                    $formattedTime = '0';
                                                                                                } elseif ($hours === 0 && $minutes === 0) {
                                                                                                    $formattedTime = '0 sec';
                                                                                                } elseif ($hours === 0 && $seconds === 0) {
                                                                                                    $formattedTime = "{$minutes} min";
                                                                                                } elseif ($hours === 0) {
                                                                                                    $formattedTime = "{$minutes} min, {$seconds} sec";
                                                                                                } elseif ($minutes === 0 && $seconds === 0) {
                                                                                                    $formattedTime = "{$hours} hr/s";
                                                                                                } elseif ($minutes === 0) {
                                                                                                    $formattedTime = "{$hours} hr, {$seconds} sec";
                                                                                                } elseif ($seconds === 0) {
                                                                                                    $formattedTime = "{$hours} hr, {$minutes} min";
                                                                                                } else {
                                                                                                    $formattedTime = "{$hours} hr, {$minutes} min, {$seconds} sec";
                                                                                                }

                                                                                                // Time period 1 (formatted time)
                                                                                                $totalHours1 = $attendance->hours_perDay;
                                                                                                $hours1 = floor($totalHours1);
                                                                                                $minutes1 = floor(($totalHours1 - $hours1) * 60);
                                                                                                $seconds1 = round((((($totalHours1 - $hours1) * 60) - $minutes1) * 60));

                                                                                                // Convert time period 1 to total seconds
                                                                                                $timePeriod1Seconds = ($hours1 * 3600) + ($minutes1 * 60) + $seconds1;

                                                                                                // Time period 2 (total worked time)
                                                                                                $totalHoursWorked = $attendance->total_hours_worked;
                                                                                                $workedHours = floor($totalHoursWorked);
                                                                                                $totalMinutes = ($totalHoursWorked - $workedHours) * 60;
                                                                                                $workedMinutes = floor($totalMinutes);
                                                                                                $workedSeconds = round(($totalMinutes - $workedMinutes) * 60);

                                                                                                // Total late and undertime in minutes
                                                                                                $totalLateMinutesDecimal = $attendance->total_late;
                                                                                                $am = $attendance->undertimeAM;
                                                                                                $pm = $attendance->undertimePM;
                                                                                                $totalUndertimeInMinutes = $am + $pm;

                                                                                                // Combine late and undertime in minutes
                                                                                                $totalAdditionalMinutes = $totalLateMinutesDecimal + $totalUndertimeInMinutes;

                                                                                                // Convert time period 2 to total seconds
                                                                                                $timePeriod2Seconds = ($workedHours * 3600) + ($workedMinutes * 60) + $workedSeconds + ($totalAdditionalMinutes * 60);

                                                                                                // Calculate the difference in seconds
                                                                                                $differenceSeconds = $timePeriod1Seconds - $timePeriod2Seconds;

                                                                                                // Convert the difference back to hours, minutes, and seconds
                                                                                                $differenceHours = floor($differenceSeconds / 3600);
                                                                                                $differenceMinutes = floor(($differenceSeconds % 3600) / 60);
                                                                                                $differenceSeconds = $differenceSeconds % 60;

                                                                                                $formattedDifferenceHours = $differenceHours > 0 ? "{$differenceHours} hr/s" : '';
                                                                                                $formattedDifferenceMinutes = $differenceMinutes > 0 ? "{$differenceMinutes} min" : '';
                                                                                                $formattedDifferenceSeconds = $differenceSeconds > 0 ? "{$differenceSeconds} sec" : '';

                                                                                                // Combine formatted parts for difference
                                                                                                $formattedDifference = trim("{$formattedDifferenceHours} {$formattedDifferenceMinutes} {$formattedDifferenceSeconds}");
                                                                                                $formattedDifference = empty($formattedDifference) ? '0' : $formattedDifference;
                                                                                            @endphp

                                                                                            {{ $formattedDifference}}
                                                                                        </td>
                                                                                        <td class="text-black border border-gray-400 text-xs">
                                                                                            <!-- this is total hour required -->
                                                                                            <!-- {{ $attendance->hours_perDay }} hr/s -->
                                                                                            @php
                                                                                                // Assuming $attendance->hours_perDay is in decimal format
                                                                                                $totalHours = $attendance->hours_perDay;
                                                                                                $hours = floor($totalHours);
                                                                                                $minutes = floor(($totalHours - $hours) * 60);
                                                                                                $seconds = round((((($totalHours - $hours) * 60) - $minutes) * 60));

                                                                                                $formattedHours = $hours > 0 ? "{$hours} hr/s" : '0 hr/s';
                                                                                                $formattedMinutes = $minutes > 0 ? "{$minutes} min/s" : '0 min/s';
                                                                                                $formattedSeconds = $seconds > 0 ? "{$seconds} sec" : '0 sec';

                                                                                                $result = "{$formattedHours}, {$formattedMinutes}";
                                                                                            @endphp

                                                                                            {{ $result }}
                                                                                        </td>
                                                                                        <td class="text-red-500 border uppercase border-gray-400 text-xs font-bold w-32">
                                                                                            @php
                                                                                                $lateDurationAM = $attendance->late_duration;
                                                                                                $lateDurationPM = $attendance->late_durationPM;
                                                                                                $am = $attendance->undertimeAM ?? 0;
                                                                                                $pm = $attendance->undertimePM ?? 0;

                                                                                                $totalHoursAM = floor($attendance->hours_workedAM);
                                                                                                $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;
                                                                                                $totalHoursPM = floor($attendance->hours_workedPM);
                                                                                                $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;
                                                                                                $totalHours = $totalHoursAM + $totalHoursPM;
                                                                                                $totalMinutes = $totalMinutesAM + $totalMinutesPM;
                                                                                                $modify_status = $attendance->modify_status;
                                                                                                $firstCheckInStatus = $attendance->firstCheckInStatus;
                                                                                                $firstCheckOutStatus = $attendance->firstCheckOutStatus;
                                                                                                $secondCheckInStatus = $attendance->secondCheckInStatus;
                                                                                                $secondCheckOutStatus = $attendance->secondCheckOutStatus;

                                                                                                $remarkss = '';

                                                                                                if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "Absent"
                                                                                                ) {
                                                                                                    $remarkss = 'Absent';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'Leave';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    //$modify_status == "On Leave"
                                                                                                    $firstCheckInStatus == "On Leave" &&
                                                                                                    $firstCheckOutStatus == "On Leave" && 
                                                                                                    $secondCheckInStatus == "On Leave" &&
                                                                                                    $secondCheckOutStatus == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave Whole Day';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "Holiday"
                                                                                                ) {
                                                                                                    $remarkss = 'Holiday';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM > 0 ||
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0) &&
                                                                                                    $modify_status == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'Official Travel';
                                                                                                }
                                                                                                
                                                                                                
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM > 0 ||
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0) &&
                                                                                                    $modify_status == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 ||
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM > 0) &&
                                                                                                    $modify_status == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'Official Travel';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    ($totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 ||
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM > 0) &&
                                                                                                    $modify_status == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave';
                                                                                                }
                                                                                                else if (
                                                                                                    $firstCheckInStatus == "On Leave" &&
                                                                                                    $firstCheckOutStatus == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave AM - Present PM';
                                                                                                }
                                                                                                else if (
                                                                                                    $secondCheckInStatus == "On Leave" &&
                                                                                                    $secondCheckOutStatus == "On Leave"
                                                                                                ) {
                                                                                                    $remarkss = 'On Leave PM - Present AM';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    $am == 0 &&
                                                                                                    $pm == 0 &&
                                                                                                    $totalHoursAM > 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM > 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    //$modify_status == "Official Travel"
                                                                                                    $firstCheckInStatus == "Official Travel" &&
                                                                                                    $firstCheckOutStatus == "Official Travel" && 
                                                                                                    $secondCheckInStatus == "Official Travel" &&
                                                                                                    $secondCheckOutStatus == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'On Official Travel Whole Day';
                                                                                                }
                                                                                                else if (
                                                                                                    $firstCheckInStatus == "Official Travel" &&
                                                                                                    $firstCheckOutStatus == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'On Official Travel AM - Present PM';
                                                                                                }
                                                                                                else if (
                                                                                                    $secondCheckInStatus == "Official Travel" &&
                                                                                                    $secondCheckOutStatus == "Official Travel"
                                                                                                ) {
                                                                                                    $remarkss = 'On Official Travel PM - Present AM';
                                                                                                }
                                                                                                else if (
                                                                                                    $lateDurationAM == 0 &&
                                                                                                    $lateDurationPM == 0 &&
                                                                                                    ($am == 0 || $am > 0) &&
                                                                                                    ($pm == 0 || $pm > 0)  &&
                                                                                                    $totalHoursAM == 0 &&
                                                                                                    $totalMinutesAM == 0 &&
                                                                                                    $totalHoursPM == 0 &&
                                                                                                    $totalMinutesPM == 0 &&
                                                                                                    $modify_status == "On-campus"
                                                                                                ) {
                                                                                                    $remarkss = 'Invalid Attendance';
                                                                                                }
                                                                                                
                                                                                                else {
                                                                                                    if ($totalHoursPM == null && $totalMinutesPM == null && $totalHoursAM == 0 && $totalMinutesAM == 0 && $modify_status == "Weekend") {
                                                                                                        $remarkss = "Absent";
                                                                                                    } 
                                                                                                    else if ($totalHoursAM == null && $totalMinutesAM == null && $modify_status == "On-campus") {
                                                                                                        $remarkss = "Present";
                                                                                                    } 
                                                                                                    else if ($totalHoursAM == 0 && $totalMinutesAM == 0) {
                                                                                                        $remarkss = "Present Afternoon, Absent Morning";
                                                                                                    }
                                                                                                    else if ($totalHoursPM == 0 && $totalMinutesPM == 0) {
                                                                                                        $remarkss = "Present Morning, Absent Afternoon";
                                                                                                    }
                                                                                                    else {
                                                                                                        if ($lateDurationAM > 0 && $lateDurationPM > 0) {
                                                                                                            $remarkss = 'Present - Late AM & PM';
                                                                                                        } elseif ($lateDurationAM > 0) {
                                                                                                            $remarkss = 'Present - Late AM';
                                                                                                        } elseif ($lateDurationPM > 0) {
                                                                                                            $remarkss = 'Present - Late PM';
                                                                                                        }
                                                                                                        else {
                                                                                                            $remarkss = "Present";
                                                                                                        }
                                                                                                    }

                                                                                                    $undertimeRemark = '';
                                                                                                    if ($am > 0) {
                                                                                                        $undertimeRemark .= 'Undertime AM';
                                                                                                    }
                                                                                                    if ($pm > 0) {
                                                                                                        if (!empty($undertimeRemark)) {
                                                                                                            $undertimeRemark .= ' & PM';
                                                                                                        } else {
                                                                                                            $undertimeRemark .= 'Undertime PM';
                                                                                                        }
                                                                                                    }
                                                                                                    if (!empty($undertimeRemark)) {
                                                                                                        $remarkss .= ' - ' . $undertimeRemark;
                                                                                                    }
                                                                                                }
                                                                                            @endphp

                                                                                                @if ($remarkss === 'Present')
                                                                                                    <span class="text-black">{{ $remarkss }}</span>
                                                                                                @else
                                                                                                    <span class="text-red-500">{{ $remarkss }}</span>
                                                                                                @endif
                                                                                            </td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
        
                                                <!-- <tr>
                                                    <td class="border border-black text-red-500">{{ $totalFormatted }}   from ({{ $attendanceDaysCount }} days worked)</td>
                                                    <td class="border border-black text-red-500">{{ $hours }} hr/s, {{ $minutes }} min/s, {{ $seconds }} sec</td>
                                                    <td class="border border-black text-red-500">{{ $finalHourDeductionFormatted }}</td>
                                                    <td class="border border-black text-red-500">{{ $hoursM }} hr/s, {{ $minutesM }} min/s, {{ $secondsM }} sec</td>
                                                    <td class="border border-black text-red-500">{{ $undertimeFormatted }}</td>
                                                    <td class="border border-black text-red-500">{{ $absentFormatted }}</td>
                                                </tr> -->
                                            </table>
                                        @endforeach
                                    </div>                        
                                </div>
                            </div> 
                        </div>
                        <!-- HOLIDAYS  -->
                        <div x-show="tab === 'holidays'" class="w-full">
                            <div class="flex flex-col items-center mt-8 w-full mx-auto">
                                <p class="text-black text-xl text-center mb-2">LIST OF ADDED HOLIDAYS</p>
                                <p class="text-center mb-4">Holiday dates are excluded from calculations and are not included in the attendance or working hour computations.</p>
                                <div class="w-[40%] flex justify-center mb-4">
                                    @if($holidays->isNotEmpty())
                                        <table class="border border-collapse border-1 border-black w-full mb-4">
                                            <thead>
                                                <tr class="border border-collapse border-1 border-black">
                                                    <th class="border border-collapse border-1 border-black p-2">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($holidays as $holiday)
                                                    <tr class="border border-collapse border-1 border-black text-center">
                                                        <td class="border border-collapse border-1 border-black p-2">{{ \Carbon\Carbon::parse($holiday->check_in_date)->format('F j, Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="font-bold text-red-500 mb-4 text-center">No Holiday Dates Confirmed yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>                     
                    </div>
                </div>
                
            @endif
        @else
            @if($employees->isEmpty())
                <p class="text-black text-sm mt-11 mb-4 uppercase text-center">Add Employee first in the department</p>
            @else
                <p class="text-black text-sm mt-11 mb-4 uppercase text-center">No selected Employee</p>
            @endif
        @endif

    @endif
        

            
        
    
   
</div>
@push('scripts')
<script>
    Livewire.on('livewire:load', () => {
        flatpickr("#date_start", {
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('dateStart', dateStr);
            }
        });

        flatpickr("#date_end", {
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('dateEnd', dateStr);
            }
        });
    });
</script>
@endpush

<script>
    function downloadPDF() {
        // Initialize jsPDF
        const pdf = new jspdf.jsPDF('l', 'px', 'a4');

        // Set margins (adjust as needed)
        const margins = { top: 30, bottom: 10, left: 30, width: 800 };

        // Define position variables
        let posY = margins.top;

        // Add title
        pdf.setFontSize(16);


        pdf.text('Time In', margins.left, posY);
        posY += 10; // Increase posY for spacing

        // Define column widths
        const col1Width = 60;
        const col2Width = 120;
        const col3Width = 120;

        // Add table headers without background
        pdf.setFontSize(12);

        // Employee ID column header
        pdf.rect(margins.left, posY - 7, col1Width, 20); // Border around cell
        pdf.text('Employee ID', margins.left + 5, posY); // Add text with adjusted position

        // Date column header
        pdf.rect(margins.left + col1Width, posY - 7, col2Width, 20); // Border around cell
        pdf.text('Date', margins.left + col1Width + 5, posY); // Add text with adjusted position

        // Check-In Time column header
        pdf.rect(margins.left + col1Width + col2Width, posY - 7, col3Width, 20); // Border around cell
        pdf.text('Check-In Time', margins.left + col1Width + col2Width + 5, posY); // Add text with adjusted position

        posY += 18; // Increase posY for table header row

        // Iterate through table rows and add data with borders and padding
        @foreach ($attendanceTimeIn as $attendanceIn)
            pdf.setFontSize(11);

            // Employee ID data
            pdf.rect(margins.left, posY - 5, col1Width, 15); // Border around cell, adjusted height to 15px
            pdf.text('{{ $attendanceIn->employee_id }}', margins.left + 5, posY + 5); // Text alignment with padding

            // Date data
            pdf.rect(margins.left + col1Width, posY - 5, col2Width, 15); // Border around cell, adjusted height to 15px
            pdf.text('{{ date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)) }}', margins.left + col1Width + 5, posY + 5); // Text alignment with padding

            // Check-In Time data
            pdf.rect(margins.left + col1Width + col2Width, posY - 5, col3Width, 15); // Border around cell, adjusted height to 15px
            pdf.text('{{ date('g:i:s A', strtotime($attendanceIn->check_in_time)) }}', margins.left + col1Width + col2Width + 5, posY + 5); // Text alignment with padding

            posY += 15; // Increase posY for next row, adjusted to 15px height
        @endforeach
        // Save the PDF
        pdf.save('attendance.pdf');
    }
</script>





<script>
    function confirmUpdate(event) {
        event.preventDefault();

        let currentValue = event.target.value;
        let currentDeptID = event.target.DepartmentID;

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to update <strong>${this.originalValue}</strong> to <strong>${this.value}</strong>. Are you sure you want to proceed?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.value = currentValue;
                event.target.value = currentDeptID;
                event.target.closest('form').submit();
            } else {
                this.editing = false;
                this.cancelEdit();
            }
        });
    }
</script>
<script>
    document.addEventListener('livewire:load', function () {
        flatpickr("#rangeDate", {
            mode: "range",
            dateFormat: "Y-m-d",
        });
    });
</script>
<script>

    function cancelEdit() {
        this.value = this.originalValue;
        this.editing = false;
    }

</script>

<script> 

    document.addEventListener('DOMContentLoaded', function() {
        tippy('[data-tippy-content]', {
            allowHTML: true,
            theme: 'light', // Optional: Change the tooltip theme (light, dark, etc.)
            placement: 'right-end', // Optional: Adjust tooltip placement
        });
    });

</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script> -->
<script src="{{asset('assets/js/fancybox.umd.js')}}" defer></script>
<script>
      Fancybox.bind('[data-fancybox]', {
        contentClick: "iterateZoom",
        Images: {
            Panzoom: {
                maxScale: 3,
                },
            initialSize: "fit",
        },
        Toolbar: {
          display: {
            left: ["infobar"],
            middle: [
              "zoomIn",
              "zoomOut",
              "toggle1to1",
              "rotateCCW",
              "rotateCW",
              "flipX",
              "flipY",
            ],
            right: ["slideshow", "download", "thumbs", "close"],
          },
        },
      });    
</script>


<script>
    function confirmDeleteAll(event) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: 'Select Employee to Delete All Records',
            html: `
            
                <select id="department_id_select" class="cursor-pointer hover:border-red-500 swal2-select">
                    <option value="">Select Department</option>
                     @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department_id }} | {{ $department->department_abbreviation }} - {{ $department->department_name }}</option>
                        @endforeach
                </select>
            `,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete all!',
            preConfirm: () => {
                const departmentId = Swal.getPopup().querySelector('#department_id_select').value;
                if (!departmentId) {
                    Swal.showValidationMessage(`Please select a department`);
                }
                return { departmentId: departmentId };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const schoolId = result.value.schoolId;
                document.getElementById('department_id_to_delete').value = schoolId;
                document.getElementById('deleteAll').submit();
            }
        });
    }

    function ConfirmDeleteSelected(event, rowId, employeeId, employeeAbbreviation, employeeName) {
        event.preventDefault(); // Prevent form submission initially

        Swal.fire({
            title: `Are you sure you want to delete the employee ${employeeId} - ${employeeAbbreviation} ${employeeName} ?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteSelected');
                // Replace the placeholders with the actual rowId and employeeId
                const actionUrl = form.action.replace(':id', rowId);
                form.action = actionUrl;
                form.submit();
            }
        });

        return false; 
    }
</script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}" defer></script>
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
<script>
function handleImageError(image) {
    // Set the default image
    image.src = "{{ asset('assets/img/user.png') }}";
    
    // Display the error message
    document.getElementById('errorMessage').style.display = 'block';
}
</script>

<script>
         function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>

<script>
    document.addEventListener('export-success', () => {
        Alpine.store('loading', false);
    });
</script>

 <script>
        function updateStatus() {
            const amShift = document.getElementById('am_shift').checked;
            const pmShift = document.getElementById('pm_shift').checked;
            const amShiftHidden = document.getElementById('am_shift_hidden');
            const pmShiftHidden = document.getElementById('pm_shift_hidden');
            const statusSelect = document.getElementById('status');

            // Update hidden inputs based on checkbox states
            amShiftHidden.value = amShift ? '1' : '0';
            pmShiftHidden.value = pmShift ? '1' : '0';

            console.log('AM Shift:', amShift, 'PM Shift:', pmShift); // Debugging line
            console.log('AM Shift Hidden Value:', amShiftHidden.value, 'PM Shift Hidden Value:', pmShiftHidden.value); // Debugging line

            // Define default statuses for AM and PM shifts
            const defaultAmStatus = 'On Leave';
            const defaultPmStatus = 'Official Travel';

            // Update status based on the shifts
            if (amShift && pmShift) {
                statusSelect.value = defaultAmStatus; // Choose based on additional logic if needed
            } else if (amShift) {
                statusSelect.value = defaultAmStatus;
            } else if (pmShift) {
                statusSelect.value = defaultPmStatus;
            } else {
                statusSelect.value = defaultAmStatus; // Set default value if no shifts are selected
            } 

            // Ensure dropdown is always enabled
            statusSelect.disabled = false;
        }

        // Initialize status based on checkboxes on page load
        document.addEventListener('DOMContentLoaded', updateStatus);

        // Update status on checkbox change
        document.getElementById('am_shift').addEventListener('change', updateStatus);
        document.getElementById('pm_shift').addEventListener('change', updateStatus);
    </script>
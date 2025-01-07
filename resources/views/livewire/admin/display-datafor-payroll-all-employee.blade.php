<div class="mb-4">
        @php
            session(['selectedSchool' => $selectedSchool]);
            session(['selectedDepartment4' => $selectedDepartment4]);
            session(['selectedEmployee' => $selectedEmployee])
        @endphp
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
        <div class="font-bold text-md tracking-tight text-md text-black mt-2 uppercase">Admin / All Employee's Attendance Report by Department</div>
    </div>
    
        <div class="flex flex-column overflow-x-auto -mb-5">
            <div class="col-span-3 mb-2">
                <label for="school_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">School Year:</label>
                <select wire:model="selectedSchool" id="school_id" name="school_id" wire:change="updateEmployees"
                        class="cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('school_id') is-invalid @enderror md:w-auto"
                        required>
                    <option value="">Select School Year</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->abbreviation }}</option>
                    @endforeach
                </select>
                @if($schoolToShow)
                    <p class="text-black mt-2 text-sm mb-1 ">Selected School Year: <span class="text-red-500 ml-2">{{ $schoolToShow->abbreviation }}</span></p>
                    <!-- <p class="text-black  text-sm ml-4">Selected School: <span class="text-red-500 ml-2">{{ $schoolToShow->school_name }}</span></p> -->
                @endif
            </div>

        <div class="col-span-1 ml-5">
            @if(!empty($selectedSchool))
                <label for="department_id" class="block text-sm text-gray-700 font-bold md:mr-4 truncate uppercase">Department:</label>
                <select wire:model="selectedDepartment4" id="department_id" name="department_id"
                        wire:change="updateEmployeesByDepartment"
                        class="mr-5 cursor-pointer text-sm shadow appearance-none border pr-16 rounded py-2 px-2 text-black leading-tight focus:outline-none focus:shadow-outline @error('department_id') is-invalid @enderror md:w-auto"
                        required>
                    <option selected>All Departments</option> <!-- Added option for selecting all departments -->
                    @if($departments->isEmpty())
                        <option value="0">No Departments</option>
                    @else
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department_abbreviation }}</option>
                        @endforeach
                    @endif
                </select>

                @if($departmentToShow)
                    <!-- <p class="text-black mt-2 text-sm mb-1">Selected Department ID: <span class="text-red-500 ml-2">{{ $departmentToShow->department_id }}</span></p> -->
                    <p class="text-black text-sm mt-2">Selected Department: <span class="text-red-500 ml-2">{{ $departmentToShow->department_abbreviation }}</span></p>
                @endif

            @endif
        </div>
        <div class="col-span-1 ml-5">
            @if($departmentToShow)
                <div x-data="{ open: false }" @keydown.window.escape="open = false" x-cloak>
                    <!-- Modal Trigger Button -->
                    <button @click="open = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2 mt-5"><i class="fa-solid fa-calendar-days"></i> Click to view Working Hour</button>

                    <!-- Modal Background -->
                    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 z-50" @click="open = false"></div>

                    <!-- Modal Content -->
                    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-white p-8 rounded-lg shadow-lg max-w-7xl w-full ">
                            <div class="mt-2 flex justify-between">
                                <h2 class="text-lg font-semibold mb-4">Work Details</h2> <a href="{{ route('admin.workinghour.index') }}"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2 ">Edit Now</button></a>
                                <button @click="open = false" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded ml-2 "><i class="fa-solid fa-times fa-xs"></i> Close</button>
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
            @endif
        </div>

    </div>
    <hr class="border-gray-200 my-4">
        @if(!$schoolToShow)
            <p class="text-black text-sm mt-11 mb-4 uppercase text-center">No selected school</p>
        @endif

    @if($isAllDepartmentsSelected)
        <!-- <div class="flex justify-end mt-[60px] mb-2">                               
            <div class="flex flex-col">
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
                            <a href="">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"><i class="fa-solid fa-arrows-rotate"></i> Refresh</button>
                            </a>
                        </div>
                    </div>        
                </div>
            </div>
        </div> -->


        <div 
            x-data="{ 
                loading: false, 
                startDate: '', 
                endDate: '', 
                open: @json(session()->has('success') ? true : false) 
            }"
            x-init="
                $watch('startDate', value => checkDates());
                $watch('endDate', value => checkDates());
                function checkDates() {
                    loading = startDate && endDate;
                }
                if (open) {
                    loading = false; // Automatically close the modal after 3 seconds
                }
            "
            @export-success.window="loading = false; open = true"
            class="flex justify-end mt-[60px] mb-2"
        >
            <div class="flex flex-col">
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
                                @change="startDate = $event.target.value; checkDates()"
                            >
                            <label for="endDate" class="text-gray-600">End Date:</label>
                            <input 
                                id="endDate" 
                                type="date" 
                                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                wire:model="endDate"
                                wire:change="updateAttendanceByDateRange"
                                @change="endDate = $event.target.value; checkDates()"
                            >
                            <a href="">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                                    <i class="fa-solid fa-arrows-rotate"></i> Refresh
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div x-show="loading" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-white"></div>
            </div>
            
        </div>

                            
        <!-- Display content for when "All Departments" is selected -->
        <p class="text-sm mt-2 text-red-500">Select specific date to view attendance report.</p>
        <div class="w-full">
            <div class="flex justify-center ">
                <div class="flex justify-end w-full">
                    <div class="flex flex-col w-full">

                        
                        @if($isAllDepartmentsSelected)
                                @php
                                    // Group data by employee_id
                                    $employees = [];

                                    foreach ($attendanceData as $attendance) {
                                

                                        
                                        $employeeId = $attendance->employee_id;
                                        $check = $attendance->check_in_time;
                                        if (!isset($employees[$employeeId])) {
                                            $employees[$employeeId] = [
                                                'hours_workedAM' => 0,
                                                'hours_workedPM' => 0,
                                                'totalHours' => 0,
                                                'total_hours_worked' => 0,
                                                'hours_late_overall' => 0,
                                                'id' => $attendance->employee_id,
                                                'hours_undertime_overall' => 0,
                                                'employee_idd' => $attendance->employee_idd,
                                                'employee_id' => $attendance->employee_idd,
                                                'employee_lastname' => $attendance->employee_lastname,
                                                'employee_firstname' => $attendance->employee_firstname,
                                                'employee_middlename' => $attendance->employee_middlename,
                                                'uniqueDays' => []
                                            ];
                                        }
                                        
                                        // Accumulate totals for each employee
                                        $employees[$employeeId]['totalHours'] += $attendance->hours_perDay;
                                        $total = $attendance->hours_workedAM + $attendance->hours_workedPM;
                                        $employees[$employeeId]['total_hours_worked'] += $total;
                                        $employees[$employeeId]['hours_late_overall'] += $attendance->hours_late_overall; // Replace with actual late hours field
                                        $employees[$employeeId]['hours_undertime_overall'] += $attendance->hours_undertime_overall; // Replace with actual undertime field
                                    
                                        $date = \Illuminate\Support\Carbon::parse($attendance->check_in_time)->toDateString();
                                        $employees[$employeeId]['uniqueDays'][$date] = true;
                                    }
                                @endphp                           
                        @else
                            <div class="flex justify-center">
                                <h1 class="uppercase text-[30px]">Department: {{ $departmentToShow->department_abbreviation }}</h1>
                            </div>
                        @endif
                        
                        <div x-data="{ loading: false, open: {{ session()->has('success') ? 'true' : 'false' }} }"
                            x-init="() => {
                                if (open) {
                                    loading = false; // Automatically close the modal after 3 seconds
                                }
                            }"
                            @export-success.window="loading = false; open = true">



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
                                    <div class="">
                                        <div class="">
                                            <button 
                                                x-on:click="loading = true" 
                                                wire:click="generateExcelPayroll" 
                                                wire:loading.attr="disabled" 
                                                wire:loading.class="cursor-wait"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                <i class="fa-solid fa-file"></i> Export to Excel
                                            </button>
                                            <button 
                                                x-on:click="loading = true" 
                                                wire:click="generatePDF" 
                                                wire:loading.attr="disabled" 
                                                wire:loading.class="cursor-wait"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                <i class="fa-solid fa-file"></i> Generate PDF 
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p>Selected Date Range:</p>
                                <div class="flex justify-between -mt-4">
                                    <p class="py-4">No selected Date</p>
                                    <div class="">
                                        <button 
                                            x-on:click="loading = true" 
                                            wire:click="generateExcelPayroll" 
                                            wire:loading.attr="disabled" 
                                            wire:loading.class="cursor-wait"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                            <i class="fa-solid fa-file"></i> Export to Excel
                                        </button>
                                        <button 
                                            x-on:click="loading = true" 
                                            wire:click="generatePDF" 
                                            wire:loading.attr="disabled" 
                                            wire:loading.class="cursor-wait"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                            <i class="fa-solid fa-file"></i> Generate PDF 
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <table class="border border-black " cellpadding="4">
                            <thead>
                                <tr class="border border-black text-xs bg-gray-200">
                                    <!-- <th class="border border-black text-center">Employee ID</th> -->
                                    <th class="border border-black text-center">Employee Full Name</th>
                                    <th class="border border-black text-center">Duty Hours To Be Rendered</th>
                                    <th class="border border-black text-center">Total Time Rendered</th>
                                    <th class="border border-black text-center">Final Time deduction</th>
                                    <th class="border border-black text-center">Total Late</th>
                                    <th class="border border-black text-center">Total Undertime</th>
                                    <th class="border border-black text-center">Absent Hour</th>
                                    <th class="border border-black text-center">Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
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
                                            $overallhours = floor($overallminutes / 60);
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

                                    <tr class="border border-black text-[11px] hover:bg-gray-200">
                                        <!-- <td class="text-black border border-black text-center">
                                            
                                        </td> -->
                                        <td class="text-black border border-black">
                                            {{ $employeeData['employee_lastname'] }},
                                            {{ $employeeData['employee_firstname'] }},
                                            {{ $employeeData['employee_middlename'] }}
                                        </td>
                                        <td class="text-black border border-black">{{ $totalFormatted }}  from ({{ $attendanceDaysCount }} days worked)</td>
                                            <td class="text-black border border-black">{{$formattedTimeWorked}}</td>
                                            <td class="text-black border border-black">{{ $finalHourDeductionFormatted }}</td>
                                            <td class="text-black border border-black">{{ $lateFormatted }}</td>
                                            <td class="text-black border border-black">{{ $undertimeFormatted }}</td>
                                            <td class="text-black border border-black text-center">{{ $absentFormatted }}</td>
                                            <td class="text-black border border-black">
                                                <div class="flex justify-center items-center space-x-2 p-1 z-50">
                                                    <div x-data="{ open: false }">
                                                        <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">
                                                            <i class="fa-solid fa-eye fa-xs" style="color: #ffffff;"></i> View Records
                                                        </a>
                                                        <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                            <div @click.away="open = false" class=" w-[85%] max-h-[90vh] bg-white p-6 rounded-md shadow-lg  mx-auto overflow-y-auto">
                                                                <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                    <p class="text-xl font-bold">Detailed Calculation of Work Hours</p>
                                                                    <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                </div>
                                                                <div class="w-full">
                                                                    <!-- <h3 class="text-center text-lg font-semibold uppercase mb-2 mt-6">Calculation of Work Hours</h3> -->
                                                                        <p> Employee: <text class="text-red-500 font-bold">{{ $employeeData['employee_lastname'] }},
                                                                                                            {{ $employeeData['employee_firstname'] }},
                                                                                                            {{ $employeeData['employee_middlename']}}</text></p>
                                                                    <p> Employee ID: {{ $employeeData['employee_idd'] }}</p>
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
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> 
    @else
        @if($departmentToShow)
            <div 
                    x-data="{ 
                        loading: false, 
                        startDate: '', 
                        endDate: '', 
                        open: @json(session()->has('success') ? true : false) 
                    }"
                    x-init="
                        $watch('startDate', value => checkDates());
                        $watch('endDate', value => checkDates());
                        function checkDates() {
                            loading = startDate && endDate;
                        }
                        if (open) {
                            loading = false; // Automatically close the modal after 3 seconds
                        }
                    "
                    @export-success.window="loading = false; open = true"
                    class="flex justify-end mt-[60px] mb-2"
                >
                    <div class="flex flex-col">
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
                                        @change="startDate = $event.target.value; checkDates()"
                                    >
                                    <label for="endDate" class="text-gray-600">End Date:</label>
                                    <input 
                                        id="endDate" 
                                        type="date" 
                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                        wire:model="endDate"
                                        wire:change="updateAttendanceByDateRange"
                                        @change="endDate = $event.target.value; checkDates()"
                                    >
                                    <a href="">
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                                            <i class="fa-solid fa-arrows-rotate"></i> Refresh
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div x-show="loading" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-white"></div>
                    </div>
            
                </div>                                      
            <div x-data="{ tab: 'modify_date' }" class="p-4">
                <div class="overflow-x-auto">
                    
                    

                    <div x-show="tab === 'computed-hours'" class="w-full">
                        <!-- Table for Computed Working Hours -->

                        <p>Required Total Hour Per Week based on Working hour: <text class="text-red-500">{{ $overallTotalHoursSum }} hr/s.</text></p>

                        <div class="w-full">
                            <h3 class="text-center text-lg font-semibold uppercase mb-2 mt-2">Calculation of Work Hours</h3>
                            
                                @foreach ($employees as $employee)                                   
                                        <h4 class="text-left text-md font-semibold mb-2">{{ $employee->employee_lastname }}, {{ $employee->employee_firstname }}, {{ $employee->employee_middlename }}</h4>
                                    
                                        <table class="table-auto min-w-full text-center text-xs mb-4 divide-y divide-gray-200">
                                            <thead class="bg-gray-200 text-black">
                                                <tr>
                                                    <th class="border border-gray-400 px-2 py-1">Date</th>
                                                    <th class="border border-gray-400 px-2 py-1">AM Late</th>
                                                    <th class="border border-gray-400 px-2 py-1">PM Late</th>
                                                    <th class="border border-gray-400 px-2 py-1">AM UnderTime</th>
                                                    <th class="border border-gray-400 px-2 py-1">PM UnderTime</th>
                                                    <th class="border border-gray-400 px-2 py-1">Total AM Hours</th>
                                                    <th class="border border-gray-400 px-2 py-1">Total PM Hours</th>
                                                    <th class="border border-gray-400 px-2 py-1">Total Late</th>
                                                    <th class="border border-gray-400 px-2 py-1">Total Undertime</th>
                                                    <th class="border border-gray-400 px-2 py-1">Total Hours Rendered</th>
                                                    <th class="border border-gray-400 px-2 py-1">Required Hours</th>
                                                    <th class="border border-gray-400 px-2 py-1">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    
                                                    $attendanceDataCollection = is_array($attendanceData) ? new \Illuminate\Support\Collection($attendanceData) : $attendanceData;
                                                    $attendanceForEmployee = $attendanceDataCollection->where('employee_id', $employee->id);
                                                    
                                                    
                                                @endphp
                                                
                                                @foreach ($attendanceData as $attendance)
                                                <tr class="hover:border hover:bg-gray-200">
                                                    <td class="text-black border border-gray-400 px-2 py-1">{{ date('M d, Y (D)', strtotime($attendance->worked_date)) }}</td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        @php
                                                            // Calculate late duration in minutes
                                                            $lateDurationInMinutes = $attendance->late_duration;

                                                            // Calculate late hours, minutes, and seconds
                                                            $lateHours = intdiv($lateDurationInMinutes, 60);
                                                            $lateMinutes = $lateDurationInMinutes % 60;
                                                            $lateSeconds = ($lateDurationInMinutes - floor($lateDurationInMinutes)) * 60;

                                                            // Round seconds to avoid precision issues
                                                            $lateSeconds = round($lateSeconds);

                                                            // Format the late duration string
                                                            $lateDurationFormatted = ($lateHours > 0 ? "{$lateHours} hr " : '') 
                                                                                    . ($lateMinutes > 0 ? "{$lateMinutes} min " : '')
                                                                                    . ($lateSeconds > 0 ? "{$lateSeconds} sec" : '');

                                                            // If the formatted string is empty, ensure we show "0"
                                                            $lateDurationFormatted = $lateDurationFormatted ?: '0 sec';
                                                        @endphp

                                                        {{ $lateDurationFormatted }}

                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        @php
                                                            // Calculate late duration in minutes
                                                            $lateDurationInMinutes = $attendance->late_durationPM;

                                                            // Calculate late hours, minutes, and seconds
                                                            $lateHours = intdiv($lateDurationInMinutes, 60);
                                                            $lateMinutes = $lateDurationInMinutes % 60;
                                                            $lateSeconds = ($lateDurationInMinutes - floor($lateDurationInMinutes)) * 60;

                                                            // Round seconds to avoid precision issues
                                                            $lateSeconds = round($lateSeconds);

                                                            // Format the late duration string
                                                            $lateDurationFormatted = ($lateHours > 0 ? "{$lateHours} hr " : '') 
                                                                                    . ($lateMinutes > 0 ? "{$lateMinutes} min " : '')
                                                                                    . ($lateSeconds > 0 ? "{$lateSeconds} sec" : '');

                                                            // If the formatted string is empty, ensure we show "0"
                                                            $lateDurationFormatted = $lateDurationFormatted ?: '0 sec';
                                                        @endphp

                                                        {{ $lateDurationFormatted }}
                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                    @php
                                                            // Assume $attendance->undertimeAM is in minutes
                                                            $undertimeInMinutes = $attendance->undertimeAM;

                                                            // Convert minutes to total seconds
                                                            $undertimeInSeconds = $undertimeInMinutes * 60;

                                                            // Convert total seconds to hours, minutes, and seconds
                                                            $undertimeHours = intdiv($undertimeInSeconds, 3600); // Total hours
                                                            $remainingSeconds = $undertimeInSeconds % 3600; // Remaining seconds after hours
                                                            $undertimeMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                            $undertimeSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                            // Format the duration string
                                                            $undertimeFormatted = 
                                                                ($undertimeHours > 0 ? "{$undertimeHours} hr " : '') .
                                                                ($undertimeMinutes > 0 ? "{$undertimeMinutes} min " : '0 min ') .
                                                                ($undertimeSeconds > 0 ? "{$undertimeSeconds} sec" : '0 sec');
                                                        @endphp

                                                        {{ $undertimeFormatted }}

                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        @php
                                                            // Assume $attendance->undertimePM is in minutes
                                                            $undertimeInMinutes = $attendance->undertimePM;

                                                            // Convert minutes to total seconds
                                                            $undertimeInSeconds = $undertimeInMinutes * 60;

                                                            // Convert total seconds to hours, minutes, and seconds
                                                            $undertimeHours = intdiv($undertimeInSeconds, 3600); // Total hours
                                                            $remainingSeconds = $undertimeInSeconds % 3600; // Remaining seconds after hours
                                                            $undertimeMinutes = intdiv($remainingSeconds, 60); // Total minutes
                                                            $undertimeSeconds = $remainingSeconds % 60; // Remaining seconds after minutes

                                                            // Format the duration string
                                                            $undertimeFormatted = 
                                                                ($undertimeHours > 0 ? "{$undertimeHours} hr " : '') .
                                                                ($undertimeMinutes > 0 ? "{$undertimeMinutes} min " : '0 min ') .
                                                                ($undertimeSeconds > 0 ? "{$undertimeSeconds} sec" : '0 sec');
                                                        @endphp

                                                        {{ $undertimeFormatted }}

                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        <!-- {{ floor($attendance->hours_workedAM) }} hrs. {{ round($attendance->hours_workedAM - floor($attendance->hours_workedAM), 1) * 60 }} min. -->
                                                    @php
                                                        // Total hours worked in AM shift
                                                        $totalHoursAM = floor($attendance->hours_workedAM);
                                                        $totalMinutesAM = ($attendance->hours_workedAM - $totalHoursAM) * 60;

                                                        // Convert minutes to seconds
                                                        $totalSecondsAM = ($totalMinutesAM - floor($totalMinutesAM)) * 60;
                                                        $totalMinutesAM = floor($totalMinutesAM);

                                                        // Get late duration in minutes for AM shift
                                                    
                                                        // Convert total minutes to hours and minutes for AM shift
                                                        $finalHoursAM = $totalHoursAM + floor($totalMinutesAM / 60);
                                                        $finalMinutesAM = $totalMinutesAM % 60;

                                                        // Ensure final seconds is a whole number
                                                        $finalSecondsAM = round($totalSecondsAM);

                                                    @endphp

                                                    {{ $finalHoursAM }} hrs. {{ $finalMinutesAM }} min. {{ $finalSecondsAM }} sec.
                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        <!-- {{ floor($attendance->hours_workedPM) }} hrs. {{ round($attendance->hours_workedPM - floor($attendance->hours_workedPM), 1) * 60 }} min. -->
                                                        @php
                                                        // Total hours worked in AM PM shift
                                                        $totalHoursPM = floor($attendance->hours_workedPM);
                                                        $totalMinutesPM = ($attendance->hours_workedPM - $totalHoursPM) * 60;

                                                        // Convert minutes to seconds
                                                        $totalSecondsPM = ($totalMinutesPM - floor($totalMinutesPM)) * 60;
                                                        $totalMinutesPM = floor($totalMinutesPM);

                                                    

                                                        // Convert total minutes to hours and minutes for AM shift
                                                        $finalHoursPM = $totalHoursPM + floor($totalMinutesPM / 60);
                                                        $finalMinutesPM = $totalMinutesPM % 60;

                                                        // Ensure final seconds is a whole number
                                                        $finalSecondsPM = round($totalSecondsPM);

                                                    @endphp

                                                    {{ $finalHoursPM }} hrs. {{ $finalMinutesPM }} min. {{ $finalSecondsPM }} sec.
                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        @php
                                                            // Total late time in minutes as a decimal
                                                            $totalLateMinutesDecimal = $attendance->total_late;

                                                            // Convert decimal minutes to total hours, minutes, and seconds
                                                            $totalLateHours = intdiv($totalLateMinutesDecimal, 60); // Total hours
                                                            $remainingMinutes = floor($totalLateMinutesDecimal % 60); // Remaining minutes
                                                            $totalLateSeconds = round(($totalLateMinutesDecimal - floor($totalLateMinutesDecimal)) * 60); // Total seconds

                                                            // Format the duration string
                                                            $totalLateDurationFormatted = 
                                                                ($totalLateHours > 0 ? "{$totalLateHours} hrs " : '') .
                                                                ($remainingMinutes > 0 ? "{$remainingMinutes} mins " : '0 mins ') .
                                                                ($totalLateSeconds > 0 ? "{$totalLateSeconds} secs" : '0 secs');
                                                        @endphp

                                                        {{ $totalLateDurationFormatted }}
                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        @php
                                                            $am = $attendance->undertimeAM;
                                                            $pm = $attendance->undertimePM;
                                                            $totalUndertimeInMinutes = $am + $pm;

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
                                                        @endphp

                                                        {{ $totalLateDurationFormatted }}

                                                    </td>
                                                    <td class="text-black border border-gray-400 px-2 py-1">
                                                        @php
                                                            // Total hours worked in decimal format
                                                            $totalHoursWorked = $attendance->total_hours_worked;
                                                            
                                                            // Calculate hours and minutes
                                                            $totalHours = floor($totalHoursWorked);
                                                            $totalMinutes = ($totalHoursWorked - $totalHours) * 60;
                                                            
                                                            // Convert total minutes to total seconds
                                                            $totalSeconds = $totalMinutes * 60;
                                                            
                                                            // Calculate final hours, minutes, and seconds
                                                            $finalHours = $totalHours + floor($totalSeconds / 3600);
                                                            $remainingSeconds = $totalSeconds % 3600;
                                                            $finalMinutes = floor($remainingSeconds / 60);
                                                            $finalSeconds = $remainingSeconds % 60;
                                                        @endphp

                                                        {{ $finalHours }} hrs. {{ $finalMinutes }} min. {{ $finalSeconds }} sec.
                                                                
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
                                                    <td class="text-black border uppercase border-gray-400 text-xs font-semibold">
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
                                                            $modify_status == "On Leave"
                                                        ) {
                                                            $remarkss = 'Leave';
                                                        }
                                                        else {
                                                            if ($totalHoursAM == 0 && $totalMinutesAM == 0) {
                                                                $remarkss = "Present but Absent Morning";
                                                            }
                                                            else if ($totalHoursPM == 0 && $totalMinutesPM == 0) {
                                                                $remarkss = "Present but Absent Afternoon";
                                                            } else {
                                                                if ($lateDurationAM > 0 && $lateDurationPM > 0) {
                                                                    $remarkss = 'Present - Late AM & PM';
                                                                } elseif ($lateDurationAM > 0) {
                                                                    $remarkss = 'Present - Late AM';
                                                                } elseif ($lateDurationPM > 0) {
                                                                    $remarkss = 'Present - Late PM';
                                                                } else {
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

                                                        {{ $remarkss }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                               
                                            </tbody>
                                        </table>
                                         @if ($attendanceForEmployee->isEmpty())
                                            <p class="text-center mt-8">No Working Hour found.</p>
                                        @endif
                            @endforeach
                        </div>
                        <!-- end -->
                    </div>
                    <div x-show="tab === 'modify_date'" class="w-full">
                        <div class="flex justify-center ">
                            <div class="flex justify-end w-full">
                                <div class="flex flex-col w-full">

                                    @php
                                        // Group data by employee_id
                                        $employees = [];

                                        foreach ($attendanceData as $attendance) {

                                            
                                            $employeeId = $attendance->employee_id;
                                            $check = $attendance->check_in_time;
                                            if (!isset($employees[$employeeId])) {
                                                $employees[$employeeId] = [
                                                    'hours_workedAM' => 0,
                                                    'hours_workedPM' => 0,
                                                    'totalHours' => 0,
                                                    'total_hours_worked' => 0,
                                                    'hours_late_overall' => 0,
                                                    'id' => $attendance->employee_id,
                                                    'hours_undertime_overall' => 0,
                                                    'employee_idd' => $attendance->employee_idd,
                                                    'employee_id' => $attendance->employee_idd,
                                                    'employee_lastname' => $attendance->employee_lastname,
                                                    'employee_firstname' => $attendance->employee_firstname,
                                                    'employee_middlename' => $attendance->employee_middlename,
                                                    'uniqueDays' => []
                                                ];
                                            }
                                            
                                            // Accumulate totals for each employee
                                            $employees[$employeeId]['totalHours'] += $attendance->hours_perDay;
                                            $total = $attendance->hours_workedAM + $attendance->hours_workedPM;
                                            $employees[$employeeId]['total_hours_worked'] += $total;
                                            $employees[$employeeId]['hours_late_overall'] += $attendance->hours_late_overall; // Replace with actual late hours field
                                            $employees[$employeeId]['hours_undertime_overall'] += $attendance->hours_undertime_overall; // Replace with actual undertime field
                                        
                                            $date = \Illuminate\Support\Carbon::parse($attendance->check_in_time)->toDateString();
                                            $employees[$employeeId]['uniqueDays'][$date] = true;
                                        }
                                    @endphp
                                    
                                    <div class="flex justify-center">
                                        <h1 class="uppercase text-[30px]">Department: {{ $departmentToShow->department_abbreviation }}</h1>
                                    </div>
                                    <div x-data="{ loading: false, open: {{ session()->has('success') ? 'true' : 'false' }} }"
                                        x-init="() => {
                                            if (open) {
                                                loading = false;
                                                setTimeout(() => open = false, 3000); // Automatically close the modal after 3 seconds
                                            }
                                        }"
                                        @export-success.window="loading = false; open = true">

                                        <!-- Modal Background -->
                                        <!-- <div x-cloak x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                                            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
                                                <h2 class="text-xl font-semibold mb-4">Download Info</h2>
                                                <p>{{ session()->get('success') }}</p>
                                                <div class="flex justify-end mt-4">
                                                    <button @click="open = false" class="px-4 py-2 bg-blue-500 text-white rounded">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div> -->


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
                                                <div class="">
                                                    <div class="">
                                                        <button 
                                                            x-on:click="loading = true" 
                                                            wire:click="generateExcelPayroll" 
                                                            wire:loading.attr="disabled" 
                                                            wire:loading.class="cursor-wait"
                                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                            <i class="fa-solid fa-file"></i> Export to Excel
                                                        </button>
                                                        <button 
                                                            x-on:click="loading = true" 
                                                            wire:click="generatePDF" 
                                                            wire:loading.attr="disabled" 
                                                            wire:loading.class="cursor-wait"
                                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                            <i class="fa-solid fa-file"></i> Generate PDF 
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <p>Selected Date Range:</p>
                                            <div class="flex justify-between -mt-4">
                                                <p class="py-4">No selected Date</p>
                                                <div class="">
                                                    <button 
                                                        x-on:click="loading = true" 
                                                        wire:click="generateExcelPayroll" 
                                                        wire:loading.attr="disabled" 
                                                        wire:loading.class="cursor-wait"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                        <i class="fa-solid fa-file"></i> Export to Excel
                                                    </button>
                                                    <button 
                                                        x-on:click="loading = true" 
                                                        wire:click="generatePDF" 
                                                        wire:loading.attr="disabled" 
                                                        wire:loading.class="cursor-wait"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">
                                                        <i class="fa-solid fa-file"></i> Generate PDF 
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <table class="border border-black " cellpadding="4">
                                        <thead>
                                            <tr class="border border-black text-xs bg-gray-200">
                                                <!-- <th class="border border-black text-center">Employee ID</th> -->
                                                <th class="border border-black text-center">Employee Full Name</th>
                                                <th class="border border-black text-center">Duty Hours To Be Rendered</th>
                                                <th class="border border-black text-center">Total Time Rendered</th>
                                                <th class="border border-black text-center">Final Time deduction</th>
                                                <th class="border border-black text-center">Total Late</th>
                                                <th class="border border-black text-center">Total Undertime</th>
                                                <th class="border border-black text-center">Absent Hour</th>
                                                <th class="border border-black text-center">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                                        $overallhours = floor($overallminutes / 60);
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

                                                <tr class="border border-black text-[11px] hover:bg-gray-200">
                                                    <!-- <td class="text-black border border-black text-center">
                                                      
                                                    </td> -->
                                                    <td class="text-black border border-black">
                                                        {{ $employeeData['employee_lastname'] }},
                                                        {{ $employeeData['employee_firstname'] }},
                                                        {{ $employeeData['employee_middlename'] }}
                                                    </td>
                                                    <td class="text-black border border-black">{{ $totalFormatted }}  from ({{ $attendanceDaysCount }} days worked)</td>
                                                        <td class="text-black border border-black">{{$formattedTimeWorked}}</td>
                                                        <td class="text-black border border-black">{{ $finalHourDeductionFormatted }}</td>
                                                        <td class="text-black border border-black">{{ $lateFormatted }}</td>
                                                        <td class="text-black border border-black">{{ $undertimeFormatted }}</td>
                                                        <td class="text-black border border-black text-center">{{ $absentFormatted }}</td>
                                                        <td class="text-black border border-black">
                                                            <div class="flex justify-center items-center space-x-2 p-1 z-50">
                                                                <div x-data="{ open: false }">
                                                                    <a @click="open = true" class="cursor-pointer bg-blue-500 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">
                                                                        <i class="fa-solid fa-eye fa-xs" style="color: #ffffff;"></i> View Records
                                                                    </a>
                                                                    <div x-cloak x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                                                        <div @click.away="open = false" class=" w-[85%] max-h-[90vh] bg-white p-6 rounded-md shadow-lg  mx-auto overflow-y-auto">
                                                                            <div class="flex justify-between items-start pb-3"> <!-- Changed items-center to items-start -->
                                                                                <p class="text-xl font-bold">Detailed Calculation of Work Hours</p>
                                                                                <a @click="open = false" class="cursor-pointer text-black text-sm px-3 py-2 rounded hover:text-red-500">X</a>
                                                                            </div>
                                                                            <div class="w-full">
                                                                                <!-- <h3 class="text-center text-lg font-semibold uppercase mb-2 mt-6">Calculation of Work Hours</h3> -->
                                                                                 <p> Employee: <text class="text-red-500 font-bold">{{ $employeeData['employee_lastname'] }},
                                                                                                                        {{ $employeeData['employee_firstname'] }},
                                                                                                                        {{ $employeeData['employee_middlename']}}</text></p>
                                                                                <p> Employee ID: {{ $employeeData['employee_idd'] }}</p>
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
                                                                                            <th class="border border-gray-400 px-2 py-1 uppercase">Emp ID</th>
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
                                                                                            
                                                                                            $id = $employeeData['employee_idd'];
                                                                                           
                                                                                            
                                                                                            // Group check-in times for the specific employee
                                                                                            foreach ($attendanceTimeIn as $attendanceIn) {
                                                                                                $employeeId = $attendanceIn->employee->employee_id;

                                                                                                // Only process if the employee ID matches the specified ID
                                                                                                if ($employeeId == $id) {
                                                                                                    $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
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
                                                                                            }

                                                                                            // Group check-out times for the specific employee
                                                                                            foreach ($attendanceTimeOut as $attendanceOut) {
                                                                                                $employeeId = $attendanceOut->employee->employee_id;

                                                                                                // Only process if the employee ID matches the specified ID
                                                                                                if ($employeeId == $id) {
                                                                                                    $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
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
                                                                                            }
                                                                                            
                                                                                        @endphp
                                                                                        
                                                                                        @foreach ($attendanceData as $attendance )
                                                                                            @if($attendance->employee_id == $employeeData['id'])
                                                                                                @php
                                                                                                    $workedDate = date('Y-m-d', strtotime($attendance->worked_date));
                                                                                                @endphp
                                                                                            <tr class="hover:border hover:bg-gray-200">
                                                                                                <td class="text-black border border-gray-400 px-2 py-1 font-bold">{{ $id }}</td>
                                                                                                <td class="text-black border border-gray-400 px-2 py-1 font-bold">{{ date('M d, Y (D)', strtotime($attendance->worked_date)) }}</td>
                                                                                                <td class="text-black border border-gray-400 px-2 py-1 w-28">
                                                                                                @foreach ($groupedAttendance as $employeeId => $dates)
                                                                                                    @foreach ($dates as $date => $attendance1)
                                                                                                        @if ($date === $workedDate)
                                                                                                            @if (!empty($attendance1['check_ins']))
                                                                                                                <hr class="" style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                <text class="text-red-500">1ST TIME IN:  </text>
                                                                                                                    @php
                                                                                                                        $isPmDisplayed = false;
                                                                                                                    @endphp
                                                                                                            
                                                                                                                    @foreach ($attendance1['check_ins'] as $index => $checkIn)
                                                                                                                        
                                                                                                                        <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                        
                                                                                                                        {{ $checkIn }}
                                                                                                                        
                                                                                                                        @if (!$isPmDisplayed)
                                                                                                                            @php
                                                                                                                                $isPmDisplayed = true;
                                                                                                                            @endphp
                                                                                                                                
                                                                                                                                <br><br>
                                                                                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                                <text class="text-blue-500">2ND TIME IN:  </text>

                                                                                                                        @endif
                                                                                                                        
                                                                                                                    @endforeach

                                                                                                                    @if (!$isPmDisplayed)
                                                                                                                        <p>No AM check-in</p>
                                                                                                                    @endif
                                                                                                            @else
                                                                                                                <p>No Check-Ins</p>
                                                                                                            @endif
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                @endforeach
                                                                                                </td>
                                                                                                <td class="text-black border border-gray-400 px-2 py-1 w-32">
                                                                                                    @foreach ($groupedAttendance as $employeeId => $dates)
                                                                                                        @foreach ($dates as $date => $attendance1)
                                                                                                            @if ($date === $workedDate)
                                                                                                                @if (!empty($attendance1['check_outs']))
                                                                                                                    <hr style="border: none; border-top: 1px solid #000;">
                                                                                                                    <text class="text-red-500"> 1ST TIME OUT:  </text>
                                                                                                                        @php
                                                                                                                            $isPmDisplayed = false;
                                                                                                                        @endphp

                                                                                                                        @foreach ($attendance1['check_outs'] as $index => $checkOut)
                                                                                                                            <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                            {{ $checkOut }}

                                                                                                                            @if (!$isPmDisplayed)
                                                                                                                                @php
                                                                                                                                    $isPmDisplayed = true;
                                                                                                                                @endphp
                                                                                                                                <br><br>
                                                                                                                                <hr style="border: none; border-top: 1px solid #000; margin: 2px 0;">
                                                                                                                                <text class="text-blue-500"> 2ND TIME OUT:  </text>
                                                                                                                                
                                                                                                                            @endif
                                                                                                                        @endforeach

                                                                                                                        @if (!$isPmDisplayed)
                                                                                                                            <p>No PM check-in</p>
                                                                                                                        @endif
                                                                                                                @else
                                                                                                                    <p>No Check-Ins</p>
                                                                                                                @endif
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    @endforeach
                                                                                                </td>
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

                                                                                                        $finalHoursPM = $totalHoursPM;
                                                                                                        $roundedMinutesPM = round($totalMinutesPM + ($totalSecondsPM / 60));
                                                                                                        $finalSecondsAM = round($totalSecondsPM % 60);

                                                                                                        if ($finalSecondsPM >= 59) {
                                                                                                            $finalSecondsPM = 0;
                                                                                                            $roundedMinutesPM += 1;
                                                                                                        } else {
                                                                                                            $finalSecondsPM = 0;
                                                                                                        }

                                                                                                        if ($roundedMinutesPM >= 59) {
                                                                                                            $roundedMinutesPM = 0;
                                                                                                            $finalHoursPM += 1;
                                                                                                        }

                                                                                                        $finalMinutesPM = $roundedMinutesPM;
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
                                                                                                <td class="text-black border uppercase border-gray-400 text-xs font-semibold w-32">
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
                                                                                                        $modify_status == "Official Travel"
                                                                                                    ) {
                                                                                                        $remarkss = 'Official Travel';
                                                                                                    }
                                                                                                    else if (
                                                                                                        $lateDurationAM == 0 &&
                                                                                                        $lateDurationPM == 0 &&
                                                                                                        ($am == 0 || $am > 0 ) &&
                                                                                                        ($pm == 0 || $pm > 0 ) &&
                                                                                                        $totalHoursAM == 0 &&
                                                                                                        $totalMinutesAM == 0 &&
                                                                                                        $totalHoursPM == 0 &&
                                                                                                        $totalMinutesPM == 0 &&
                                                                                                        $modify_status == "On-campus"
                                                                                                    ) {
                                                                                                        $remarkss = 'Invalid Attendance';
                                                                                                    }
                                                                                                    
                                                                                                    else {
                                                                                                        if ($totalHoursAM == 0 && $totalMinutesAM == 0) {
                                                                                                            $remarkss = "Present but Absent Morning";
                                                                                                        }
                                                                                                        else if ($totalHoursPM == 0 && $totalMinutesPM == 0) {
                                                                                                            $remarkss = "Present but Absent Afternoon";
                                                                                                        }
                                                                                                        else {
                                                                                                            if ($lateDurationAM > 0 && $lateDurationPM > 0) {
                                                                                                                $remarkss = 'Present - Late AM & PM';
                                                                                                            } elseif ($lateDurationAM > 0) {
                                                                                                                $remarkss = 'Present - Late AM';
                                                                                                            } elseif ($lateDurationPM > 0) {
                                                                                                                $remarkss = 'Present - Late PM';
                                                                                                            } else {
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

                                                                                                    {{ $remarkss }}
                                                                                                </td>
                                                                                            </tr>
                                                                                            @endif
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        @endif
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const timeInput = document.getElementById('check_in_time_time');

    // Function to format and validate time input
    timeInput.addEventListener('input', function(event) {
        let value = event.target.value;
        
        // Regular expression to match time format with optional AM/PM
        const amPmPattern = /^([0-1]?[0-9]|1[0-2]):([0-5][0-9]):([0-5][0-9])\s?(AM|PM)?$/i;
        const match = amPmPattern.exec(value);
        
        if (match) {
            let hours = parseInt(match[1], 10);
            let minutes = parseInt(match[2], 10);
            let seconds = parseInt(match[3], 10);
            let ampm = match[4] ? match[4].toUpperCase() : '';

            // Validate and correct values
            if (hours < 1) hours = 12;
            if (hours > 12) hours = 12;
            if (minutes > 59) minutes = 59;
            if (seconds > 59) seconds = 59;

            // Update input value
            event.target.value = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} ${ampm}`;
        } else {
            // Remove invalid characters
            event.target.value = value.replace(/[^0-9:AMPM\s]/g, '');
        }
    });
});
</script>


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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    document.addEventListener('reload-success', () => {
        Alpine.store('loading', false);
    });
</script>

<!-- <script>
    document.addEventListener('livewire:load', function () {
        Livewire.hook('element.updated', (el, component) => {
            if (el.hasAttribute('wire:model')) {
                // Stop the loading spinner
                Alpine.store('loading', false);
            }
        });
        
        Livewire.hook('message.processed', (message, component) => {
            // Ensure to stop the spinner after processing is complete
            Alpine.store('loading', false);
        });
    });

    document.addEventListener('livewire:loading', function () {
        Alpine.store('loading', true);
    });

    document.addEventListener('livewire:loaded', function () {
        Alpine.store('loading', false);
    });
</script> -->


<!-- <script>
    document.addEventListener('livewire:load', function () {
        Livewire.hook('element.updated', (el, component) => {
            if (el.hasAttribute('wire:model')) {
                // Check if startDate and endDate have values after Livewire updates
                let startDate = document.getElementById('startDate').value;
                let endDate = document.getElementById('endDate').value;
                let loading = (startDate && endDate);
                
                // Update the Alpine.js store
                Alpine.store('loading', false);
            }
        });
    });
</script> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Time In Report - {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</title>
    <style>
        @page { margin:18px; }
    </style>
    <style>
        /* Add your PDF-specific styles here */
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0 auto; /* Center align the container */
        }
        .table-container {
            margin-top:20px;
            margin-bottom: 20px;
            text-align: center; /* Center align text within container */
        }

        .table-container table {
            width: 40%;
            border-collapse: collapse;
            /* background-color:red; */
            /* Float tables to achieve side-by-side display */
            margin-right: 5px; /* Add some margin between tables */
        }
        table, th, td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
            font-size:10px;
      
        }
        th {
            background-color: #f2f2f2;
        }
        .table-container .table2 {
            width: 60%;
            border-collapse: collapse;
          
            margin-right: 5px; /* Add some margin between tables */
        }
        .table2, th, td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;

        }
        .table th {
            background-color: #f2f2f2;
 
        }
        h3 {
            margin-left: 10px;
            text-align:center;
        }
        span {
            margin-left: 10px;
        }
            .border-right {
        border-right: 2px solid #000; /* Adjust the border style, width, and color as needed */
        padding-right: 10px; /* Optional: Add some padding for better spacing */
        margin-right: 10px; /* Optional: Add some margin to separate the content from the border */
    }

    .border-separator {
        display: inline-block;
        height: 100%;
        border-right: 2px solid #000; /* Adjust the border style, width, and color as needed */
        margin-right: 10px; /* Optional: Add some margin to separate the content from the border */
    }

    </style>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h3>ATTENDANCE REPORT</h3>
    <span>Employee: <text style="color:red">{{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text></span><br>
    <span>Employee ID: <text style="color:red">{{ $selectedEmployeeToShow->employee_id }}</text></span>
    @if ($selectedStartDate && $selectedEndDate)
        <div class="date-range">
            <span>
                Selected Date:  <text style="color:red">{{ \Carbon\Carbon::createFromFormat('m', str_pad($selectedMonth, 2, '0', STR_PAD_LEFT))->format('F') }} {{$selectedStartDate}} to {{ $selectedEndDate }}, {{ $selectedYear }}</text>
            </span>
        </div>
    @else
        <div class="date-range">
            <span>Selected Date: No date range selected</span>
        </div>
    @endif
    <center>
    <div class="table-container">
        @php
            // Define weekend days
            $weekendDays = ['Saturday', 'Sunday'];

            // Group check-ins and check-outs by employee and date
            $groupedAttendance = [];

            foreach ($attendanceTimeIn as $attendanceIn) {
                $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                $employeeId = $attendanceIn->employee->employee_id;
                $status = $attendanceIn->status; // Get status from check-in
                
                if (!isset($groupedAttendance[$employeeId][$date])) {
                    $groupedAttendance[$employeeId][$date] = [
                        'date' => date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)),
                        'check_ins' => [],
                        'check_outs' => [],
                        'status' => $status
                    ];
                }

                $groupedAttendance[$employeeId][$date]['check_ins'][] = date('g:i:s A', strtotime($attendanceIn->check_in_time));
            }

            foreach ($attendanceTimeOut as $attendanceOut) {
                $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                $employeeId = $attendanceOut->employee->employee_id;
                $status = $attendanceOut->status; // Get status from check-out
                
                if (!isset($groupedAttendance[$employeeId][$date])) {
                    $groupedAttendance[$employeeId][$date] = [
                        'date' => date('m-d-Y, (l)', strtotime($attendanceOut->check_out_time)),
                        'check_ins' => [],
                        'check_outs' => [],
                        'status' => $status
                    ];
                }

                $groupedAttendance[$employeeId][$date]['check_outs'][] = date('g:i:s A', strtotime($attendanceOut->check_out_time));
                
                // Update status with the check-out status, appending if it already exists
                if ($groupedAttendance[$employeeId][$date]['status'] !== $status) {
                    $groupedAttendance[$employeeId][$date]['status'] = ' Present';
                }
            }

            // Modify check-ins and check-outs based on status
            foreach ($groupedAttendance as $employeeId => $dates) {
                foreach ($dates as $date => &$attendance) {
                    $status = $attendance['status'];
                    $dayOfWeek = date('l', strtotime($attendance['date']));
                    
                    // Check if status is absent, weekend, or on leave
                    if ($status === 'Absent' || $status === 'On Leave' || $status === 'Weekend') {
                        $attendance['check_ins'] = [$status];
                        $attendance['check_outs'] = [$status];
                    } else {
                        // If status is none of the declared statuses, make it "Present"

                        $attendance['check_ins'] = ['Present'];
                        $attendance['check_outs'] = ['Present'];
                    }

                }
            }

            
        @endphp
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
                <tr>
                    <td  class="text-black border border-gray-400 px-2 py-1 font-bold">{{ date('M d, Y (D)', strtotime($attendance->worked_date)) }}</td>
                    <td class="text-black border border-gray-400 px-2 py-1 w-24">
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
                    <td class="text-black border border-gray-400 px-2 py-1 w-24">
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
                                        <p>No Check-Outs</p>
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
                    <td class="text-black border border-gray-400 px-2 py-1 w-[80px]">
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
                    <td class="text-black border border-gray-400 p-2 w-24">
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
                                if ($totalHoursAM == 0 && $totalMinutesAM == 0) {
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

                            {{ $remarkss }}
                        </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- <table style="float:left;" id="table1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Time - In</th>
                    <th>Time - Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedAttendance as $employeeId => $dates)
                    @foreach($dates as $date => $attendance)
                        @php
                            $status = $attendance['status'] ?? 'No Status';
                            $dayOfWeek = date('l', strtotime($attendance['date']));
                            $isWeekend = in_array($dayOfWeek, ['Saturday', 'Sunday']);
                            $isAbsentOrLeave = in_array($status, ['Absent', 'On Leave', 'Weekend']);
                        @endphp
                        <tr>
                            <td>{{ $employeeId }}</td>
                            <td>{{ $attendance['date'] }}</td>
                            <td>
                                @if ($isAbsentOrLeave)
                                    {{ $status }} 
                                @else
                                    @if (!empty($attendance['check_ins']))
                                        @foreach($attendance['check_ins'] as $checkIn)
                                            {{ $checkIn }}<br>
                                        @endforeach
                                    @else
                                        No Check-Ins
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($isAbsentOrLeave)
                                    {{ $status }}
                                @else
                                    @if (!empty($attendance['check_outs']))
                                        @foreach($attendance['check_outs'] as $checkOut)
                                            {{ $checkOut }}<br>
                                        @endforeach
                                    @else
                                        No Check-Outs
                                    @endif
                                @endif
                            </td>
                            <td>{{ $status }}</td> 
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table> -->

        
        <!-- make  -->
         <br><br><br>

        <div style="margin-top:200px; margin-left:20%;display:flex; justify-content:flex-end; align-items:center;">
            <div class="flex">
                <div class="flex flex-col">
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

                        <table class="border border-black" cellpadding="2">
                            <tr class="text-sm">
                                <th class="border border-black text-center">Duty Hours To Be Rendered</th>
                                <th class="border border-black text-center">Total Time Rendered</th>
                                <th class="border border-black text-center">Total Time Deduction (Late + Undertime + Absent Hours)</th>
                                <th class="border border-black text-center">Total Late</th>
                                <th class="border border-black text-center">Total Undertime</th>
                                <th class="border border-black text-center">Total Absent</th>
                            </tr>
                                <tr class="border border-black text-sm">

                                <td class="text-black border border-black">{{ $totalFormatted }}  from ({{ $attendanceDaysCount }} days total hour)</td>
                                <td class="text-black border border-black">{{$formattedTimeWorked}}</td>
                                <td class="text-black border border-black">{{ $finalHourDeductionFormatted }}</td>
                                <td class="text-black border border-black">{{ $lateFormatted }}</td>
                                <td class="text-black border border-black">{{ $undertimeFormatted }}</td>
                                <td class="text-black border border-black">{{ $absentFormatted }}</td>
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
    </center>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Time In Report</title>
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
             
            text-align: center; /* Center align text within container */
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
            float: left; /* Float tables to achieve side-by-side display */
            margin-right: 5px; /* Add some margin between tables */
        }
        table, th, td {
            border: 1px solid black;
            padding: 2px;
     
        }
        th {
            background-color: #f2f2f2;
        }
        h4 {
            margin-left: 10px;
            text-align:center;
            text-transform: uppercase;
            font-size:14px;
        }
        span {
            margin-left: 10px;
            text-transform: uppercase;
            font-weight:bold;

        }
        .text-center {
            text-align:center;
        }

    </style>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @foreach($departments as $department)
        <h4>ATTENDANCE REPORT in <text style="color:red;font-weight:bold">{{$department->department_abbreviation}} department</text></h4>
    @endforeach
    <!-- @foreach($departments as $department)
        <span>Department: <text style="color:red">{{$department->department_abbreviation}}</text></span><br>
    @endforeach -->
    @if ($selectedStartDate && $selectedEndDate)
        <div class="date-range">
            <span>Selected Date: <text style="color:red;font-weight:bold">{{ \Carbon\Carbon::createFromFormat('m', str_pad($selectedMonth, 2, '0', STR_PAD_LEFT))->format('F') }} {{ $selectedStartDate}} - {{ $selectedEndDate }}, {{$selectedYear}}</text></span>
        </div>
    @else
        <div class="date-range">
            <span>Selected Month: <text style="color:red;font-weight:bold">{{ \Carbon\Carbon::createFromFormat('m', str_pad($selectedMonth, 2, '0', STR_PAD_LEFT))->format('F') }}, {{$selectedYear}}</text></span>
        </div>
    @endif
    <div class="table-container">       
        <div style="margin-top:10px;display:flex; justify-content:flex-end; align-items:center;">
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
                            'employee_lastname' => $attendance->employee_lastname,
                            'employee_firstname' => $attendance->employee_firstname,
                            'employee_middlename' => $attendance->employee_middlename,
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


            <table class="border border-black" cellpadding="2">
                <thead>
                    <tr class="text-sm">
                        <th class="border border-black text-center">Employee ID</th>
                        <th class="border border-black text-center">Employee Name</th>
                        <th class="border border-black text-center">Duty Hours To Be Rendered</th>
                        <th class="border border-black text-center">Total Time Rendered</th>
                        <th class="border border-black text-center">Total Time Deduction</th>
                        <th class="border border-black text-center">Total Late</th>
                        <th class="border border-black text-center">Total Undertime</th>
                        <th class="border border-black text-center">Total Absent</th>
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

                            // Format the absence time
                            $absentFormatted = 
                                ($absentHours > 0 ? "{$absentHours} hr/s" : '') .
                                (($absentHours > 0 && $absentMinutes > 0) ? ", " : '') . 
                                ($absentMinutes > 0 ? "{$absentMinutes} min/s" : '') .
                                (($absentMinutes > 0 && $absentSeconds > 0) ? " " : '') . 
                                ($absentSeconds > 0 ? "{$absentSeconds} sec" : ($absentHours <= 0 && $absentMinutes <= 0 ? ' 0 ' : ''));

                            // Add the comma and space between the valuesdcd
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

                        
                                <tr class="border border-black text-sm">
                                <td class="text-black border border-black text-center">
                                    {{ $employeeData['employee_idd'] }}
                                </td>
                                <td class="text-black border border-black text-left ">
                                    {{ $employeeData['employee_lastname'] }},
                                    {{ $employeeData['employee_firstname'] }},
                                    {{ $employeeData['employee_middlename'] }}
                                </td>
                                <td class="text-black border border-black text-center ">{{ $totalFormatted }} ({{ $attendanceDaysCount }} days total hour)</td>
                                <td class="text-black border border-black text-center">{{$formattedTimeWorked}}</td>
                                <td class="text-black border border-black text-center">{{ $finalHourDeductionFormatted }}</td>
                                <td class="text-black border border-black text-center">{{ $lateFormatted }}</td>
                                <td class="text-black border border-black text-center">{{ $undertimeFormatted }}</td>
                                <td class="text-black border border-black text-center">{{ $absentFormatted }}</td>
                            </tr>

                            <!-- <tr>
                                <td class="border border-black text-red-500">{{ $totalFormatted }}   from ({{ $attendanceDaysCount }} days worked)</td>
                                <td class="border border-black text-red-500">{{ $hours }} hr/s, {{ $minutes }} min/s, {{ $seconds }} sec</td>
                                <td class="border border-black text-red-500">{{ $finalHourDeductionFormatted }}</td>
                                <td class="border border-black text-red-500">{{ $hoursM }} hr/s, {{ $minutesM }} min/s, {{ $secondsM }} sec</td>
                                <td class="border border-black text-red-500">{{ $undertimeFormatted }}</td>
                                <td class="border border-black text-red-500">{{ $absentFormatted }}</td>
                            </tr> -->
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

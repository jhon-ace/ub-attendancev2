
        
    @php
        // Group data by employee_id
        $employees = [];
        $departmentAbbreviation = null;
        $sameDepartment = true;
        $startDate = $startDate ?? null;
        $endDate = $endDate ?? null;

        foreach ($attendanceData as $attendance) {

            if ($departmentAbbreviation === null) {
                $departmentAbbreviation = $attendance->department_abbreviation; // Set initial abbreviation
            } elseif ($attendance->department_abbreviation !== $departmentAbbreviation) {
                $sameAbbreviation = false; // If any abbreviation is different, set flag to false
            }

            // Set startDate and endDate based on the attendance records

            // Check if $attendance has the required properties and handle accordingly
            if (isset($attendance->startDate) && ($startDate === null || $attendance->startDate < $startDate)) {
                $startDate = $attendance->startDate;
            }

            if (isset($attendance->endDate) && ($endDate === null || $attendance->endDate > $endDate)) {
                $endDate = $attendance->endDate;
            }

            // Handle the case when no dates are selected
            if ($startDate === null && $endDate === null) {
                // Handle the case where no dates are selected
                // For example, you might want to set default values or return an error message
                $startDate = null;
                $endDate = null;
            }

          

            $employeeId = $attendance->employee_id;
            $check = $attendance->check_in_time;
            if (!isset($employees[$employeeId])) {
                $employees[$employeeId] = [
                    'totalHours' => 0,
                    'total_hours_worked' => 0,
                    'hours_late_overall' => 0,
                    'hours_undertime_overall' => 0,
                    'employee_idd' => $attendance->employee_idd,
                    'employee_firstname' => $attendance->employee_firstname,
                    'employee_lastname' => $attendance->employee_lastname,
                    'employee_middlename' => $attendance->employee_middlename,
                    'uniqueDays' => [],
                    //'department_abbreviation' => $attendance->department_abbreviation,
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

    @if($sameDepartment && $departmentAbbreviation !== null)
        <h2 class="text-center font-bold">Department: {{ $departmentAbbreviation }}</h2>
    @endif

    @if($startDate && $endDate)
        <h4 class="text-center font-bold">
            {{ \Illuminate\Support\Carbon::parse($startDate)->format('F j, Y') }} 
            &nbsp; to &nbsp; 
            {{ \Illuminate\Support\Carbon::parse($endDate)->format('F j, Y') }}
        </h4>
    @else
        <p>No selected date range.</p>
    @endif

    <table class="border border-black h-full" cellpadding="2">
        <tr class="text-sm">
            <th class="border border-black text-center">Emp ID</th>
            <th class="border border-black text-center">Employee Fullname</th>
            <th class="border border-black text-center">Duty Hours To Be Rendered</th>
            <th class="border border-black text-center">Total Time Rendered</th>
            <th class="border border-black text-center">Total Time Deduction (late + undertime + absent)</th>
            <th class="border border-black text-center">Total Late</th>
            <th class="border border-black text-center">Total Undertime</th>
            <th class="border border-black text-center">Total Absent Hours</th>
        </tr>
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

            <tr class="border border-black text-sm">
                <td class="text-black border border-black text-center">
                    {{ $employeeData['employee_idd'] }}
                </td>
                <td class="text-black border border-black text-center">
                    {{ $employeeData['employee_lastname'] }}, 
                    {{ $employeeData['employee_firstname'] }}
                </td>
                <td class="text-black border border-black">{{ $totalFormatted }}  from ({{ $attendanceDaysCount }} days worked)</td>
                <td class="text-black border border-black">{{$formattedTimeWorked}}</td>
                <td class="text-black border border-black">{{ $finalHourDeductionFormatted }}</td>
                <td class="text-black border border-black">{{ $lateFormatted }}</td>
                <td class="text-black border border-black">{{ $undertimeFormatted }}</td>
                <td class="text-black border border-black text-center">{{ $absentFormatted }}</td>
            </tr>
        @endforeach
    </table>

    
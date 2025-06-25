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
            font-size:11px;
      
        }
        th {
            background-color: #f2f2f2;
        }
        .table-container .table2 {
            width: 40%;
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

    .was{
        border:0;
    }

    </style>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <img src="{{ asset('assets/img/newHeader.png') }}" alt="">
    <h3 style="margin-top:10px;margin-bottom:15px;font-size:15px">EMPLOYEE ATTENDANCE - DAILY TIME RECORD</h3>
    <span>Employee: <text style="color:red;font-size:12px">{{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}</text></span><br>
    <span>Employee ID: <text style="color:red;font-size:12px">{{ $selectedEmployeeToShow->employee_id }}</text></span>
    @if ($selectedStartDate && $selectedEndDate)
        <div class="date-range">
            <span>
                Selected Date:  <text style="color:red;font-size:12px">{{ \Carbon\Carbon::createFromFormat('m', str_pad($selectedMonth, 2, '0', STR_PAD_LEFT))->format('F') }} {{$selectedStartDate}} to {{ $selectedEndDate }}, {{ $selectedYear }}</text>
            </span>
        </div>
    @else
        <div class="date-range">
            <span>Selected Date: <text style=";font-size:12px">No date range selected</text></span>
        </div>
    @endif
    
    <center>
    
    <div class="table-container">
        @php
            foreach ($attendanceTimeIn as $attendanceIn) {
                $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                $employeeId = $attendanceIn->employee->employee_id;
                $employeeName = $attendanceIn->employee->name;

                if (!isset($groupedAttendance[$employeeId][$date])) {
                    $groupedAttendance[$employeeId][$date] = [
                        'date' => date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)),
                        'check_ins' => [],
                        'check_outs' => [],
                        'employee_name' => $employeeName,
                        'status' => $attendanceIn->status ?? null
                    ];
                }

                $groupedAttendance[$employeeId][$date]['check_ins'][] = date('g:i:s A', strtotime($attendanceIn->check_in_time));
            }

            

            foreach ($attendanceTimeOut as $attendanceOut) {
                $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                $employeeId = $attendanceOut->employee->employee_id;
                $employeeName = $attendanceOut->employee->name;

                if (!isset($groupedAttendance[$employeeId][$date])) {
                    $groupedAttendance[$employeeId][$date] = [
                        'date' => date('m-d-Y, (l)', strtotime($attendanceOut->check_out_time)),
                        'check_ins' => [],
                        'check_outs' => [],
                        'employee_name' => $employeeName,
                        'status' => $attendanceOut->status ?? null
                    ];
                }

                $groupedAttendance[$employeeId][$date]['check_outs'][] = date('g:i:s A', strtotime($attendanceOut->check_out_time));
            }

        @endphp

        {{-- Attendance Table --}}
        <table border="1" cellpadding="8" cellspacing="0"  class="table-auto min-w-full text-center text-sm mb-4 divide-y divide-gray-200">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedAttendance as $employeeId => $dates)
                    @foreach ($dates as $date => $attendance)
                        @php
                            $checkIns = collect($attendance['check_ins']);
                            $checkOuts = collect($attendance['check_outs']);
                            $status = $attendance['status'] ?? null;

                            $allCheckInsAtMidnight = $checkIns->isNotEmpty() && $checkIns->every(fn($in) => $in === '12:00:00 AM');
                            $allCheckOutsAtMidnight = $checkOuts->isNotEmpty() && $checkOuts->every(fn($out) => $out === '12:00:00 AM');

                            $isWeekend = $status === 'Weekend' && $allCheckInsAtMidnight && $allCheckOutsAtMidnight;
                            $isAbsent = $status === 'Absent';
                            $isHoliday = $status === 'Holiday';
                            $isOnLeave = $status === 'On Leave';
                            $isOfficialTravel = $status === 'Official Travel';
                        @endphp
                        <tr>
                            <td style="margin-top:5px">{{ $employeeId }}</td>
                            <td style="margin-top:5px">{{ $attendance['date'] }}</td>

                            {{-- Time In --}}
                            <td style="vertical-align: top;">
                                @if ($isOnLeave) 
                                    <div style="text-transform:uppercase;color:red">On Leave</div>
                                @elseif ($isOfficialTravel)
                                    <div style="text-transform:uppercase;color:red">Official Travel</div>
                                @elseif ($isHoliday)
                                    <div style="color:blue; font-weight:bold;">Holiday</div>
                                @elseif ($isWeekend)
                                    <div style="text-transform:uppercase;color:red">Weekend</div>
                                @else
                                    @php
                                        $validCheckIns = $checkIns->filter(fn($in) => $in !== '12:00:00 AM')->values();
                                        $validCheckOuts = $checkOuts->filter(fn($out) => $out !== '12:00:00 AM')->values();
                                        $allInAtMidnight = $checkIns->count() >= 2 && $checkIns->every(fn($in) => $in === '12:00:00 AM');
                                        $allOutAtMidnight = $checkOuts->count() >= 2 && $checkOuts->every(fn($out) => $out === '12:00:00 AM');
                                        $singleInSingleOut = $validCheckIns->count() === 1 && $validCheckOuts->count() === 1;
                                    @endphp

                                    @if ($isAbsent || ($validCheckIns->isEmpty() && $allInAtMidnight && $allOutAtMidnight))
                                        <div></div>
                                        <hr style="border: none; border-top: 1px solid black; margin: 20px 0;">
                                        <div></div>
                                    @elseif ($validCheckIns->isEmpty())
                                        <div></div>
                                    @elseif ($singleInSingleOut)
                                        <div>{{ $validCheckIns[0] }}</div>
                                        <hr style="border: none; border-top: 1px solid black; margin: 4px 0;">
                                        <div style="height: 20px;"></div>
                                    @elseif ($validCheckIns->count() === 1)
                                        {{-- Only one time-in: show time + hr + blank space --}}
                                        <div>{{ $validCheckIns[0] }}</div>
                                        <hr style="border: none; border-top: 1px solid black; margin: 4px 0;">
                                        <div style="height: 20px;"></div>
                                    @else
                                        @foreach ($validCheckIns as $index => $in)
                                            <div>{{ $in }}</div>
                                            @if ($index < $validCheckIns->count() - 1)
                                                <hr style="border: none; border-top: 1px solid black; margin: 4px 0;">
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </td>
                            
                            {{-- Time Out --}}
                            <td style="vertical-align: top;">
                                @if ($isOnLeave) 
                                    <div style="text-transform:uppercase;color:red">On Leave</div>
                                @elseif ($isOfficialTravel)
                                    <div style="text-transform:uppercase;color:red">Official Travel</div>
                                @elseif ($isHoliday)
                                    <div style="color:blue; font-weight:bold;">Holiday</div>
                                @elseif ($isWeekend)
                                    <div style="text-transform:uppercase;color:red">Weekend</div>
                                @else
                                    @php
                                        $validCheckIns = $checkIns->filter(fn($in) => $in !== '12:00:00 AM')->values();
                                        $validCheckOuts = $checkOuts->filter(fn($out) => $out !== '12:00:00 AM')->values();
                                        $allOutAtMidnight = $checkOuts->count() >= 2 && $checkOuts->every(fn($out) => $out === '12:00:00 AM');
                                        $allInAtMidnight = $checkIns->count() >= 2 && $checkIns->every(fn($in) => $in === '12:00:00 AM');
                                        $singleInSingleOut = $validCheckIns->count() === 1 && $validCheckOuts->count() === 1;
                                        $oneInZeroOut = $validCheckIns->count() === 1 && $validCheckOuts->isEmpty();
                                    @endphp

                                    @if ($isAbsent || ($validCheckOuts->isEmpty() && $allOutAtMidnight && $allInAtMidnight))
                                        <div></div>
                                        <hr style="border: none; border-top: 1px solid black; margin: 20px 0;">
                                        <div></div>

                                    @elseif ($allOutAtMidnight)
                                        @for ($i = 0; $i < $checkOuts->count(); $i++)
                                            <div></div>
                                            @if ($i < $checkOuts->count() - 1)
                                                <hr style="border: none; border-top: 1px solid black; margin: 25px 0;">
                                            @endif
                                        @endfor

                                    @elseif ($oneInZeroOut)
                                        {{-- Show blank for manual time-out --}}
                                        <div></div>
                                        <hr style="border: none; border-top: 1px solid black; margin: 25px 0;">
                                        

                                    @elseif ($singleInSingleOut)
                                        <div>{{ $validCheckOuts[0] }}</div>
                                        <hr style="border: none; border-top: 1px solid black; margin: 4px 0;">
                                        <div style="height: 20px;"></div>

                                    @elseif ($validCheckOuts->count() === 1)
                                        <div>{{ $validCheckOuts[0] }}</div>
                                        <hr style="border: none; border-top: 1px solid black; margin: 4px 0;">

                                    @else
                                        @foreach ($validCheckOuts as $index => $out)
                                            <div>{{ $out }}</div>
                                            @if ($index < $validCheckOuts->count() - 1)
                                                <hr style="border: none; border-top: 1px solid black; margin: 4px 0;">
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            </td>






                        </tr>

                    @endforeach
                @endforeach



            </tbody>
        </table>
        <br>

            <div style="font-weight: bold;text-align:left">REMARKS:</div>
            <div style="margin-top: 10px;">
                <div style="border-bottom: 1px solid black; height: 20px;"></div>
                <div style="border-bottom: 1px solid black; height: 20px;"></div>
                <div style="border-bottom: 1px solid black; height: 20px;"></div>
                <div style="border-bottom: 1px solid black; height: 20px;"></div>
            </div>
        
            
       <div style="margin-top: 10px; font-size: 14px;">
            <div style="margin-top: 40px;">
                <!-- First Table: Employee Info -->
                <table style="width: 100%; font-size: 14px; text-align: center; border-collapse: collapse; border: 1px solid black;margin: 0; padding: 0;">
                    <tr>
                        <td style="width: 33%; height: 50px;">
                            {{ $selectedEmployeeToShow->employee_lastname }}, {{ $selectedEmployeeToShow->employee_firstname }} {{ $selectedEmployeeToShow->employee_middlename }}
                        </td>
                        <td style="width: 33%; height: 50px;"></td>
                        <td style="width: 33%; height: 50px;"></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:10px;">Name of Employee</td>
                        <td style="padding-bottom:10px">Dean/Office Head</td>
                        <td style="padding-bottom:10px">Vice President</td>
                    </tr>
                </table>

                <!-- Second Table: Signature Block -->
                <table style="width: 100%; margin-top: 0px; font-size: 14px; text-align: center; border-collapse: collapse; border: 1px solid black;margin: 0; padding: 0;">
                    <tr>
                        <td style="width: 33.33%; padding-top: 60px;padding-bottom:10px">
                            <strong>PRESCIOUS JOY D. BAGUIO, MPM</strong><br>
                            <span style="font-size: 13px;">Human Resource Manager</span>
                        </td>
                        <td style="width: 33.33%; padding-top: 60px;padding-bottom:10px">
                            <strong>RIA EVA M. SEVILLA, MAHESOS, MPA</strong><br>
                            <span style="font-size: 13px;">Vice President for Administration</span>
                        </td>
                    </tr>
                </table>

            </div>
        </div>





        



    </div>
   

    </center>

</body>
</html>

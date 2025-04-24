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
    <h3>EMPLOYEE ATTENDANCE - DAILY TIME RECORD</h3>
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
        // Define weekend days (optional, not used since we're not filtering)
        $weekendDays = ['Saturday', 'Sunday'];

        // Group check-ins and check-outs by employee and date
        $groupedAttendance = [];

        foreach ($attendanceTimeIn as $attendanceIn) {
            $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
            $employeeId = $attendanceIn->employee->employee_id;
            $employeeName = $attendanceIn->employee->name;

            if (!isset($groupedAttendance[$employeeId][$date])) {
                $groupedAttendance[$employeeId][$date] = [
                    'date' => date('m-d-Y, (l)', strtotime($attendanceIn->check_in_time)),
                    'check_ins' => [],
                    'check_outs' => [],
                    'employee_name' => $employeeName
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
                    'employee_name' => $employeeName
                ];
            }

            $groupedAttendance[$employeeId][$date]['check_outs'][] = date('g:i:s A', strtotime($attendanceOut->check_out_time));
        }
    @endphp

    {{-- Attendance Table --}}
    <table border="1" cellpadding="8" cellspacing="0" class="table-auto min-w-full text-center text-xs mb-4 divide-y divide-gray-200">
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
                    <tr>
                        <td>{{ $employeeId }}</td>
                        <td>{{ $attendance['date'] }}</td>
                        <td>
                            @if (!empty($attendance['check_ins']))
                                @foreach ($attendance['check_ins'] as $checkIn)
                                    <div>{{ $checkIn }}</div>
                                @endforeach
                            @else
                                <div>-</div>
                            @endif
                        </td>
                        <td>
                            @if (!empty($attendance['check_outs']))
                                @foreach ($attendance['check_outs'] as $checkOut)
                                    <div>{{ $checkOut }}</div>
                                @endforeach
                            @else
                                <div>-</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    </div>
    </center>
</body>
</html>

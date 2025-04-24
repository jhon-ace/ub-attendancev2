<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report - {{ $selectedEmployeeToShow->student_lastname }}, {{ $selectedEmployeeToShow->student_firstname }} {{ $selectedEmployeeToShow->student_middlename }}</title>
    <style>
        @page { margin:18px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0 auto;
        }
        .table-container {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .table-container table {
            width: 70%;
            border-collapse: collapse;
            margin-right: 5px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 2px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h4 {
            text-align: center;
        }
        span {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h4>ATTENDANCE REPORT</h4>
    <span>STUDENT: <text style="color:red">{{ $selectedEmployeeToShow->student_lastname }}, {{ $selectedEmployeeToShow->student_firstname }} {{ $selectedEmployeeToShow->student_middlename }}</text></span><br>
    <span>STUDENT ID: <text style="color:red">{{ $selectedEmployeeToShow->student_id }}</text></span>
    
    @if ($selectedStartDate && $selectedEndDate)
        <div class="date-range">
            <span>
                Selected Date:  
                <text style="color:red">
                    {{ \Carbon\Carbon::createFromFormat('m', str_pad($selectedMonth, 2, '0', STR_PAD_LEFT))->format('F') }} 
                    {{ $selectedStartDate }} to {{ $selectedEndDate }}, {{ $selectedYear }}
                </text>
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
                $groupedAttendance = [];

                // Step 1: Process attendanceTimeIn
                $attendanceInRecords = [];
                foreach ($attendanceTimeIn as $attendanceIn) {
                    $date = date('Y-m-d', strtotime($attendanceIn->check_in_time));
                    $studentId = $attendanceIn->student_id;

                    $attendanceInRecords[] = (object) [
                        'date' => $date,
                        'student_id' => $studentId,
                        'check_in_time' => $attendanceIn->check_in_time,
                        'check_out_time' => null
                    ];
                }

                // Step 2: Process attendanceTimeOut
                $attendanceOutRecords = [];
                foreach ($attendanceTimeOut as $attendanceOut) {
                    $date = date('Y-m-d', strtotime($attendanceOut->check_out_time));
                    $studentId = $attendanceOut->student_id;

                    $attendanceOutRecords[] = (object) [
                        'date' => $date,
                        'student_id' => $studentId,
                        'check_out_time' => $attendanceOut->check_out_time
                    ];
                }

                // Step 3: Pair Check-in and Check-out in Sequence
                foreach ($attendanceInRecords as $inRecord) {
                    $date = $inRecord->date;
                    $studentId = $inRecord->student_id;

                    if (!isset($groupedAttendance[$date])) {
                        $groupedAttendance[$date] = [];
                    }

                    // Find the first unmatched attendanceOut record
                    foreach ($attendanceOutRecords as $key => $outRecord) {
                        if ($outRecord->date == $date && $outRecord->student_id == $studentId) {
                            $inRecord->check_out_time = $outRecord->check_out_time;

                            // Remove the matched check-out record to avoid duplication
                            unset($attendanceOutRecords[$key]);
                            break;
                        }
                    }

                    $groupedAttendance[$date][] = $inRecord;
                }
            @endphp

            <table class="w-full table-fixed border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="text-black border border-gray-400 w-1/3 text-center">Date</th>
                        <th class="text-black border border-gray-400 w-1/3 text-center">Check-In Time (On-Campus)</th>
                        <th class="text-black border border-gray-400 w-1/3 text-center">Check-Out Time (Off-Campus)</th>
                    </tr>
                </thead>
            </table>

            <div class="overflow-y-auto max-h-96">
                <table class="w-full table-fixed border-collapse">
                    <tbody>
                        @foreach ($groupedAttendance as $date => $records)
                            @foreach ($records as $index => $record)
                                <tr class="hover:bg-gray-100">
                                    @if ($index == 0)
                                        <!-- Only show the date once for the first record in each date group -->
                                        <td class="text-black border text-center font-bold border-gray-400 align-middle w-1/3" rowspan="{{ count($records) }}">
                                            {{ date('m-d-Y (l)', strtotime($date)) }}
                                        </td>
                                    @endif
                                    <td class="text-black border border-gray-400 w-1/3 text-center align-middle">
                                        @if ($record->check_in_time)
                                            {{ date('g:i:s A', strtotime($record->check_in_time)) }}
                                        @else
                                            No check-in recorded
                                        @endif
                                    </td>
                                    <td class="text-black border border-gray-400 w-1/3 text-center align-middle">
                                        @if ($record->check_out_time)
                                            {{ date('g:i:s A', strtotime($record->check_out_time)) }}
                                        @else
                                            No check-out recorded
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </center>
</body>
</html>

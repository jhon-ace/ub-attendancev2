<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\EmployeeAttendanceTimeIn;
use App\Models\Admin\EmployeeAttendanceTimeOut;
use App\Models\Admin\Employee; // Assuming you have an Employee model
use App\Models\Admin\DepartmentWorkingHour; // Assuming you have an Employee model
use DateTime;
use DateTimeZone;

class SubmitMissingAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:submit-missing-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit missing attendance records at midnight';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get current time with timezone
        $now = new DateTime('now', new DateTimeZone('Asia/Taipei'));
        $currentDate = $now->format('Y-m-d');
        $dayOfWeek = $now->format('w');
        // Fetch all employees

        $employees = Employee::all();

        $this->info("Processing attendance for employees...");

        foreach ($employees as $employee) {
            $employeeId = $employee->id;
            $char = $employee->department_id;
      

            $departmentWorkingHour = DepartmentWorkingHour::where('department_id', $char)
                                                ->where('day_of_week', $dayOfWeek)
                                                ->first();
   
            // Check if there are check-in or check-out records for the current date
            $hasCheckIn = EmployeeAttendanceTimeIn::whereDate('check_in_time', $currentDate)
                ->where('employee_id', $employeeId)
                ->exists();

            $hasCheckOut = EmployeeAttendanceTimeOut::whereDate('check_out_time', $currentDate)
                ->where('employee_id', $employeeId)
                ->exists();

                  $status = ($dayOfWeek == 6 || $dayOfWeek == 0) ? "Weekend" : "Absent";

            // Create missing check-in record if none exists
            if (!$hasCheckIn) {
                $attendance = new EmployeeAttendanceTimeIn();
                $attendance->employee_id = $employeeId;
                $attendance->check_in_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->save();

                $attendance = new EmployeeAttendanceTimeIn();
                $attendance->employee_id = $employeeId;
                $attendance->check_in_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->save();

                $this->info("Created missing check-in record for employee ID {$employeeId}.");
            } else {
                $this->info("Check-in record already exists for employee ID {$employeeId}.");
            }

            // Create missing check-out record if none exists
            if (!$hasCheckOut) {
                $attendance = new EmployeeAttendanceTimeOut();
                $attendance->employee_id = $employeeId;
                $attendance->check_out_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->save();

                $attendance = new EmployeeAttendanceTimeOut();
                $attendance->employee_id = $employeeId;
                $attendance->check_out_time = "{$currentDate} 00:00:00";
                $attendance->status = $status;
                $attendance->save();
                $this->info("Created missing check-out record for employee ID {$employeeId}.");
            } else {
                $this->info("Check-out record already exists for employee ID {$employeeId}.");
            }
        }

        $this->info("Attendance processing complete.");
    }
}


// DONE OKEY
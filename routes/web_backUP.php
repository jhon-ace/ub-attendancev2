<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\WorkingHourController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\EmployeeAttendanceController;
use App\Http\Controllers\Admin\CSVImportController;
use App\Http\Controllers\Admin\PublicPageController;
use App\Http\Controllers\Admin\FingerprintController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if(Auth::check() && Auth::user()->hasRole('admin'))
    {
        return redirect()->route('admin.dashboard');
    } 
    else if(Auth::check() && Auth::user()->hasRole('admin_staff')) 
    {
        return redirect()->route('admin_staff.dashboard');
    }
    else 
    {
        return view('auth.login');
    }
    
});

Route::get('/attendance/portal', [PublicPageController::class, 'portalTimeIn'])->name('attendance.portal');
Route::post('/attendance/portal', [PublicPageController::class, 'submitAttendance'])->name('admin.attendance.store');

Route::get('/attendance/portal/vdt', [PublicPageController::class, 'portalTimeInvdt'])->name('attendance.portal.vdt');
Route::post('/attendance/portal/vdt', [PublicPageController::class, 'submitAttendancevdt'])->name('admin.attendance.store.vdt');

Route::get('/attendance/portal/student', [PublicPageController::class, 'portalTimeInStudent'])->name('attendance.portal.student');
Route::post('/attendance/portal/student', [PublicPageController::class, 'submitAttendanceStudent'])->name('admin.attendance.store.student');

// Admin Routes
Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        //dashboard
        // Route::get('/dashboard', function () {
        //     return view('dashboard');
        // })->name('dashboard');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //school  routes
        Route::resource('school', SchoolController::class)->names([
            'index' => 'school.index',
            'create' => 'school.create',
            'store' => 'school.store',
            'edit' => 'school.edit',
            'update' => 'school.update'
        ]);
        Route::delete('school', [SchoolController::class, 'deleteAll'])->name('school.deleteAll');
        
        //department routes
        Route::resource('department', DepartmentController::class)->names([
            'index' => 'department.index',
            'create' => 'department.create',
            'store' => 'department.store',
            'edit' => 'department.edit',
            'update' => 'department.update'
        ]);
        Route::delete('department', [DepartmentController::class, 'deleteAll'])->name('department.deleteAll');
        
        //department_working_hours
        Route::get('/department-working-hour', [WorkingHourController::class, 'index'])->name('workinghour.index');
        Route::post('/department-working-hour', [WorkingHourController::class, 'store'])->name('workinghour.store');
        Route::put('/department-working-hour/{id}', [WorkingHourController::class, 'update'])->name('workinghour.update');
        Route::delete('/department-working-hour/{id}', [WorkingHourController::class, 'destroy'])->name('workinghour.delete');



        // course routes
        Route::get('/courses', [CourseController::class, 'index'])->name('course.index');
        Route::post('/courses', [CourseController::class, 'store'])->name('course.store');
        Route::get('/courses/{course_id}/edit', [CourseController::class, 'edit'])->name('course.edit');
        Route::put('courses/{id}', [CourseController::class, 'update'])->name('course.update');
        Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
        Route::delete('courses', [CourseController::class, 'deleteAll'])->name('course.deleteAll');

        // Employee routes
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employee.store');
        // Route::get('/employees/{employee_id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employee.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
        Route::delete('employee', [EmployeeController::class, 'deleteAll'])->name('employee.deleteAll');

        // CSV Route
        // routes/web.php
        Route::post('/import-csv', [CSVImportController::class, 'import'])->name('csv.import');
        Route::post('/import-csv/department', [CSVImportController::class, 'importDepartment'])->name('csv.import.department');


        // Employee Attendance routes
        Route::get('/employees/attendance', [EmployeeAttendanceController::class, 'employee'])->name('attendance.employee_attendance');
        Route::get('/employees/attendance/payroll', [EmployeeAttendanceController::class, 'employeelist'])->name('attendance.employee_attendance.payroll');
        Route::get('/employees/attendance/payroll/all', [EmployeeAttendanceController::class, 'employeelistall'])->name('attendance.employee_attendance.payroll.all');

        Route::post('/employees/attendance/add-time-in', [EmployeeAttendanceController::class, 'employeeAddTimeIn'])->name('attendance.employee_attendance.addIn');
        Route::post('/employees/attendance/add-time-out', [EmployeeAttendanceController::class, 'employeeAddTimeOut'])->name('attendance.employee_attendance.addOut');
        Route::delete('employees/attendance/delete-time-in/{id}', [EmployeeAttendanceController::class, 'deleteTimeIn'])->name('attendance.employee_attendance.deleteTimeIn');
        Route::delete('employees/attendance/delete-time-out/{id}', [EmployeeAttendanceController::class, 'deleteTimeOut'])->name('attendance.employee_attendance.deleteTimeOut');
        Route::get('/employees/attendance/search', [EmployeeAttendanceController::class, 'employeeSearch'])->name('attendance.employee_attendance.search');
        Route::get('/generate-pdf', [EmployeeAttendanceController::class, 'generatePDF'])->name('generate.pdf');

        Route::get('/attendance/time-in/portal', [EmployeeAttendanceController::class, 'portalTimeIn'])->name('attendance.time-in.portal');
        // Route::post('/attendance/time-in/portal', [EmployeeAttendanceController::class, 'portalTimeIn'])->name('attendance.time-in.portal');
        Route::get('/attendance/time-out/portal', [EmployeeAttendanceController::class, 'portalTimeOut'])->name('attendance.time-out.portal');

        Route::post('/attendance/time-in/portal', [EmployeeAttendanceController::class, 'submitPortalTimeIn'])->name('attendance.time-in.store');
        Route::post('/attendance/time-out/portal', [EmployeeAttendanceController::class, 'submitPortalTimeOut'])->name('attendance.time-out.store');   

        Route::post('/attendance/modify', [EmployeeAttendanceController::class, 'modifyAttendance'])->name('attendance.modify');
        Route::post('/attendance/modify/halfday', [EmployeeAttendanceController::class, 'modifyAttendanceHalfDay'])->name('attendance.modify.halfDay');   
           
        //Student Attendance routes
        Route::get('/students/attendance', [EmployeeAttendanceController::class, 'student'])->name('attendance.student_attendance');

        // Student routes
        Route::get('/students', [StudentController::class, 'index'])->name('student.index');
        Route::post('/students', [StudentController::class, 'store'])->name('student.store');
        // Route::get('/students/{student_id}/edit', [StudentController::class, 'edit'])->name('student.edit');
        Route::put('/students/{id}', [StudentController::class, 'update'])->name('student.update');
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
        Route::delete('students', [StudentController::class, 'deleteAll'])->name('student.deleteAll');

        
        //staff routes
        Route::resource('staff', AdminStaffController::class)->names([
            'index' => 'staff.index',
            'create' => 'staff.create',
            'store' => 'staff.store',
            'edit' => 'staff.edit',
            'update' => 'staff.update'
        ]);
        Route::delete('staff', [AdminStaffController::class, 'deleteAll'])->name('staff.deleteAll');


        Route::put('/edit-TimeIn/{id}', [EmployeeAttendanceController::class, 'attendanceTimeInUpdate'])->name('attendanceIn.edit');
        Route::put('/edit-TimeOut/{id}', [EmployeeAttendanceController::class, 'attendanceTimeOutUpdate'])->name('attendanceOut.edit');

        Route::get('/insert-photos', [EmployeeController::class, 'employee_insertPhoto'])->name('insert.photos');

        //Graceperiod
        Route::post('/setGracePeriod', [EmployeeAttendanceController::class, 'storePeriod'])->name('attendance.gracePeriod');
        Route::put('/setGracePeriod/update/{id}', [EmployeeAttendanceController::class, 'updatePeriod'])->name('attendance.gracePeriod.update');
        Route::delete('/deletePeriod/{id}', [EmployeeAttendanceController::class, 'deletePeriod'])->name('attendance.gracePeriod.delete');
        //settings nav route
        Route::get('/setGracePeriodSet', [EmployeeAttendanceController::class, 'storePeriodView'])->name('attendance.gracePeriodSet');
        Route::get('/setAttendanceHoliday', [EmployeeAttendanceController::class, 'holiday'])->name('attendance.holiday');
        Route::post('/setHoliday', [EmployeeAttendanceController::class, 'setHoliday'])->name('attendance.setHoliday');



    });




    // ADMIN STAFF ROUTES ----------------------------------------------------------------------------
    Route::middleware(['role:admin_staff'])->prefix('staff')->name('admin_staff.')->group(function () {
         
            Route::get('/dashboard', [DashboardController::class, 'indexStaff'])->name('dashboard');


            Route::controller(EmployeeAttendanceController::class)->group(function () {
                Route::get('/employees/attendance/search', 'employeeSearch')->name('attendance.employee_attendance.search');
                Route::get('/employees/attendance', 'employee')->name('attendance.employee_attendance');
                Route::get('/employees/attendance/payroll', 'employeelist')->name('attendance.employee_attendance.payroll');
            });


 
            //School Routes
            Route::resource('school', SchoolController::class)->names([
                    'index' => 'school.index',
                    'create' => 'school.create',
                    'store' => 'school.store',
                    'edit' => 'school.edit',
                    'update' => 'school.update'
                ]);
            Route::delete('school', [SchoolController::class, 'deleteAll'])->name('school.deleteAll');

            //department routes
            Route::resource('department', DepartmentController::class)->names([
                    'index' => 'department.index',
                    'create' => 'department.create',
                    'store' => 'department.store',
                    'edit' => 'department.edit',
                    'update' => 'department.update'
                ]);
            Route::delete('department', [DepartmentController::class, 'deleteAll'])->name('department.deleteAll');

            //department_working_hours
            Route::get('/department-working-hour', [WorkingHourController::class, 'index'])->name('workinghour.index');
            Route::post('/department-working-hour', [WorkingHourController::class, 'store'])->name('workinghour.store');
            Route::put('/department-working-hour/{id}', [WorkingHourController::class, 'update'])->name('workinghour.update');
            Route::delete('/department-working-hour/{id}', [WorkingHourController::class, 'destroy'])->name('workinghour.delete');

            //Courses routes staff
            Route::get('/courses', [CourseController::class, 'index'])->name('course.index');
            Route::post('/courses', [CourseController::class, 'store'])->name('course.store');
            Route::get('/courses/{course_id}/edit', [CourseController::class, 'edit'])->name('course.edit');
            Route::put('courses/{id}', [CourseController::class, 'update'])->name('course.update');
            Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
            Route::delete('courses', [CourseController::class, 'deleteAll'])->name('course.deleteAll');
            
            // Employee routes
            Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
            Route::post('/employees', [EmployeeController::class, 'store'])->name('employee.store');
            // Route::get('/employees/{employee_id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
            Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employee.update');
            Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
            Route::delete('employee', [EmployeeController::class, 'deleteAll'])->name('employee.deleteAll');

            // Student routes
            Route::get('/students', [StudentController::class, 'index'])->name('student.index');
            Route::post('/students', [StudentController::class, 'store'])->name('student.store');
            // Route::get('/students/{student_id}/edit', [StudentController::class, 'edit'])->name('student.edit');
            Route::put('/students/{id}', [StudentController::class, 'update'])->name('student.update');
            Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
            Route::delete('students', [StudentController::class, 'deleteAll'])->name('student.deleteAll');

            
            Route::get('/setAttendanceHoliday', [EmployeeAttendanceController::class, 'holiday'])->name('attendance.holiday');
            Route::post('/setHoliday', [EmployeeAttendanceController::class, 'setHoliday'])->name('attendance.setHoliday');
            Route::delete('/deleteHoliday/{id}', [EmployeeAttendanceController::class, 'deleteHoliday'])->name('holiday.destroy');


            Route::get('/setGracePeriodSet', [EmployeeAttendanceController::class, 'storePeriodView'])->name('attendance.gracePeriodSet');
            Route::post('/setGracePeriod', [EmployeeAttendanceController::class, 'storePeriod'])->name('attendance.gracePeriod');
            Route::put('/setGracePeriod/update/{id}', [EmployeeAttendanceController::class, 'updatePeriod'])->name('attendance.gracePeriod.update');
            Route::delete('/deletePeriod/{id}', [EmployeeAttendanceController::class, 'deletePeriod'])->name('attendance.gracePeriod.delete');


            Route::get('/delete-date-of-an-attendance', [EmployeeAttendanceController::class, 'delete_attendance'])->name('delete_attendance');
            Route::post('/delete-date-of-an-attendance-submit', [EmployeeAttendanceController::class, 'validate_delete_attendance'])->name('submit_delete_attendance');
            
            

            Route::get('/fingerprint', [FingerprintController::class, 'index'])->name('show.fingerprint');


            Route::get('/enable-fingerprint', [FingerprintController::class, 'activate_fingerprint'])->name('fingerprint');
            Route::put('/set-fingerprint/{id}', [FingerprintController::class, 'set_fingerprint'])->name('enable_fingerprint');
            

    });



    // For HR ------------------------------------------------------------------------------------

    Route::middleware(['role:employee'])->prefix('hr')->name('hr.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'indexStaff'])->name('dashboard');

        //department routes
        Route::resource('department', DepartmentController::class)->names([
                'index' => 'department.index',
                'create' => 'department.create',
                'store' => 'department.store',
                'edit' => 'department.edit',
                'update' => 'department.update'
            ]);
        Route::delete('department', [DepartmentController::class, 'deleteAll'])->name('department.deleteAll');

        //department_working_hours
        Route::get('/department-working-hour', [WorkingHourController::class, 'index'])->name('workinghour.index');
        Route::post('/department-working-hour', [WorkingHourController::class, 'store'])->name('workinghour.store');
        Route::put('/department-working-hour/{id}', [WorkingHourController::class, 'update'])->name('workinghour.update');
        Route::delete('/department-working-hour/{id}', [WorkingHourController::class, 'destroy'])->name('workinghour.delete');





    });



});


// Route::middleware(['role:student'])->group(function () {
    //     // Student routes
    // });
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

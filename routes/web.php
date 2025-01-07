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
use App\Http\Controllers\Admin\SAOController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if(Auth::check() && Auth::user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } 
    else if(Auth::check() && Auth::user()->hasRole('admin_staff')) {
        return redirect()->route('admin_staff.dashboard');
    } else if(Auth::check() && Auth::user()->hasRole('sao')) {
        return redirect()->route('sao.dashboard');

    } else {
        return view('auth.login');
    }
    
});

Route::controller(PublicPageController::class)->group(function () {
    Route::get('/attendance/portal', 'portalTimeIn')->name('attendance.portal');
    Route::post('/attendance/portal', 'submitAttendance')->name('admin.attendance.store');

    Route::get('/attendance/fetch-latest-ub', 'fetchLatestEmployeeUB')->name('admin.attendance.fetch.latest.ub');

    Route::get('/attendance/portal/vdt', 'portalTimeInvdt')->name('attendance.portal.vdt');
    Route::post('/attendance/portal/vdt', 'submitAttendancevdt')->name('admin.attendance.store.vdt');

    Route::get('/attendance/portal/student', 'portalTimeInStudent')->name('attendance.portal.student');
    Route::post('/attendance/portal/student', 'submitAttendanceStudent')->name('admin.attendance.store.student');

    Route::get('/attendance/fetch-latest', 'fetchLatest')->name('admin.attendance.fetch.latest');
});


Route::middleware(['auth:web'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

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
        Route::controller(WorkingHourController::class)->group(function () {          
            Route::get('/department-working-hour', 'index')->name('workinghour.index');
            Route::post('/department-working-hour', 'store')->name('workinghour.store');
            Route::put('/department-working-hour/{id}', 'update')->name('workinghour.update');
            Route::delete('/department-working-hour/{id}', 'destroy')->name('workinghour.delete');
        });



        // course routes
        Route::controller(CourseController::class)->group(function () {         
            Route::get('/courses', 'index')->name('course.index');
            Route::post('/courses', 'store')->name('course.store');
            Route::get('/courses/{course_id}/edit', 'edit')->name('course.edit');
            Route::put('courses/{id}', 'update')->name('course.update');
            Route::delete('/courses/{id}', 'destroy')->name('course.destroy');
            Route::delete('courses', 'deleteAll')->name('course.deleteAll');
        });

        // Employee routes
        Route::controller(EmployeeController::class)->group(function () {   
            Route::get('/employees', 'index')->name('employee.index');
            Route::post('/employees', 'store')->name('employee.store');
            Route::put('/employees/{id}', 'update')->name('employee.update');
            Route::delete('/employees/{id}', 'destroy')->name('employee.destroy');
            Route::delete('employee', 'deleteAll')->name('employee.deleteAll');
        });

        // CSV Route
        // routes/web.php
        Route::controller(CSVImportController::class)->group(function () {   
            Route::post('/import-csv', 'import')->name('csv.import');
            Route::post('/import-csv/department', 'importDepartment')->name('csv.import.department');
        });


        // Employee Attendance routes
        Route::controller(EmployeeAttendanceController::class)->group(function () {  
            Route::get('/employees/attendance', 'employee')->name('attendance.employee_attendance');
            Route::get('/employees/attendance/payroll', 'employeelist')->name('attendance.employee_attendance.payroll');
            Route::get('/employees/attendance/payroll/all', 'employeelistall')->name('attendance.employee_attendance.payroll.all');

            Route::post('/employees/attendance/add-time-in', 'employeeAddTimeIn')->name('attendance.employee_attendance.addIn');
            Route::post('/employees/attendance/add-time-out', 'employeeAddTimeOut')->name('attendance.employee_attendance.addOut');
            Route::delete('employees/attendance/delete-time-in/{id}', 'deleteTimeIn')->name('attendance.employee_attendance.deleteTimeIn');
            Route::delete('employees/attendance/delete-time-out/{id}', 'deleteTimeOut')->name('attendance.employee_attendance.deleteTimeOut');
            Route::get('/employees/attendance/search', 'employeeSearch')->name('attendance.employee_attendance.search');
            Route::get('/generate-pdf', 'generatePDF')->name('generate.pdf');
            Route::get('/attendance/time-in/portal', 'portalTimeIn')->name('attendance.time-in.portal');
            Route::get('/attendance/time-out/portal', 'portalTimeOut')->name('attendance.time-out.portal');
            Route::post('/attendance/time-in/portal', 'submitPortalTimeIn')->name('attendance.time-in.store');
            Route::post('/attendance/time-out/portal', 'submitPortalTimeOut')->name('attendance.time-out.store');   
            Route::post('/attendance/modify', 'modifyAttendance')->name('attendance.modify');
            Route::post('/attendance/modify/halfday', 'modifyAttendanceHalfDay')->name('attendance.modify.halfDay');   

            Route::put('/edit-TimeIn/{id}', 'attendanceTimeInUpdate')->name('attendanceIn.edit');
            Route::put('/edit-TimeOut/{id}', 'attendanceTimeOutUpdate')->name('attendanceOut.edit');

            Route::get('/insert-photos', 'employee_insertPhoto')->name('insert.photos');

            //Graceperiod
            Route::post('/setGracePeriod', 'storePeriod')->name('attendance.gracePeriod');
            Route::put('/setGracePeriod/update/{id}', 'updatePeriod')->name('attendance.gracePeriod.update');
            Route::delete('/deletePeriod/{id}', 'deletePeriod')->name('attendance.gracePeriod.delete');
            //settings nav route
            Route::get('/setGracePeriodSet', 'storePeriodView')->name('attendance.gracePeriodSet');
            Route::get('/setAttendanceHoliday', 'holiday')->name('attendance.holiday');
            Route::post('/setHoliday', 'setHoliday')->name('attendance.setHoliday');
            //Student Attendance routes
            Route::get('/students/attendance', 'student')->name('attendance.student_attendance');
        });

        // Student routes
        Route::controller(StudentController::class)->group(function () {  
            Route::get('/students', 'index')->name('student.index');
            Route::post('/students', 'store')->name('student.store');
            Route::put('/students/{id}', 'update')->name('student.update');
            Route::delete('/students/{id}', 'destroy')->name('student.destroy');
            Route::delete('students', 'deleteAll')->name('student.deleteAll');
        });

        
        //staff routes
        Route::resource('staff', AdminStaffController::class)->names([
            'index' => 'staff.index',
            'create' => 'staff.create',
            'store' => 'staff.store',
            'edit' => 'staff.edit',
            'update' => 'staff.update'
        ]);
        Route::delete('staff', [AdminStaffController::class, 'deleteAll'])->name('staff.deleteAll');

    });




    // ADMIN STAFF ROUTES ----------------------------------------------------------------------------
    Route::middleware(['role:admin_staff'])->prefix('staff')->name('admin_staff.')->group(function () {
         
            Route::get('/dashboard', [DashboardController::class, 'indexStaff'])->name('dashboard');


            Route::controller(EmployeeAttendanceController::class)->group(function () {
                Route::get('/employees/attendance/search', 'employeeSearch')->name('attendance.employee_attendance.search');
                Route::get('/employees/attendance', 'employee')->name('attendance.employee_attendance');
                Route::get('/employees/attendance/payroll', 'employeelist')->name('attendance.employee_attendance.payroll');
                Route::post('/employees/attendance/add-time-in', 'employeeAddTimeIn')->name('attendance.employee_attendance.addIn');
                Route::post('/employees/attendance/add-time-out', 'employeeAddTimeOut')->name('attendance.employee_attendance.addOut');
                Route::put('/edit-TimeIn/{id}', 'attendanceTimeInUpdate')->name('attendanceIn.edit');
                Route::put('/edit-TimeOut/{id}', 'attendanceTimeOutUpdate')->name('attendanceOut.edit');

                Route::delete('employees/attendance/delete-time-in/{id}', 'deleteTimeIn')->name('attendance.employee_attendance.deleteTimeIn');
                Route::delete('employees/attendance/delete-time-out/{id}', 'deleteTimeOut')->name('attendance.employee_attendance.deleteTimeOut');

                Route::get('/employees/attendance/payroll/all', 'employeelistall')->name('attendance.employee_attendance.payroll.all');
                
                Route::get('/setAttendanceHoliday', 'holiday')->name('attendance.holiday');
                Route::post('/setHoliday', 'setHoliday')->name('attendance.setHoliday');
                Route::delete('/deleteHoliday/{id}', 'deleteHoliday')->name('holiday.destroy');

                Route::post('/attendance/modify', 'modifyAttendance')->name('attendance.modify');
                Route::post('/attendance/modify/halfday', 'modifyAttendanceHalfDay')->name('attendance.modify.halfDay'); 

                Route::get('/setGracePeriodSet', 'storePeriodView')->name('attendance.gracePeriodSet');
                Route::post('/setGracePeriod', 'storePeriod')->name('attendance.gracePeriod');
                Route::put('/setGracePeriod/update/{id}', 'updatePeriod')->name('attendance.gracePeriod.update');
                Route::delete('/deletePeriod/{id}', 'deletePeriod')->name('attendance.gracePeriod.delete');


                Route::get('/delete-date-of-an-attendance', 'delete_attendance')->name('delete_attendance');
                Route::post('/delete-date-of-an-attendance-submit', 'validate_delete_attendance')->name('submit_delete_attendance');
            });
//

 
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
            Route::controller(WorkingHourController::class)->group(function () {
                Route::get('/department-working-hour', 'index')->name('workinghour.index');
                Route::post('/department-working-hour', 'store')->name('workinghour.store');
                Route::put('/department-working-hour/{id}', 'update')->name('workinghour.update');
                Route::delete('/department-working-hour/{id}', 'destroy')->name('workinghour.delete');
            });
            

            //Courses routes staff
            Route::controller(CourseController::class)->group(function () {
                Route::get('/courses', 'index')->name('course.index');
                Route::post('/courses', 'store')->name('course.store');
                Route::get('/courses/{course_id}/edit', 'edit')->name('course.edit');
                Route::put('courses/{id}', 'update')->name('course.update');
                Route::delete('/courses/{id}', 'destroy')->name('course.destroy');
                Route::delete('courses', 'deleteAll')->name('course.deleteAll');
            });
            
            // Employee routes
            Route::controller(EmployeeController::class)->group(function () {
                Route::get('/employees', 'index')->name('employee.index');
                Route::post('/employees', 'store')->name('employee.store');
                Route::put('/employees/{id}', 'update')->name('employee.update');
                Route::delete('/employees/{id}', 'destroy')->name('employee.destroy');
                Route::delete('employee', 'deleteAll')->name('employee.deleteAll');
            });

            // Student routes
            Route::controller(StudentController::class)->group(function () {
                Route::get('/students', 'index')->name('student.index');
                Route::post('/students', 'store')->name('student.store');
                Route::put('/students/{id}', 'update')->name('student.update');
                Route::delete('/students/{id}', 'destroy')->name('student.destroy');
                Route::delete('students', 'deleteAll')->name('student.deleteAll');
            }); 

            Route::controller(FingerprintController::class)->group(function () {
                Route::get('/fingerprint', 'index')->name('show.fingerprint');
                Route::get('/enable-fingerprint', 'activate_fingerprint')->name('fingerprint');
                Route::put('/set-fingerprint/{id}', 'set_fingerprint')->name('enable_fingerprint');
            });


            

    });





    Route::middleware(['role:sao'])->prefix('sao')->name('sao.')->group(function () {
        
        Route::get('/dashboard', [SAOController::class, 'index'])->name('dashboard');
    });



    // For HR ------------------------------------------------------------------------------------


});

Route::middleware(['auth:employee', 'role:employee'])->group(function () {
    Route::get('/sdcs', [EmployeeController::class, 'employee_dashboard'])->name('employee.dashboard');
    Route::post('/employee/logout', [EmployeeController::class, 'logoutEmployee'])->name('logout.employee');
});



    Route::controller(EmployeeController::class)->group(function () {
                    
                    Route::get('/employee/login', 'employee_login')->name('employee.login.portal');
                    Route::post('/employee/login/store', 'login_employee')->name('employee_login');
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

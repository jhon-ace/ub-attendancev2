<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class UserRoutePageName extends Component
{
    /**
     * Create a new component instance.
     */

    public $title;

    public function __construct(string $routeName)
    {
        $this->setTitle($routeName);
    }

     protected function setTitle(string $routeName)
    {
        if (!Auth::check()) {
            $this->title = __('University of Bohol Attendance System');
            return;
        }

        if(Auth::guard('web')->check())
        {

            if (Auth::user()->hasRole('admin')) {
                
                $titles = [
                    //admin route pages name
                    'admin.dashboard' => __('Admin Dashboard'),
                    //route page name for managing school
                    'admin.school.index' => __('Admin - Manage School'),
                    'admin.department.index' => __('Admin - Manage Department'),
                    'admin.workinghour.index' => __('Admin - Manage Department Working Hour'),
                    'admin.staff.index' => __('Admin - Manage Admin Staff'),
                    'admin.employee.index' => __('Admin - Manage Employee'),
                    'admin.student.index' => __('Admin - Manage Student'),
                    'admin.course.index' => __('Admin - Manage Courses'),
                    'admin.attendance.employee_attendance' => __('Admin - Employee Attendance'),
                    'admin.attendance.employeeSearch' => __('Admin - Employee Attendance Search'),
                    'admin.attendance.student_attendance' => __('Admin - Student Attendance'),
                    'admin.attendance.employee_attendance.payroll' =>  __('Admin - Manage Attendance Reports by Department'),
                    'admin.attendance.employee_attendance.payroll.all' =>  __('Admin - Manage All Employees Attendance Reports'),
                    'admin.attendance.employee_attendance.portal' => __('Employee Attendance Portal'),
                    'admin.attendance.gracePeriodSet' => __('Admin - Attendance Grace Period'),
                    'admin.attendance.holiday' => __('Admin - Add Holiday'),

                    
                ];

                $this->title = $titles[$routeName] ?? __('University of Bohol Attendance System');

            }
            else if (Auth::user()->hasRole('admin_staff')) {

                $titles = [

                    'staff.dashboard' => __('Staff | Dashboard'),
                    'admin_staff.attendance.employee_attendance.search' => __('Admin Staff | Search Employee Attendance'),
                    'admin_staff.attendance.employee_attendance' => __('Admin Staff | Employee Attendance'),
                    'admin_staff.attendance.employee_attendance.payroll' => __('Admin Staff | All Employee Attendance'),
                    'admin_staff.attendance.holiday' => __('Staff | Manage Holiday'),
                    'admin_staff.attendance.gracePeriodSet' => __('Staff | Manage Grace Period'),
                    'admin_staff.delete_attendance' => __('Staff | Manage Attendance Date Deletion'),
                    'admin_staff.show.fingerprint' => __('Staff | Enroll Fingerprint to Employees'),
                    'admin_staff.fingerprint' => __('Staff | Activate Fingerprint'),
                    'staff.school.index' => __('Staff | Manage School Year'),
                    'staff.department.index' => __('Staff | Manage Department'),
                    'staff.workinghour.index' => __('Staff | Manage Dept Working Hour'),
                    'staff.course.index' => __('Staff | Manage Courses'),
                    'staff.employee.index' => __('Staff | Manage Employee'),
                    'staff.student.index' => __('Staff | Manage Student'),
                    

                ];

                $this->title = $titles[$routeName] ?? __('University of Bohol Attendance System');

            }
            else if (Auth::user()->hasRole('sao')) {

                $titles = [

                    'sao.dashboard' => __('SAO | Dashboard'),
                ];

                $this->title = $titles[$routeName] ?? __('University of Bohol Attendance System');
            }
            else if (Auth::user()->hasRole('employee')) {

                $titles = [

                    //employee route pages name
                    'hr.dashboard' => __('Human Resource | Dashboard'),
                ];

                $this->title = $titles[$routeName] ?? __('University of Bohol Attendance System');

            }
            else {
                $this->title = __('University of Bohol Attendance System');
            }
        } else {
            $titles = [

                //employee route pages name
                'employee.dashboard' => __('Employee DTR | Dashboard'),
            ];

            $this->title = $titles[$routeName] ?? __('University of Bohol Attendance System');
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-route-page-name');
    }
}

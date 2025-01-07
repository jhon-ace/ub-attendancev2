<?php

namespace App\Livewire;

use App\Models\Admin\EmployeeAttendanceTimeIn;
use App\Models\Admin\EmployeeAttendanceTimeOut;
use App\Models\Admin\School;
use App\Models\Admin\Department;
use App\Models\Admin\Employee;
use App\Models\Admin\DepartmentWorkingHour;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DateTimeZone;

class SearchDashboardAdmin extends Component
{
     use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment4 = null;
    public $selectedEmployee = null;
    public $departmentsToShow;
    public $schoolToShow;
    public $departmentToShow;
    public $attendancesToShow;
    public $selectedEmployeeToShow;
    public $startDate = null;
    public $endDate = null;
    public $selectedStartDate = null;
    public $selectedEndDate = null;
    public $selectedAttendanceByDate;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateDateRange()
    {
        $this->resetPage();
    }
    public function clearSearch()
    {
        $this->search = '';
    }

     public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }


    public function render()
    {

         $queryTimeIn = EmployeeAttendanceTimeIn::query()
            ->with(['employee.school', 'employee.department']);

        $queryTimeOut = EmployeeAttendanceTimeOut::query()
            ->with(['employee.school', 'employee.department']);
            

        // Apply search filters
 
        // $queryTimeOut = $this->applySearchFilters($queryTimeOut);


        $queryTimeIn = $this->applySearchFiltersTimeIn($queryTimeIn);
        $queryTimeOut = $this->applySearchFiltersTimeOut($queryTimeOut);

        // Get the results
       $attendanceTimeIn = $queryTimeIn->where(function ($query) {
                            $query->where('status', '!=', 'Holiday')
                                ->where('status', '!=', 'On Leave')
                                ->where('status', '!=', 'Official Travel');
                        })
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->get();

        $attendanceTimeOut = $queryTimeOut->where(function ($query) {
                            $query->where('status', '!=', 'Holiday')
                                ->where('status', '!=', 'On Leave')
                                ->where('status', '!=', 'Official Travel');
                        })
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->get();      



        return view('livewire.search-dashboard-admin', [
                    'attendanceTimeIn' =>  $attendanceTimeIn,
                    'attendanceTimeOut' =>  $attendanceTimeOut,
                ]);
    }

    protected function applySearchFiltersTimeIn($queryTimeIn)
    {
        $currentDate = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
        
        return $queryTimeIn->whereHas('employee', function (Builder $query) {
            $query->where('employee_id', 'like', '%' . $this->search . '%')
                  ->orWhere('employee_lastname', 'like', '%' . $this->search . '%')
                  ->orWhere('employee_firstname', 'like', '%' . $this->search . '%')
                  ->orWhere('employee_middlename', 'like', '%' . $this->search . '%')
                  ->orWhereHas('department', function (Builder $query) {
                      $query->where('department_name', 'like', '%' . $this->search . '%')  // Adjust 'department_name' to the actual field name
                             ->orWhere('department_abbreviation', 'like', '%' . $this->search . '%');
                    });
        })
        ->whereDate('check_in_time', $currentDate); // Adjust 'created_at' to your date field
    }

    protected function applySearchFiltersTimeOut($queryTimeOut)
    {
         $currentDate = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
        
        return $queryTimeOut->whereHas('employee', function (Builder $query) { 
            $query->where('employee_id', 'like', '%' . $this->search . '%')
                ->orWhere('employee_lastname', 'like', '%' . $this->search . '%')
                ->orWhere('employee_firstname', 'like', '%' . $this->search . '%')
                ->orWhere('employee_middlename', 'like', '%' . $this->search . '%')
                ->orWhereHas('department', function (Builder $query) {
                      $query->where('department_name', 'like', '%' . $this->search . '%')  // Adjust 'department_name' to the actual field name
                             ->orWhere('department_abbreviation', 'like', '%' . $this->search . '%');
                    }); 
        })
        ->whereDate('check_out_time', $currentDate);
    }
}

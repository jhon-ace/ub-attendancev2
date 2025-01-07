<?php

namespace App\Livewire\Admin;

use \App\Models\Admin\School; 
use \App\Models\Admin\Department; 
use \App\Models\Admin\DepartmentWorkingHour; 
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ShowDepartmentWorkingHours extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'department_id';
    public $sortDirection = 'asc';
    public $selectedSchool = null;
    public $selectedDepartment = null;
    public $departmentsToShow;
    public $schoolToShow;
    public $scheduleToShow;
    public $showSelectedDepartment;
    // public $todayDayOfWeek;
    // public $todayDayOfWeekNumber;

    protected $listeners = ['updateDepartments', 'showDepartmentSchedule'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {   
        // $this->todayDayOfWeekNumber = Carbon::now()->dayOfWeek;
        // $this->todayDayOfWeek = Carbon::now()->dayOfWeek;

         if (Auth::check() && Auth::user()->school) {
            $this->selectedSchool = Auth::user()->school->id;
        }

        $this->selectedDepartment = session('selectedDepartment', null);
        $this->showSelectedDepartment = session('showSelectedDepartment', null);

        $this->departmentsToShow = collect([]); // Initialize as an empty collection
        $this->schoolToShow = collect([]);
        $this->scheduleToShow = collect([]); // Initialize as an empty collection

        //$this->selectedSchool = $this->selectedSchool ?? json_decode(request()->cookie('selectedSchool'), true);
    }

    public function updatingSelectedSchool()
    {
        session(['selectedSchool' => $this->selectedSchool]);
        // cookie()->queue('selectedSchool', json_encode($this->selectedSchool), 60*24*30); // Store for 30 days
        $this->resetPage();
        
    }

    public function updatingSelectedDepartment()
    {
        session(['selectedDepartment' => $this->selectedDepartment]);
        // cookie()->queue('selectedDepartment', json_encode($this->selectedDepartment), 60*24*30); // Store for 30 days
        $this->resetPage();
        
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

    protected $daysOfWeek = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function render()
    {
        $query = Department::with('school');

        // Apply search filters
        $query = $this->applySearchFilters($query);

        // Apply selected school filter
        if ($this->selectedSchool) {
            $query->where('school_id', $this->selectedSchool);
            $this->schoolToShow = School::findOrFail($this->selectedSchool);
        } else {
            $this->schoolToShow = null; // Reset schoolToShow if no school is selected
        }


        $schedule = DepartmentWorkingHour::with('department');
        if ($this->selectedDepartment) {

            $schedule->where('department_id', $this->selectedDepartment);
            $this->scheduleToShow = DepartmentWorkingHour::find($this->selectedDepartment);

        } else {
            $this->scheduleToShow = null;
        }

        $workingHour = $schedule->orderBy($this->sortField, $this->sortDirection)
                             ->paginate(200);
        $departments = $query->where('dept_identifier', '!=', 'student')
                    //  ->orderBy($this->sortField, $this->sortDirection)
                     ->orderBy('department_abbreviation', 'asc')
                     ->paginate(200);

        // $workingHour = $showSchedule->orderBy($this->sortField, $this->sortDirection)
        //                      ->paginate(10);

        $schools = School::all();

        


         $departmentCounts = Department::select('school_id', \DB::raw('count(*) as department_count'))
                                  ->groupBy('school_id')
                                  ->get()
                                  ->keyBy('school_id');
        
        return view('livewire.admin.show-department-working-hours', [
            'daysOfWeek' => $this->daysOfWeek,
            'workingHour' => $workingHour,
            'showSelectedDepartment' => $this->showSelectedDepartment,
            'departments' => $departments,
            'schools' => $schools,
            'departmentCounts' => $departmentCounts,
            'schoolToShow' => $this->selectedSchool,
        ]);
    }

    public function updateDepartments()
    {
        // Update departmentsToShow based on selected school
        if ($this->selectedSchool) {
            $this->departmentsToShow = Department::where('school_id', $this->selectedSchool)
                ->get(); // Ensure this returns a collection
        } else {
            $this->departmentsToShow = collect(); // Reset to empty collection if no school is selected
        }

        
    }
    

    public function showDepartmentSchedule()
    {
        if ($this->selectedDepartment) {
            $this->showSelectedDepartment = Department::find($this->selectedDepartment);
        } else {
            $this->showSelectedDepartment = null;
        }
        

        
    }

    protected function applySearchFilters($query)
{
    return $query->where(function (Builder $query) {
        $query->where('id', 'like', '%' . $this->search . '%')
            ->orwhere('department_id', 'like', '%' . $this->search . '%')
            ->orWhere('department_abbreviation', 'like', '%' . $this->search . '%')
            ->orWhere('department_name', 'like', '%' . $this->search . '%')
            ->orWhere('dept_identifier', 'like', '%' . $this->search . '%')
            ->orWhereHas('school', function (Builder $query) {
                $query->where('abbreviation', 'like', '%' . $this->search . '%')
                    ->orWhere('school_name', 'like', '%' . $this->search . '%');
            });
    });
}

    

}

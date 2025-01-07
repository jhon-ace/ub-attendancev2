<?php

namespace App\Livewire\Admin;

use \App\Models\Admin\Staff; 
use \App\Models\Admin\School; 
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;

class ShowStaffTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'staff_id';
    public $sortDirection = 'asc';

    public function updatingSearch()
    {
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

    public function render()
    {

        $staffs = Staff::with('school')
            ->where(function (Builder $query) {
                $query->where('staff_id', 'like', '%' . $this->search . '%')
                      ->orWhere('staff_firstname', 'like', '%' . $this->search . '%')
                      ->orWhere('staff_middlename', 'like', '%' . $this->search . '%')
                      ->orWhere('staff_lastname', 'like', '%' . $this->search . '%')
                      ->orWhere('staff_rfid', 'like', '%' . $this->search . '%')
                      ->orWhere('access_type', 'like', '%' . $this->search . '%')
                      ->orWhereHas('school', function (Builder $query) {
                          $query->where('abbreviation', 'like', '%' . $this->search . '%')
                          ->orWhere('school_name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

            $schools = School::all();

        return view('livewire.admin.show-staff-table', [
            'staffs' => $staffs,
            'schools' => $schools,
        ]);
    }

}

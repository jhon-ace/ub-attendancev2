<?php

namespace App\Livewire\Admin;

use \App\Models\Admin\School; 
use Livewire\Component;
use Livewire\WithPagination;

class ShowSchoolTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
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
        $schools = School::where('abbreviation', 'like', '%' . $this->search . '%')
            ->orWhere('school_name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.show-school-table', [
            'schools' => $schools,
        ]);
    }
}

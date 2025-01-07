<?php

namespace App\Livewire;

use Livewire\Component;

class CellTableEdit extends Component
{
    public $editable = false;
    public $value;
    public $originalValue;

    public function render()
    {
        return view('livewire.cell-table-edit');
    }
}

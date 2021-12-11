<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;

class ManagerEmployee extends Component
{
    use WithPagination;
    public $search = '';
    public $paginate= 10;

    public function render()
    {
        if ($this->search !=null) {
            $employees =  Employee::with(['user'])->search(trim($this->search))->simplePaginate($this->paginate);
            return view('livewire.manager-employee', ['employees'=> $employees]);
        }
        // dd($employees);
        $employees = Employee::with(['user'])->orderBy('id', 'ASC')->simplePaginate($this->paginate);

        return view('livewire.manager-employee', ['employees'=> $employees]);
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;

class ManagerAccount extends Component
{
    use WithPagination;
    public $search = '';
    public $userId = null;
    public $email = null;
    public $selection=[];
    public $roles=[];
    public $checked =[];
    public $paginate= 10;

    public function __construct()
    {
        $this->roles= Role::All();
    }

    public function render()
    {
        if ($this->search !=null) {
            $users =  User::with(['roles','employee'])->withTrashed()->search(trim($this->search))->simplePaginate($this->paginate);
            return view('livewire.manager-account',['users' => $users]);
        }

        $users = User::with(['roles','employee'])->withTrashed()->orderBy('id', 'ASC')->simplePaginate($this->paginate);
        // dd($users);
        return view('livewire.manager-account',['users' => $users]);
    }

    public function confirmUserRemoved($user_id, $email)
    {
        $this->userId = $user_id;
        $this->email=$email;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function blockUser()
    {
        User::findOrFail($this->userId)->delete();
        $this->dispatchBrowserEvent('hide-delete-modal',['message'=>'Đã khoá tài khoản: '.$this->email]);
    }

    public function isChecked($user_id)
    {
        return in_array($user_id, $this->checked);
    }

    public function emptyChecked()
    {
        $this->checked = [];
    }

    public function blockChecked()
    {
        $result = User::WhereKey($this->checked)->delete();

        if ( $result == true ) {
            $this->dispatchBrowserEvent('noti',['message'=> 'Đã khóa các tài khoản!']);
        }else{
            $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có lỗi xảy ra!']);
        }
        $this->checked = [];
    }

    public function deleteChecked()
    {
        $result = User::whereKey($this->checked)->forceDelete();
        if ( $result == true ) {
            $this->dispatchBrowserEvent('noti',['message'=> 'Đã xoá tài khoản']);
        }else{
            $this->dispatchBrowserEvent('noti',['message'=> 'Error']);
        }
        $this->checked = [];
    }
}

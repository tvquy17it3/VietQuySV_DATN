<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;

class BlockUsers extends Component
{
    use WithPagination;
    public $search = '';
    public $userId = null;
    public $email = null;
    public $selection=[];
    public $roles=[];
    public $checked =[];
    public $paginate= 10;
    public $userIdRemote = null;

    public function __construct()
    {
        $this->roles= Role::All();
    }

    public function render()
    {
        if ($this->search !=null) {
            $users =  User::with(['roles','employee'])->onlyTrashed()->search(trim($this->search))->simplePaginate($this->paginate);
            return view('livewire.block-users',['users' => $users]);
        }

        $users = User::with(['roles','employee'])->onlyTrashed()->whereHas('roles', function($q){
            $q->whereNotIn('slug', ['admin']);
        })->orderBy('id', 'ASC')->simplePaginate($this->paginate);

        return view('livewire.block-users',['users' => $users]);
    }

    public function confirmUserRemoved($user_id, $email)
    {
        $this->userIdRemote = $user_id;
        $this->email=$email;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function confirmUserRestore($user_id, $email)
    {
        $this->userIdRemote = $user_id;
        $this->email=$email;
        $this->dispatchBrowserEvent('show-restore-modal');
    }

    public function restore()
    {
        User::withTrashed()->where('id', $this->userIdRemote)->restore();
        $this->dispatchBrowserEvent('hide-restore-modal',['message'=>'Đã khôi phục tài khoản: '.$this->email]);
    }

    public function deleteUser()
    {
        User::withTrashed()->where('id', $this->userIdRemote)->forceDelete();
        $this->dispatchBrowserEvent('hide-delete-modal',['message'=>'Đã xoá tài khoản: '.$this->email]);
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

    public function restoreChecked()
    {
        $result = User::withTrashed()->whereKey($this->checked)->restore();

        if ( $result == true ) {
            $this->dispatchBrowserEvent('noti',['message'=> 'Đã khôi phục tài khoản!']);
        }else{
            $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có lỗi xảy ra!']);
        }
        $this->checked = [];
    }
}

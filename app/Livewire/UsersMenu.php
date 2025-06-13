<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;


class UsersMenu extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $isHome = true;
    public $isCreate = false;
    public $isEdit = false;
    public $isShow = false;
    public $search = '';
    public $id, $name, $email, $roles_id, $password, $phone_number;

    public function render()
    {
        return view('livewire.users-menu', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')->paginate(5),
            'allRoles' => Role::all()
        ]);
    }

    public function newuser()
    {
        $this->isHome = false;
        $this->isCreate = true;
        $this->isEdit = false;
        $this->isShow = false;
    }

    public function edit($id)
    {
        $this->isHome = false;
        $this->isCreate = false;
        $this->isEdit = true;
        $this->isShow = false;
        $user = User::find($id);
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles_id = $user->roles_id;
        $this->password = $user->password;
        $this->phone_number = $user->phone_number;
    }

    public function show($id)
    {
        $this->isHome = false;
        $this->isCreate = false;
        $this->isEdit = false;
        $this->isShow = true;
        $user = User::find($id);
        $this->id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles_id = $user->roles_id;
        $this->phone_number = $user->phone_number;
    }

    public function back()
    {
        $this->isHome = true;
        $this->isCreate = false;
        $this->isEdit = false;
        $this->isShow = false;

        $this->reset('id', 'name', 'email', 'roles_id', 'password', 'phone_number');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'roles_id' => 'required|exists:roles,id',
            'password' => ['required', Password::min(1)],
            'phone_number' => 'required|string|max:15',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'roles_id' => $this->roles_id,
            'password' => Hash::make($this->password),
            'phone_number' => $this->phone_number,
        ]);
        session()->flash('success', 'User created successfully.');
        $this->reset('name', 'email', 'roles_id', 'password', 'phone_number');
        $this->back();
    }

    // Validasi input
    public function setUpdate($id)
    {
        // Validasi input
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'roles_id' => 'required|exists:roles,id',
            'password' => ['nullable', Password::min(8)],
            'phone_number' => 'required|string|max:15',
        ]);

        $user = User::find($id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'roles_id' => $this->roles_id,
            'password' => $this->password,
            'phone_number' => $this->phone_number


        ]);

        session()->flash('success', 'User updated successfully.');
        $this->reset('name', 'email', 'roles_id', 'password', 'phone_number');
        $this->back();
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            session()->flash('success', 'User deleted successfully.');
        } else {
            return redirect()->back();
        }
    }
}

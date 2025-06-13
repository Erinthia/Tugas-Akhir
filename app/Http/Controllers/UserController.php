<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::findOrFail($request->role_id);

        $user->roles()->sync([$role->id]); // atau ->attach jika ingin tambah saja
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }
}

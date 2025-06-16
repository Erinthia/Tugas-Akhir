<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $permissions = Permission::with(['roles'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('admin.permissions.index', compact('permissions', 'search'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.permissions.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions',
            'description' => 'required|string',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Simpan permission
        $permission = Permission::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Attach roles jika ada
        if (!empty($validated['roles'])) {
            $permission->roles()->attach($validated['roles']);
        }

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        return view('admin.permissions.show', compact('permission'));
    }

    public function edit($id)
    {
        $permission = Permission::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('admin.permissions.edit', compact('permission', 'roles'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $id,
            'description' => 'required|string',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update($request->only('name', 'description'));

        // Sinkronisasi role
        $permission->roles()->sync($request->roles);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }


    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}

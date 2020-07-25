<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view roles', ['only' => ['roles']]);
        $this->middleware('permission:create role', ['only' => ['create','store']]);
        $this->middleware('permission:edit role', ['only' => ['edit','update']]);
        $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function roles()
    {
        // Getting All Roles and converting In Array
        $roles = Role::orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        return view('admin.roles.all', compact('roles'));
    }


    /**
     * @return Factory|View
     */
    public function create()
    {
        // Getting All Permissions
        $permissions = Permission::orderBy('name')
            ->get(['name', 'id'])
            ->toArray();

        // Returning Create View
        return view('admin.roles.create', compact('permissions'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|without_spaces|unique:roles',
        ]);
        if ($validator->fails()) {
            return redirect('admin/role/create')
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Role
        $role = Role::create([
            'name' => strtolower($request->name),
        ]);

        // Assigning Permissions To The Role
        foreach ($request->permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        // Printing Alert Message
        Alert::toast('Role Created Successfully', 'success');

        return redirect('admin/role/create');
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Initializing Empty Array
        $role_permissions_id_list = [];

        // Getting Role By The ID
        $role = Role::findOrFail($id);

        // Getting All Permissions
        $permissions = Permission::orderBy('name')
            ->get(['name', 'id'])
            ->toArray();

        // Converting To Array
        $role = $role->with(['permissions'])
            ->where('id', $role->id)
            ->get()
            ->first()
            ->toArray();

        // Creating Role Permissions Id List
        foreach ($role['permissions'] as $permision) {
            $role_permissions_id_list[] = $permision['id'];
        }

        return view(
            'admin.roles.edit',
            compact
            (
                'role',
                'permissions',
                'role_permissions_id_list'
            )
        );
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting Role By The ID
        $role = Role::findOrFail($id);
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|without_spaces',
        ]);
        if ($validator->fails()) {
            return redirect('admin/role/edit/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        if ($role->name == "super-admin") {
            // Printing Alert Message
            Alert::toast('Super Admin Role Can Not Be Edited!', 'warning');

            return redirect('admin/role/edit/' . $role->id);
        }

        // Updating Role
        $role->update([
            'name' => strtolower($request->name),
        ]);

        // Updating/Creating Role's Permissions
        if ($request->has('permissions')) {
            $role->revokePermissionTo($request->permissions);
            $role->syncPermissions($request->permissions);
        } else {
            $role->revokePermissionTo($request->permissions);
        }

        // Printing Alert Message
        Alert::toast('Role Updated Successfully', 'success');

        return redirect('admin/role/edit/' . $role->id);
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function destroy($id)
    {
        // Getting Role By The ID
        $role = Role::findOrFail($id);

        // Deleting The Role
        $role->delete();

        return redirect('admin/roles')->with('success', 'Role Deleted.');
    }
}

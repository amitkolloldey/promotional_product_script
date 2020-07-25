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

class PermissionController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view permissions', ['only' => ['permissions']]);
        $this->middleware('permission:create permission', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit permission', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function permissions()
    {
        // Getting All Permissions and converting In Array
        $permissions = Permission::orderBy('created_at','desc')
            ->get()
            ->toArray();

        return view('admin.permissions.all', compact('permissions'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        // Returning Create View
        return view('admin.permissions.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions',
        ]);
        if ($validator->fails()) {
            return redirect('admin/permission/create')
                ->withErrors($validator)
                ->withInput();
        }

        // Creating Permission
        Permission::create([
            'name' => strtolower($request->name),
        ]);

        // Printing Alert Message
        Alert::toast('Permission Created Successfully', 'success');

        return redirect('admin/permission/create');
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting Permission By The ID
        $permission = permission::findOrFail($id);

        // Converting To Array
        $permission = $permission->toArray();

        return view('admin.permissions.edit', compact('permission'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting permission By The ID
        $permission = Permission::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('admin/permission/edit/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Updating Permission
        $permission->update([
            'name' => strtolower($request->name),
        ]);

        // Printing Alert Message
        Alert::toast('Permission Updated Successfully', 'success');

        return redirect('admin/permission/edit/' . $permission->id);
    }

    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function destroy($id)
    {
        // Getting Permission By The ID
        $permission = Permission::findOrFail($id);

        // Deleting The Permission
        $permission->delete();

        return redirect('admin/permissions')
            ->with('success', 'Permission Deleted.');
    }
}

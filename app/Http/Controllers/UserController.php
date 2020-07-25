<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view users', ['only' => ['users']]);
        $this->middleware('permission:create user', ['only' => ['create','store']]);
        $this->middleware('permission:edit user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }

    /**
     * @return Factory|View
     */
    public function users()
    {
        // Getting All Users and converting In Array
        $users = Cache::get('users_all', function () {
            Cache::forever('users_all', $users = User::with(['roles'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray());
            return $users;
        });

        return view('admin.users.all', compact('users'));
    }


    /**
     * @return Factory|View
     */
    public function create()
    {
        // Getting All Roles
        $roles = Role::get(['name', 'id'])->toArray();

        // Returning User Create View
        return view('admin.users.create', compact('roles'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:20',
            'status' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('admin/user/create')->withErrors($validator)->withInput();
        }

        // Creating User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'company' => $request->company,
            'phone_no' => $request->phone_no,
        ]);

        // Checking If Request Has Role
        if ($request->role != 'none') {
            $user->assignRole($request->role);
        }

        // Printing Alert Message
        Alert::toast('User Created Successfully', 'success');

        return redirect('admin/user/create');
    }


    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        // Getting The User
        $user = User::findOrFail($id);

        // Getting User With Roles and Converting Array
        $user = $user->with(['roles'])->where('id', $id)->get()->first()->toArray();

        // Getting All The Roles and Converting To Array
        $roles = Role::get(['name', 'id'])->toArray();

        return view('admin.users.edit', compact('user', 'roles'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting The User
        $user = User::findOrFail($id);

        if(in_array('super-admin', $user->roles->pluck('name')->toArray())){

            // Printing Alert Message
            Alert::toast('Super Admin Can Not Be Edited!', 'warning');

            return redirect('admin/user/edit/' . $user->id);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required',
            'status' => 'required',
            'role' => 'required',
            'company' => $request->company,
            'phone_no' => $request->phone_no,
        ]);
        if ($validator->fails()) {
            return redirect('admin/user/edit/' . $user->id)->withErrors($validator)->withInput();
        }

        // Checking If Request Has Password
        if ($request->password) {
            // Updating The Password Only
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Updating The User
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        // Checking If Request Has Role
        if (($request->has('role')) && ($request->role != "0")) {
            if (count($user->getRoleNames())){
                // Removing The Existing Role/s
                foreach ($user->getRoleNames() as $role) {
                    $user->removeRole($role);
                }

                // Else Assigning The Role
                $user->assignRole($request->role);
            }
        }

        // Printing Alert Message
        Alert::toast('User Updated Successfully', 'success');

        return redirect('admin/user/edit/' . $user->id);
    }


    /**
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function destroy($id)
    {
        // Getting The User
        $user = User::findOrFail($id);


        if(in_array('super-admin', $user->roles->pluck('name')->toArray())){

            // Printing Alert Message
            Alert::toast('Super Admin Can Not Be Deleted!', 'warning');

            return null;
        }

        // Deleting The Roles
        foreach ($user->getRoleNames() as $role) {
            $user->removeRole($role);
        }

        // Deleting The User
        $user->delete();

        return redirect('admin/users');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function frontUserUpdate(Request $request, $id){

        if (Auth::user()->id == $id){

            // Updating The User
            Auth::user()->update([
                'name' => $request->name,
                'phone_no' => $request->phone_no,
                'company' => $request->company,
            ]);

            // Printing Alert Message
            Alert::toast('Account Updated Successfully!', 'success');

            return redirect('/page/my-account');
        }

        return redirect()->back();
    }

}

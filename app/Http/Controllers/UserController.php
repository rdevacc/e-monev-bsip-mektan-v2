<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SubKelompok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * * User Index Controller *
     */
    public function index()
    {
        $dataAdminUsers = User::orderBy('role_id')->get();
        $datausers = User::where('role_id', '!=', 1)
                    ->get();

        return view('apps.users.index', [
            'dataAdminUsers' => $dataAdminUsers,
            'dataUsers' => $datausers,
        ]);
    }

    /**
     * * User Create Controller *
     */
    public function create()
    {
        $roles = Role::get(['id', 'nama']);
        $subkelompoks = SubKelompok::get(['id', 'nama']);

        return view('apps.users.create', [
            'subkelompoks' => $subkelompoks,
            'roles' => $roles,
        ]);
    }

    /**
     * * User Store Controller *
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:50',
            'email' => 'required|email',
            'password' => 'required|same:confirmed_password',
            'confirmed_password' => 'required|same:password',
            'subkelompok_id' => 'required',
            'role_id' => 'required',
        ]);

        User::create($validated);

        return redirect()->route('user-index')->with('success', 'New user has been added!');
    }

    /**
     * * User Edit Controller *
     */
    public function edit(User $user)
    {
        $roles = Role::get(['id', 'nama']);
        $subkelompoks = SubKelompok::get(['id', 'nama']);

        return view('apps.users.edit', [
            'user' => $user,
            'subkelompoks' => $subkelompoks,
            'roles' => $roles,
        ]);
    }

    /**
     * * User Edit Controller *
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'nama' => 'required|max:50',
            'email' => 'required|email',
            'subkelompok_id' => 'required',
            'role_id' => 'required',
        ];

        /**
         * * Override password field *
         */

        $request['password'] = $user->password;


        /**
         * * Validate data before update
         */
        $validated = $request->validate($rules);


        /**
         * * Update data User
         */
        User::where('id', $user->id)->update($validated);

        return redirect()->route('user-index')->with('success', 'User ' . $user->nama . ' has been updated!');
    }

    /**
     * * User Delete Controller *
     */
    public function destroy(User $user)
    {
        // Check Super admin role
        if($user->id == 1 && Auth::user()->role->id !== 1) {
            return abort(403);
        }

        // Query for delete data
        User::destroy($user->id);

        return redirect()->route('user-index')->with('success', 'User ' . $user->nama . ' has been deleted!');
    }
}

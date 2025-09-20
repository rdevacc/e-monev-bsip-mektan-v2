<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\WorkGroup;
use App\Models\WorkTeam;
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

        $dataUsers = User::whereNot('role_id', 1)
                        ->orderBy('role_id')
                        ->get();

        return view('apps.users.index', compact([
            'dataAdminUsers',
            'dataUsers',
        ]));
    }

    /**
     * * User Create Controller *
     */
    public function create()
    {
        if(auth()->id() == 1 || auth()->user()->role_id == 1) {
            $roles = Role::orderBy('name')->get();
        }else {
            $roles = Role::whereNot('id', 1)
                        ->orderBy('name')
                        ->get();
        };
        $work_groups = WorkGroup::orderBy('name')->get();
        $work_teams = WorkTeam::orderBy('name')->get();

        return view('apps.users.create', compact([
            'roles',
            'work_groups',
            'work_teams',
        ]));
    }

    /**
     * * User Store Controller *
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email',
            'password' => 'required|same:confirmed_password',
            'confirmed_password' => 'required|same:password',
            'work_team_id' => 'required',
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
        $roles = Role::get(['id', 'name']);
        $work_teams = WorkTeam::get(['id', 'name']);

        return view('apps.users.edit', compact([
            'user',
            'roles',
            'work_teams',
        ]));
    }

    /**
     * * User Edit Controller *
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email',
            'work_team_id' => 'required',
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

        return redirect()->route('user-index')->with('success', 'User ' . $user->name . ' has been updated!');
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

        // $this->authorize();

        // Query for delete data
        User::destroy($user->id);

        return redirect()->route('user-index')->with('success', 'User ' . $user->name . ' has been deleted!');
    }
}

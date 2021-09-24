<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function($request, $next){
            $this->user = Auth::user();
            return $next($request);
        }, ['auth', 'verified'])->except('show');
    }

    public function index()
    {
        if(!$this->user->can('admin.view'))
        {
            return Inertia::render('403');
        }
        $users = User::all();
        $roles = [];
        foreach($users as $user)
        {
            array_push($roles, $user->getRoleNames());
        }
        $users = DB::table('users')->select('name')->whereNull('deleted_at')->paginate(5);
        return Inertia::render('admin/Users/Index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->user->can('admin.create'))
        {
            return Inertia::render('403');
        }
        $roles = DB::table('roles')->pluck('name');
        return Inertia::render('admin/Users/Create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->user->can('admin.create'))
        {
            return Inertia::render('403');
        }
        if($request->has('role'))
        {
            if($request->role === 'Author' && Role::where('name', 'Author')->get()->count() == 1)
            {
                return back()->with('warning', 'There cannot be multiple authors in the system.');
            }
        }
        $this->validate($request, [
            'name' => 'required|unique:users|min:3',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
            'confPassword' => 'required|same:password',
            'role' => 'required|exists:roles,name'
        ], [
            'name.required' => 'The name is required',
            'name.unique' => 'The name is already in use.',
            'email.required' => 'The email is required',
            'email.unique' => 'The email address is already in use.',
            'email.email' => 'The email address is invalid.',
            'password.required' => 'The password is required',
            'confPassword.required' => 'The confirm password field is required',
            'confPassword.same' => 'The passwords doesn\'t match, please try again',
            'role.required' => 'The role is required',
            'role.exists' => 'Invalid request, please try again.',
        ]);
        
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->image = 'avatar.png';
        $user->about = 'I am a proud member of ' . config('app.name') . ' since ' . Carbon::now()->format('jS F, Y');
        $user->assignRole($request->role);
        $user->save();
        return redirect(route('user.index'))->with('success', 'User created successfully! Tell the user to log in and visit the profile to set things up!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        if(!$this->user->can('admin.edit'))
        {
            return Inertia::render('403');
        }
        $user = User::where('name', $name)->firstOrFail();
        $role = $user->getRoleNames();
        $roles = DB::table('roles')->pluck('name');
        $user = collect(User::where('name', $name)->firstOrFail()->only('name', 'email'));
        return Inertia::render('admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        if(!$this->user->can('admin.edit'))
        {
            return Inertia::render('403');
        }
        if($request->has('role'))
        {
            if($request->role === 'Author' && Role::where('name', 'Author')->get()->count() == 1)
            {
                return back()->with('warning', 'There cannot be multiple authors in the system.');
            }
        }
        $user = User::where('name', $name)->firstOrFail();
        $this->validate($request, [
            'name' => ['required', Rule::unique('users')->ignore($user->id), 'min:3'],
            'email' => ['required', Rule::unique('users')->ignore($user->id) ,'email'],
            'role' => 'required|exists:roles,name'
        ], [
            'name.required' => 'The name is required',
            'name.unique' => 'The name is already in use.',
            'email.required' => 'The email is required',
            'email.unique' => 'The email address is already in use.',
            'email.email' => 'The email address is invalid.',
            'role.required' => 'The role is required',
            'role.exists' => 'Invalid request, please try again.',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->assignRole($request->role);
        $user->save();
        return redirect(route('user.index'))->with('success', 'User updated successfully!');
    }

    public function updateUserPassword(Request $request, $name)
    {
        if(!$this->user->can('admin.edit'))
        {
            return Inertia::render('403');
        }
        $user = User::where('name', $name)->firstOrFail();

        $this->validate($request, [
            'password' => 'required|min:8',
            'confPassword' => 'required|same:password',
            'adminPassword' => 'required',
        ], [
            'password.required' => 'The password is required',
            'confPassword.required' => 'The confirm password field is required',
            'confPassword.same' => 'The passwords doesn\'t match, please try again',
            'adminPassword.required' => 'The password is required',
        ]);

        if(Hash::check($request->adminPassword, Auth::user()->password))
        {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect(route('user.index'))->with('success', 'User updated successfully!');
        }
        else
        {
            return back()->with('error', 'Your password is incorrect, please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        if(!$this->user->can('admin.delete'))
        {
            return Inertia::render('403');
        }
        $user = User::where('name', $name)->firstOrFail();
        $userName = $user->name;
        $user->delete();
        return redirect(route('user.index'))->with('warning', 'The user with name ' . $userName . ' has been trashed.');
    }

    public function trashedUsers()
    {
        if(!$this->user->can('admin.delete'))
        {
            return Inertia::render('403');
        }
        $users = User::onlyTrashed()->paginate(5);
        return Inertia::render('admin/Users/Trashed',[
            'users' => $users
        ]);
    }

    public function restoreUser($name)
    {
        if(!$this->user->can('admin.delete'))
        {
            return Inertia::render('403');
        }
        $user = User::withTrashed()->where('name', $name)->firstOrFail();
        $userName = $user->name;
        $user->restore();
        return redirect(route('user.index'))->with('success', 'The user with name ' . $userName . ' has been restored.');
    }

    public function removeUser($name)
    {
        if(!$this->user->can('admin.delete'))
        {
            return Inertia::render('403');
        }
        $user = User::withTrashed()->where('name', $name)->firstOrFail();
        $userName = $user->name;
        $user->forceDelete();
        return redirect(route('user.index'))->with('error', 'The user with name ' . $userName . ' has been removed permanently.');
    }
}

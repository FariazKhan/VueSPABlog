<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
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
        if(!$this->user->can('role.view'))
        {
            return Inertia::render('403');
        }
        $roles = Role::all();
        $roleHolders = [];
        $permissions = [];
        foreach($roles as $role)
        {
            $arr = array($role->name => $role->users()->get()->count());
            $roleHolders = array_merge($roleHolders, $arr);
            $perms = [];
            foreach($role->permissions as $perm)
            {
                array_push($perms, $perm->formal_name);
            }
            $namedPerms = array($role->name => $perms);
            $permissions = array_merge($permissions, $namedPerms);
        }
        $roles = DB::table('roles')->select('name')->whereNull('deleted_at')->paginate(5);
        return Inertia::render('admin/Role/Index', [
            'roles' => $roles,
            'roleHolders' => $roleHolders,
            'permissions' => $permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->user->can('role.create'))
        {
            return Inertia::render('403');
        }
        $permissionGroupNames = User::get_permission_groups();
        $allPermissions = Permission::all(['formal_name', 'name']);
        return Inertia::render('admin/Role/Create', [
            'permissionGroupNames' => $permissionGroupNames,
            'allPermissions' => $allPermissions
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
        if(!$this->user->can('role.create'))
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'name' => 'required|unique:roles',
            'groups' => 'nullable',
            'permissions' => 'nullable',
        ], [
            'name.required' => 'The name is required'
        ]);
        
        $invalid_request = false;

        if(isset($request->groups) && Validator::make($request->all(), ['groups' => 'json'])->fails())
        {
            return back()->with('error', 'Invalid request, please try again.');
        }

        if(isset($request->permissions) && Validator::make($request->all(), ['permissions' => 'json'])->fails())
        {
            return back()->with('error', 'Invalid request, please try again.');
        }
        if(json_decode($request->groups) === [] && json_decode($request->permissions) === [])
        {
            return back()->with('error', 'You haven\'t assigned any permissions to this role.');
        }
        else
        {
            if($request->groups === null || json_decode($request->groups) === [])
            {
                $permissions = json_decode($request->permissions);
                $role = Role::create(['name' => $request->name]);
                $role->syncPermissions($permissions);
                return redirect(route('role.index'))->with('success', 'Role created successfully!');
            }
            elseif($request->permissions === null || json_decode($request->permissions) === [])
            {
                $groups = json_decode($request->groups);
                $permissions = collect();
                foreach($groups as $group)
                {
                    foreach(User::get_permissions_by_group_name($group)->toBase() as $perm)
                    {
                        $permissions = $permissions->concat($perm);
                    }
                }
                $role = Role::create(['name' => $request->name]);
                $role->syncPermissions($permissions);
                return redirect(route('role.index'))->with('success', 'Role created successfully!');
            }
            elseif(json_decode($request->groups) !== [] && json_decode($request->permissions) !== [])
            {
                $permissions = collect();
                $non_grouped = json_decode($request->permissions);
                $permissions = $permissions->concat($non_grouped);
                $groups = json_decode($request->groups);
                foreach($groups as $group)
                {
                    foreach(User::get_permissions_by_group_name($group)->toBase() as $perm)
                    {
                        $permissions = $permissions->concat($perm);
                    }
                }
                $role = Role::create(['name' => $request->name]);
                $role->syncPermissions($permissions);
                return redirect(route('role.index'))->with('success', 'Role created successfully!');
            }
        }
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
        if(!$this->user->can('role.edit'))
        {
            return Inertia::render('403');
        }
        if($name !== 'Author')
        {
            $role = Role::where('name', $name)->firstOrFail();
            $roleName = $role->name;
            $permissions = $role->permissions()->pluck('name');
            $permissionGroupNames = User::get_permission_groups();
            $allPermissions = Permission::all(['formal_name', 'name']);
            return Inertia::render('admin/Role/Edit', [
                'permissionGroupNames' => $permissionGroupNames,
                'allPermissions' => $allPermissions,
                'roleName' => $roleName,
                'permissions' => $permissions,
                ]);
        }
        else
        {
            return redirect(route('role.index'))->with('error', 'The author role cannot be modified.');
        }
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
        if(!$this->user->can('role.edit'))
        {
            return Inertia::render('403');
        }
        if($name !== 'Author')
        {
            $role = Role::where('name', $name)->firstOrFail();
            $this->validate($request, [
                'name' => ['required', Rule::unique('roles')->ignore($role->id)],
                'groups' => 'nullable',
                'permissions' => 'nullable',
            ], [
                'name.required' => 'The name is required',
                'name.unique' => 'The name is already taken'
            ]);
            
            $invalid_request = false;

            if(isset($request->groups) && Validator::make($request->all(), ['groups' => 'json'])->fails())
            {
                return back()->with('error', 'Invalid request, please try again.');
            }

            if(isset($request->permissions) && Validator::make($request->all(), ['permissions' => 'json'])->fails())
            {
                return back()->with('error', 'Invalid request, please try again.');
            }
            if(json_decode($request->groups) === [] && json_decode($request->permissions) === [])
            {
                return back()->with('error', 'You haven\'t assigned any permissions to this role.');
            }
            else
            {
                if($request->groups === null || json_decode($request->groups) === [])
                {
                    $permissions = json_decode($request->permissions);
                    $role->name = $request->name;
                    $role->syncPermissions($permissions);
                    $role->save();
                    return redirect(route('role.index'))->with('success', 'Role created successfully!');
                }
                elseif($request->permissions === null || json_decode($request->permissions) === [])
                {
                    $groups = json_decode($request->groups);
                    $permissions = collect();
                    foreach($groups as $group)
                    {
                        foreach(User::get_permissions_by_group_name($group)->toBase() as $perm)
                        {
                            $permissions = $permissions->concat($perm);
                        }
                    }
                    $role->name = $request->name;
                    $role->syncPermissions($permissions);
                    $role->save();
                    return redirect(route('role.index'))->with('success', 'Role created successfully!');
                }
                elseif(json_decode($request->groups) !== [] && json_decode($request->permissions) !== [])
                {
                    $permissions = collect();
                    $non_grouped = json_decode($request->permissions);
                    $permissions = $permissions->concat($non_grouped);
                    $groups = json_decode($request->groups);
                    foreach($groups as $group)
                    {
                        foreach(User::get_permissions_by_group_name($group)->toBase() as $perm)
                        {
                            $permissions = $permissions->concat($perm);
                        }
                    }
                    $role->name = $request->name;
                    $role->syncPermissions($permissions);
                    $role->save();
                    return redirect(route('role.index'))->with('success', 'Role updated successfully!');
                }
            }
        }
        else
        {
            return redirect(route('role.index'))->with('error', 'The author role cannot be modified.');
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
        if(!$this->user->can('role.delete'))
        {
            return Inertia::render('403');
        }
        $role = Role::where('name', $name)->firstOrFail();
        if($role->name !== 'Author')
        {

            foreach($role->users()->get() as $user)
            {
                $user->removeRole($role->name);
            }
            $roleName = $role->name;
            $role->delete();
            return redirect(route('role.index'))->with('warning', 'The role containing name ' . $roleName . ' has been trashed. All users under this role are now holding no roles, therefore they cannot perform any task.');
        }
        else
        {
            return redirect(route('role.index'))->with('error', 'The author role cannot be modified.');
        }
    }

    public function trashedRoles()
    {
        if(!$this->user->can('role.delete'))
        {
            return Inertia::render('403');
        }
        $roles = Role::onlyTrashed()->paginate(5);
        return Inertia::render('admin/Role/Trashed',[
            'roles' => $roles
        ]);
    }

    public function restoreRole($name)
    {
        if(!$this->user->can('role.delete'))
        {
            return Inertia::render('403');
        }
        $role = Role::withTrashed()->where('name', $name)->firstOrFail();
        $roleName = $role->name;
        $role->restore();
        return redirect(route('role.index'))->with('success', 'The role containing name ' . $roleName . ' has been restored.');
    }

    public function removeRole($name)
    {
        if(!$this->user->can('role.delete'))
        {
            return Inertia::render('403');
        }
        $role = Role::withTrashed()->where('name', $name)->firstOrFail();
        $roleName = $role->name;
        $role->forceDelete();
        return redirect(route('role.index'))->with('error', 'The role containing name ' . $roleName . ' has been removed permanently.');
    }
}

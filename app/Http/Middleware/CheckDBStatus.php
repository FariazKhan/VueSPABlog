<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class CheckDBStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        $has_table = true;
        $table_names = [
            'categories',
            'failed_jobs',
            'migrations',
            'model_has_permissions',
            'model_has_roles',
            'pages',
            'password_resets',
            'permissions',
            'personal_access_tokens',
            'posts',
            'roles',
            'role_has_permissions',
            'users',
        ];
        $unmigrated_tables = [];
        foreach($table_names as $table_name)
        {
            if(Schema::hasTable($table_name))
            {
                continue;
            }
            else
            {
                $has_table = false;
                array_push($unmigrated_tables, $table_name);
            }
        }
        if($has_table)
        {
            return $next($request);
        }
        else
        {
            return redirect(route('setup-app', ['params' => $unmigrated_tables]));
        }
    }
}

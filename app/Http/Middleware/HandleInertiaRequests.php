<?php

namespace App\Http\Middleware;

use App\Models\BlogSettings;
use App\Models\Pages;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'warning' => $request->session()->get('warning'),
                ];
            },
            'pages' => function() use ($request) {
                $pages = Pages::all();
                $subset = $pages->map(function ($page) {
                    return collect($page->toArray())
                        ->only(['title', 'slug'])
                        ->all();
                });
                
                return $subset;
            },
            'blogInfo' => function() use ($request) {
                $blogInfo = BlogSettings::findOrFail(1);
                $arr = ['blogName' => $blogInfo->blog_name, 'welcomeText' => $blogInfo->welcome_text, 'blogLogo' => $blogInfo->blog_logo, 'coverImage' => $blogInfo->cover_image, 'footerText' => $blogInfo->footer_text];
                return collect($arr);
            },
            'blogAuthor' => function() use ($request) {
                $author = User::role('Author')->get()->first();
                if($author !== null)
                {
                    $author = $author->only('name', 'about', 'image');
                    return $author;
                }
                else
                {
                    return [];
                }
            },

            'menuItems' => function() use ($request) {
                /**
                 * Post
                 * Categories
                 * Pages
                 * Profile
                 * Manage Users
                 * Manage Roles
                 * Blog settings
                 */
                $menu_items_for_user = [];
                $user = $request->user();
                if($user == null)
                {
                    return $menu_items_for_user;
                }
                if($user->hasPermissionTo('post.view'))
                {
                    array_push($menu_items_for_user, ['routeName' => 'post.index', 'iconName' => 'fas fa-mail-bulk', 'label' => 'Posts']);
                }
                if($user->hasPermissionTo('category.view'))
                {
                    array_push($menu_items_for_user, ['routeName' => 'category.index', 'iconName' => 'far fa-folder-open', 'label' => 'Categories']);
                }
                if($user->hasPermissionTo('page.view'))
                {
                    array_push($menu_items_for_user, ['routeName' => 'page.index', 'iconName' => 'far fa-folder-open', 'label' => 'Pages']);
                }
                if($user->hasPermissionTo('admin.view'))
                {
                    array_push($menu_items_for_user, ['routeName' => 'user.index', 'iconName' => 'fas fa-user-cog', 'label' => 'Manage users']);
                }
                if($user->hasPermissionTo('role.view'))
                {
                    array_push($menu_items_for_user, ['routeName' => 'role.index', 'iconName' => 'fas fa-user-lock', 'label' => 'Manage roles']);
                }
                if($user->hasRole('Author'))
                {
                    array_push($menu_items_for_user, ['routeName' => 'showBlogSettings', 'iconName' => 'fas fa-cogs', 'label' => 'Blog settings']);
                }
                return $menu_items_for_user;
            },
        ]);
    }
}

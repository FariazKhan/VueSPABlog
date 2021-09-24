<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\User;
use App\Models\BlogSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Posts::orderBy('created_at', 'desc')->get();
        $authors = [];
        $readTime = [];
        $shortDescriptions = [];
        $postCategories = [];
        foreach($posts as $post)
        {
            $author = User::find($post->created_by)->get()->first()->select('name', 'image')->get()->first();
            array_push($authors, $author);
            
            Str::macro('readDuration', function(...$text) {
                $totalWords = str_word_count(implode(" ", $text));
                $minutesToRead = round($totalWords / 200);
            
                return (int)max(1, $minutesToRead);
            });
            array_push($readTime, Str::readDuration($post->body). ' mins read');
            array_push($shortDescriptions, Str::limit($post->short_description, 128) . '[...]');
            array_push($postCategories, $post->category()->get()->first()->only(['name', 'slug']));
        }
        $posts = Posts::orderBy('created_at', 'desc')->select('title', 'slug', 'short_description', 'featured_image', 'body', 'created_by', 'created_at', 'updated_at')->get();

        return Inertia::render('user/Home', [
            'posts' => $posts,
            'authors' => $authors,
            'readTime' => $readTime,
            'shortDescriptions' => $shortDescriptions,
            'postCategories' => $postCategories
        ]);
    }
    
    public function showPost($slug)
    {
        return Inertia::render('user/Post');
    }
    
    public function admin()
    {
        $total_posts = Posts::all()->count();
        $total_users = User::all()->count();
        $total_trashed_users = User::onlyTrashed()->get()->count();
        return Inertia::render('admin/Home', [
            'posts' => Posts::latest()->take(5)->get(),
            'total_posts' => $total_posts,
            'total_users' => $total_users,
            'total_trashed_users' => $total_trashed_users
        ]);
    }

    public function showProfile()
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $user = collect(User::findOrFail(Auth::user()->id)->only(['name', 'email', 'image', 'about', 'created_at']));
        $userSince = Auth::user()->created_at->format('jS F, Y');
        $roleName = Auth::user()->getRoleNames()->first();
        return Inertia::render('admin/Profile/Index', [
            'user' => $user,
            'userSince' => $userSince,
            'roleName' => $roleName
        ]);
    }

    public function saveProfileInfo(Request $request)
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('users')->ignore(Auth::user()->id)
            ],
            'email' => [
                'required', 
                Rule::unique('users')->ignore(Auth::user()->id)
            ],
            'about' => 'required|between:0,256'
        ], [
        'name.required' => 'The name is required',
        'name.unique' => 'The name is already taken',
        'email.required' => 'The email is required',
        'email.unique' => 'The email is already taken',
        'about.required' => 'The about is required',
        'about.between' => 'Please keep about info within 256 characters',
        ]);
        
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->about = $request->about;
        $user->save();
        return redirect(route('showProfile'))->with('success', 'Profile updated successfully!');
    }

    public function saveProfileImage(Request $request)
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'profileImage' => 'required|image',
        ], [
            'profileImage.required' => 'The image is required',
            'profileImage.image' => 'The image is invalid',
        ]);
        
        $user = User::find(Auth::user()->id);
        if($request->hasFile('profileImage'))
        {
            if($user->image !== 'avatar.png' && strlen($user->image) > 0)
            {
                unlink(public_path('assets/images/user/' . $user->image));
            }
            
            $file = $request->file('profileImage');
            $name = md5(random_int(0, 99999)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/user/'), $name);
            $user->image = $name;
            $user->save();
            return redirect(route('showProfile'))->with('success', 'Profile image updated successfully!');
        }
        else
        {
            return redirect(route('showProfile'))->with('error', 'Invalid image, please try again.');
        }
    }

    public function saveProfilePassword(Request $request)
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'password' => 'required',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword'
        ], [
        'password.required' => 'The password is required',
        'newPassword.required' => 'The new password is required',
        'newPassword.min' => 'The password must be at least 8 characters',
        'confirmPassword.required' => 'The confirm password field is empty',
        'confirmPassword.same' => 'The passwords doesn\'t match',
        ]);
        
        $user = User::find(Auth::user()->id)->get()->first();
        if(Hash::check($request->password, $user->password))
        {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            return redirect(route('showProfile'))->with('success', 'Profile updated successfully!');
        }
        else
        {
            return redirect(route('showProfile'))->with('error', 'Wrong password, please try again.');
        }
    }

    public function showBlogSettings()
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $data = DB::table('blog-settings')->get(['blog_name', 'blog_logo', 'cover_image', 'welcome_text', 'footer_text'])->first();
        return Inertia::render('admin/BlogSettings/Index', [
            'data' => $data
        ]);
    }

    public function saveBlogSettings(Request $request)
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'blogName' => 'required|min:4',
            'welcomeText' => 'required|min:4',
            'footerText' => 'required|min:4',
        ], [
            'blogName.required' => 'The blog name cannot be empty',
            'blogName.min' => 'The blog name must be at least 4 characters',
            'welcomeText.required' => 'The welcome text cannot be empty',
            'welcomeText.min' => 'The blog name must be at least 4 characters',
            'footerText.required' => 'The footer text cannot be empty',
            'footerText.min' => 'The footer text must be at least 4 characters',
        ]);

        $blog_settings = BlogSettings::findOrFail(1);
        $blog_settings->blog_name = $request->blogName;
        $blog_settings->welcome_text = $request->welcomeText;
        $blog_settings->footer_text = $request->footerText;
        $blog_settings->save();
        return redirect(route('showBlogSettings'))->with('success', 'Settings updated successfully!');
    }

    public function saveBlogLogo(Request $request)
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'blogLogo' => 'required|image',
        ], [
            'blogLogo.required' => 'The blog logo is required',
            'blogLogo.image' => 'The blog logo must be a valid image',
        ]);

        $blog_settings = BlogSettings::findOrFail(1);
        if($request->hasFile('blogLogo'))
        {
            if(!empty($blog_settings->blog_logo))
            {
                unlink(public_path() . '/assets/images/blog/' . $blog_settings->blog_logo);
            }
            $file = $request->file('blogLogo');
            $name = md5(rand(0, 99999)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/assets/images/blog/', $name);
            $blog_settings->blog_logo = $name;
            $blog_settings->save();
            return redirect(route('showBlogSettings'))->with('success', 'Logo changed successfully!');
        }
        else
        {
            return back()->with('error', 'Invalid image');
        }
    }

    public function saveBlogCoverImage(Request $request)
    {
        if(!Auth::check())
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'coverImage' => 'required|image',
        ], [
            'coverImage.required' => 'The blog logo is required',
            'coverImage.image' => 'The blog logo must be a valid image',
        ]);

        $blog_settings = BlogSettings::findOrFail(1);
        if($request->hasFile('coverImage'))
        {
            if(!empty($blog_settings->cover_image))
            {
                unlink(public_path() . '/assets/images/blog/' . $blog_settings->cover_image);
            }
            $file = $request->file('coverImage');
            $name = md5(rand(0, 99999)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path() . '/assets/images/blog/', $name);
            $blog_settings->cover_image = $name;
            $blog_settings->save();
            return redirect(route('showBlogSettings'))->with('success', 'Cover photo changed successfully!');
        }
        else
        {
            return back()->with('error', 'Invalid image');
        }
    }

    public function setupApp($params)
    {
        // return dd($params);
        // return Inertia::render('Setup', [
        //     'unmigrated_tables' => $params['unmigrated_tables']
        // ]);
    }

    public function migrateTables(Request $request)
    {
        // return dd($request->all());
    }

}

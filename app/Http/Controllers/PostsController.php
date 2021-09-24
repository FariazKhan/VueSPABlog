<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostsController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except('show');
        $this->middleware(function($request, $next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index()
    {
        if(!$this->user->can('post.view'))
        {
            return Inertia::render('403');
        }
        $posts = Posts::orderBy('created_at', 'desc')->select('title', 'slug', 'short_description', 'featured_image', 'body', 'created_by', 'created_at', 'updated_at')->paginate(4);
        return Inertia::render('admin/Post/Index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->user->can('post.create'))
        {
            return Inertia::render('403');
        }
        $categories = Categories::select('name', 'slug')->get();
        return Inertia::render('admin/Post/Create', [
            'categories' => $categories
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
        if(!$this->user->can('post.create'))
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'title' => 'required|unique:posts',
            'short_description' => 'required',
            'featured_image' => 'required|image',
            'postCategory' => 'required|exists:categories,slug',
            'body' => 'required',
        ], [
            'title.required' => 'The title is required',
            'title.unique' => 'The title has been used in another post, please choose a different one',
            'short_description.required' => 'The short description is required',
            'featured_image.required' => 'The featured image is required',
            'featured_image.image' => 'The featured image is invalid',
            'postCategory.required' => 'The post category is required',
            'postCategory.exists' => 'Invalid request, please try again.',
            'body.required' => 'The body is required',
        ]);
        
        $post = new Posts;
        $post->title = $request->title;
        $post->slug = Str::of($post->title)->slug('-');
        $post->short_description = $request->short_description;
        $post->created_by = Auth::user()->id;
        $post->categories_id = Categories::where('slug', $request->postCategory)->firstOrFail()->id;
        if($request->file('featured_image'))
        {
            if(in_array($request->file('featured_image')->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'bmp', 'ico']))
            {
                $extension = $request->file('featured_image')->getClientOriginalExtension();
                $fileName = md5(random_int(0, 90000)) . '.' . $extension;
                $request->file('featured_image')->move(public_path('assets/images/post/'), $fileName);
                $post->featured_image = $fileName;
            }
        }
        $post->body = $request->body;
        $post->save();
        return redirect(route('post.index'))->with('success', 'Post created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = (object) Posts::where(['slug' => $slug])->firstOrFail()->only(['title', 'slug', 'short_description', 'featured_image', 'body', 'created_by', 'created_at', 'updated_at']);
        if(session()->get('recent_posts.recently_viewed') == null)
        {
            session()->push('recent_posts.recently_viewed', $post->slug);
        }
        elseif(count(session()->get('recent_posts.recently_viewed')) < 5)
        {
            session()->push('recent_posts.recently_viewed', $post->slug);
        }
        $created_by = User::findOrFail($post->created_by)->get()->first()->name;
        $created_at = $post->created_at->format('jS F, Y');
        return Inertia::render('user/Post', [
            'post' => $post,
            'created_by' => $created_by,
            'created_at' => $created_at
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if(!$this->user->can('post.edit'))
        {
            return Inertia::render('403');
        }
        $post = (object) Posts::where(['slug' => $slug])->firstOrFail()->only(['title', 'short_description', 'slug', 'featured_image', 'body', 'created_by', 'created_at', 'updated_at']);
        $currentCategory = Categories::find(Posts::where('slug', $slug)->get()->firstOrFail()->categories_id)->slug;
        $categories = Categories::select('name', 'slug')->get();
        return Inertia::render('admin/Post/Edit', [
            'post' => $post,
            'categories' => $categories,
            'currentCategory' => $currentCategory
        ]);
    }

    public function update(Request $request, $slug)
    {
        if(!$this->user->can('post.edit'))
        {
            return Inertia::render('403');
        }
        $post = Posts::where('slug', $slug)->firstOrFail();

        $this->validate($request, [
            'title' => [ 'required', 
                Rule::unique('posts')->ignore($post->id),
            ],
            'short_description' => 'required',
            'featured_image' => 'present',
            'postCategory' => 'required|exists:categories,slug',
            'body' => 'required'],
            [
                'title.required' => 'The title field is required',
                'title.unique' => 'The title has been used in another post, please choose a different one',
                'short_description.required' => 'The short description field is required',
                'featured_image.required' => 'The featured image field is required',
                'featured_image.image' => 'The featured image is invalid',
                'postCategory.required' => 'The post category is required',
                'postCategory.exists' => 'Invalid request, please try again.',
                'body.required' => 'The body field is required',
            ]
        );

        $post->title = $request->title;
        $post->short_description = $request->short_description;
        $post->slug = Str::of($post->title)->slug('-');
        if($request->file('featured_image'))
        {
            if(in_array($request->file('featured_image')->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'bmp', 'ico']))
            {
                $extension = $request->file('featured_image')->getClientOriginalExtension();
                $fileName = md5(random_int(0, 90000)) . '.' . $extension;
                $request->file('featured_image')->move(public_path('assets/images/post/'), $fileName);
                $post->featured_image = $fileName;
            }
        }
        $post->body = $request->body;
        $post->categories_id = Categories::where('slug', $request->postCategory)->firstOrFail()->id;
        $post->save();
        return redirect(route('post.index'))->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $post = Posts::where('slug', $slug)->firstOrFail();
        $title = $post->title;
        $post->delete();
        return redirect(route('post.index'))->with('warning', 'The post containing title ' . $title . ' has been deleted successfully.');
    }

    public function trashedPosts()
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $posts = Posts::onlyTrashed()->orderBy('created_at', 'desc')->select('title', 'slug', 'short_description', 'featured_image', 'body', 'created_by', 'created_at', 'updated_at')->paginate(4);
        return Inertia::render('admin/Post/Trashed', [
            'posts' => $posts
        ]);
    }

    public function restorePost($slug)
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $posts = Posts::withTrashed()->where('slug', $slug)->restore();
        return redirect(route('post.index'))->with('success', 'Post restored successfully!');
    }

    public function removePost($slug)
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $posts = Posts::withTrashed()->where('slug', $slug)->forceDelete();
        return redirect(route('post.index'))->with('warning', 'Post permanently removed.');
    }
}

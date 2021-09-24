<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function($request, $next){
            $this->user = Auth::user();
            return $next($request);
        }, ['auth', 'verified'])->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->user->can('page.view'))
        {
            return Inertia::render('403');
        }
        $pages = Pages::orderBy('created_at', 'desc')->select('title', 'slug', 'body')->paginate();
        return Inertia::render('admin/Page/Index', [
            'pages' => $pages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->user->can('page.create'))
        {
            return Inertia::render('403');
        }
        return Inertia::render('admin/Page/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->user->can('page.create'))
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'title' => 'required|unique:pages,title',
            'body' => 'required'
        ]);

        $page = new Pages;
        $page->title = $request->title;
        $page->slug = Str::slug($request->title, '-');
        $page->body = $request->body;
        $page->save();

        return redirect(route('page.index'))->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = (object) Pages::where(['slug' => $slug])->firstOrFail()->only(['title', 'slug', 'body']);
        return Inertia::render('user/Page', [
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if(!$this->user->can('post.edit'))
        {
            return Inertia::render('403');
        }
        $page = collect(Pages::where('slug', $slug)->firstOrFail()->only(['title', 'slug', 'body']));
        return Inertia::render('admin/Page/Edit', [
            'page' => $page
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if(!$this->user->can('post.edit'))
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'title' => ['required', Rule::unique('pages', 'title')->ignore(Pages::where('slug', $slug)->firstOrfail()->id)],
            'body' => 'required'
        ]);

        $page = Pages::where('slug', $slug)->firstOrfail();
        $page->title = $request->title;
        $page->slug = Str::slug($request->title, '-');
        $page->body = $request->body;
        $page->save();

        return redirect(route('page.index'))->with('success', 'Page updated successfully!');
    }

    public function destroy($slug)
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $page = Pages::where('slug', $slug)->firstOrFail();
        $title = $page->title;
        $page->delete();
        return redirect(route('page.index'))->with('warning', 'The page containing title ' . $title . ' has been deleted successfully.');
    }


    public function trashedPages()
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $pages = Pages::onlyTrashed()->orderBy('created_at', 'desc')->select('title', 'slug')->paginate(4);
        return Inertia::render('admin/Page/Trashed', [
            'pages' => $pages
        ]);
    }

    public function restorePage($slug)
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $pages = Pages::withTrashed()->where('slug', $slug)->restore();
        return redirect(route('page.index'))->with('success', 'Page restored successfully!');
    }

    public function removePage($slug)
    {
        if(!$this->user->can('post.delete'))
        {
            return Inertia::render('403');
        }
        $pages = Pages::withTrashed()->where('slug', $slug)->forceDelete();
        return redirect(route('page.index'))->with('error', 'Page deleted permanently!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
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
        if(!$this->user->can('category.view'))
        {
            return Inertia::render('403');
        }
        $categories = Categories::orderBy('created_at', 'desc')->get();
        $postsCount = [];
        foreach($categories as $c)
        {
            array_push($postsCount, $c->posts()->get()->count());
        }
        $categories = Categories::orderBy('created_at', 'desc')->select('name', 'slug')->paginate();
        return Inertia::render('admin/Category/Index', [
            'categories' => $categories,
            'postsCount' => $postsCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->user->can('category.create'))
        {
            return Inertia::render('403');
        }
        return Inertia::render('admin/Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$this->user->can('category.create'))
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'name' => 'required|unique:categories'
        ], [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The name is already in use.'
        ]);

        $category = new Categories;
        $category->name = $request->name;
        $category->slug = Str::slug($category->name, '-');
        $category->save();
        return redirect(route('category.index'))->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Categories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        if(!$this->user->can('category.edit'))
        {
            return Inertia::render('403');
        }
        $category = Categories::where('slug', $slug)->get()->first()->only('name', 'slug');
        return Inertia::render('admin/Category/Edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if(!$this->user->can('category.edit'))
        {
            return Inertia::render('403');
        }
        $this->validate($request, [
            'name' => [
                'required', 
                Rule::unique('categories')->ignore(Categories::where('slug', $slug)->get()->first()->id)
            ]
        ], [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The name is already in use.'
        ]);

        $category = Categories::where('slug', $slug)->get()->first();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name, '-');
        $category->save();
        return redirect(route('category.index'))->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if(!$this->user->can('category.delete'))
        {
            return Inertia::render('403');
        }
        $category = Categories::where('slug', $slug)->firstOrFail();
        $name = $category->name;
        $category->delete();
        return redirect(route('category.index'))->with('warning', 'The category containing name ' . $name . ' has been deleted successfully.');
    }


    public function trashedCategories()
    {
        if(!$this->user->can('category.delete'))
        {
            return Inertia::render('403');
        }
        $categories = Categories::onlyTrashed()->orderBy('created_at', 'desc')->select('name', 'slug')->paginate(4);
        return Inertia::render('admin/Category/Trashed', [
            'categories' => $categories
        ]);
    }

    public function restoreCategory($slug)
    {
        if(!$this->user->can('category.delete'))
        {
            return Inertia::render('403');
        }
        $category = Categories::withTrashed()->where('slug', $slug)->restore();
        return redirect(route('category.index'))->with('success', 'Category restored successfully!');
    }

    public function removeCategory($slug)
    {
        if(!$this->user->can('category.delete'))
        {
            return Inertia::render('403');
        }
        $category = Categories::withTrashed()->where('slug', $slug)->forceDelete();
        return redirect(route('category.index'))->with('warning', 'Category permanently removed.');
    }
}

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [HomeController::class, 'index'])->name('homePage');

// Admin panel
Route::get('admin', [HomeController::class, 'admin'])->middleware(['auth', 'verified',])->name('admin');

// Category
Route::get('admin/category/trashed', [CategoriesController::class, 'trashedCategories'])->middleware(['auth', 'verified'])->name('trashedCategories');
Route::post('admin/category/restore/{slug}', [CategoriesController::class, 'restoreCategory'])->middleware(['auth', 'verified'])->name('restoreCategory');
Route::post('admin/category/remove/{slug}', [CategoriesController::class, 'removeCategory'])->middleware(['auth', 'verified'])->name('removeCategory');
Route::resource('admin/category', CategoriesController::class)->middleware(['auth', 'verified']);

// Post
Route::get('admin/post/trashed', [PostsController::class, 'trashedPosts'])->middleware(['auth', 'verified'])->name('trashedPosts');
Route::post('admin/post/restore/{slug}', [PostsController::class, 'restorePost'])->middleware(['auth', 'verified'])->name('restorePost');
Route::post('admin/post/remove/{slug}', [PostsController::class, 'removePost'])->middleware(['auth', 'verified'])->name('removePost');
Route::resource('post', PostsController::class);

// Page
Route::get('admin/pages/trashed', [PagesController::class, 'trashedPages'])->middleware(['auth', 'verified'])->name('trashedPages');
Route::post('admin/page/restore/{slug}', [PagesController::class, 'restorePage'])->middleware(['auth', 'verified'])->name('restorePage');
Route::post('admin/page/remove/{slug}', [PagesController::class, 'removePage'])->middleware(['auth', 'verified'])->name('removePage');
Route::resource('page', PagesController::class);

// Profile
Route::get('admin/profile', [HomeController::class, 'showProfile'])->name('showProfile')->middleware(['auth', 'verified']);
Route::post('admin/profile/save', [HomeController::class, 'saveProfileInfo'])->middleware(['auth', 'verified'])->name('saveProfileInfo');
Route::post('admin/profile/save-pwd', [HomeController::class, 'saveProfilePassword'])->middleware(['auth', 'verified'])->name('saveProfilePassword');
Route::post('admin/profile/save-image', [HomeController::class, 'saveProfileImage'])->middleware(['auth', 'verified'])->name('saveProfileImage');

// User
Route::get('admin/user/trashed', [UserController::class, 'trashedUsers'])->middleware(['auth', 'verified'])->name('trashedUsers');
Route::post('admin/user/restore/{name}', [UserController::class, 'restoreUser'])->middleware(['auth', 'verified'])->name('restoreUser');
Route::post('admin/user/remove/{name}', [UserController::class, 'removeUser'])->middleware(['auth', 'verified'])->name('removeUser');
Route::post('admin/user/update-data/{name}', [UserController::class, 'updateUserPassword'])->middleware(['auth', 'verified'])->name('updateUserPassword');
Route::resource('admin/user', UserController::class)->middleware(['auth', 'verified']);

// Role
Route::get('admin/role/trashed', [RoleController::class, 'trashedRoles'])->middleware(['auth', 'verified'])->name('trashedRoles');
Route::post('admin/role/restore/{name}', [RoleController::class, 'restoreRole'])->middleware(['auth', 'verified'])->name('restoreRole');
Route::post('admin/role/remove/{name}', [RoleController::class, 'removeRole'])->middleware(['auth', 'verified'])->name('removeRole');
Route::resource('admin/role', RoleController::class)->middleware(['auth', 'verified']);

// Blog settings
Route::get('admin/blog-settings', [HomeController::class, 'showBlogSettings'])->middleware(['auth'])->name('showBlogSettings');
Route::post('admin/save-blog-settings', [HomeController::class, 'saveBlogSettings'])->middleware(['auth'])->name('saveBlogSettings');
Route::post('admin/save-blog-logo', [HomeController::class, 'saveBlogLogo'])->middleware(['auth'])->name('saveBlogLogo');
Route::post('admin/save-blog-cover-image', [HomeController::class, 'saveBlogCoverImage'])->middleware(['auth'])->name('saveBlogCoverImage');

// Setup Application
Route::get('setup-app/{params}', [HomeController::class, 'setupApp'])->middleware(['auth'])->name('setup-app');
Route::post('migrate-tables', [HomeController::class, 'migrateTables'])->name('migrateTables')->middleware('auth');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('post/{slug}', [HomeController::class, 'showPost'])->name('showPost');

require __DIR__.'/auth.php';

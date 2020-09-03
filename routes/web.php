<?php

use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('app')->middleware(AdminCheck::class)->group(function(){
    /* Tags */
    Route::post('/create_tag', 'AdminController@addTag');
    Route::post('/edit_tag', 'AdminController@editTag');
    Route::post('/delete_tag', 'AdminController@deleteTag');
    Route::get('/get_tags', 'AdminController@getTags');
    /* Tags */

    /* category */

    Route::post('/upload', 'AdminController@upload');
    Route::post('/delete_image', 'AdminController@deleteImage');
    Route::post('/create_category', 'AdminController@addCategory');
    Route::post('/edit_category', 'AdminController@editCategory');
    Route::post('/delete_category', 'AdminController@deleteCategory');
    Route::get('/get_categories', 'AdminController@getCategories');

    /* category */

    /* Users */
    Route::post('/create_user', 'AdminController@CreateUser');
    Route::post('/edit_user', 'AdminController@editUser');
    // Route::post('/delete_tag', 'AdminController@deleteTag');
    Route::get('/get_users', 'AdminController@getUsers');
    Route::post('/admin_login', 'AdminController@adminLogin');
    /* Users */

    /* User Roles */
    Route::post('/create_role', 'AdminController@addRole');
    Route::post('/edit_role', 'AdminController@editRole');
    Route::post('/delete_role', 'AdminController@deleteRole');
    Route::get('/get_roles', 'AdminController@getRoles');
    Route::post('/assign_roles', 'AdminController@assignRoles');
    /* User Roles */

    /* Blog */
    Route::post('/create_blog', 'AdminController@createBlog');
    Route::post('/edit_role', 'AdminController@editRole');
    Route::post('/delete_blog', 'AdminController@deleteBlog');
    Route::get('/blogsdata', 'AdminController@blogsdata');
    Route::get('/blog_single/{id}', 'AdminController@singleBlogItem');
    Route::post('/update_blog/{id}', 'AdminController@updateBlog');
    Route::post('/assign_roles', 'AdminController@assignRoles');
    /* Blog */

});



/* User Login */

Route::get('/', 'AdminController@index');
Route::get('logout', 'AdminController@logout');
//Route::get('{slug}', 'AdminController@index');
Route::any('{slug}', 'AdminController@index')->where('slug', '([A-z\d-\/_.]+)?');

/* USer Login */

 Route::post('createBlog', 'AdminController@uploadEditorImage');



// Route::get('/', function () {
//     return view('welcome');
// });

// Route::any('{slug}', function () {
//     return view('welcome');
// });

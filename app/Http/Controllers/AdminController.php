<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Blog;
use App\Role;
use App\User;
use App\Blogtag;
use App\Category;
use App\Blogcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index(Request $request){
        
        //first check if you are loggedin user and admin user
        if(!Auth::check() && $request->path() != 'login' ){
            return redirect('/login');
        }
        
        if(!Auth::check() && $request->path() == 'login' ){
            return view('welcome');
        }
        //you are already Loggedin
        $user = Auth::user();
        if( $user->role->isAdmin == 0 ){
            return redirect('/login');
        }
        if( $request->path() == 'login' ){
            return redirect('/');
        }

        return $this->checkForPermission($user, $request);
    }

    public function checkForPermission( $user, $request ){
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        if(!$permission) return view('welcome');
        foreach( $permission as $p ){
            if($p->name == $request->path()){
                if($p->read){
                    $hasPermission = true;
                }
            }

        }
        if(Str::contains($request->path(), 'editblog')){
            $hasPermission = true;
        }
        if($hasPermission) return view('welcome');
        return view('notfound');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request) {
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function getTags() {
        $tags = Tag::orderBy('id', 'desc')->get();
        return response()->json($tags);
    }

    public function editTag(Request $request) {
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function deleteTag(Request $request) {
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->delete();
    }

    public function upload( Request $request ) {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png,gif',
        ]);

        $filename = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $filename);
        return $filename;
    }

    /* Category */
    public function deleteImage( Request $request ){
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
        
    }

    public function deleteFileFromServer( $fileName ){
        $filePath = public_path().'/uploads/'.$fileName;

        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return;
    }

    public function addCategory(Request $request) {
        $this->validate($request, [
            'categoryName' => 'required'
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function getCategories() {
        $categories = Category::orderBy('id', 'desc')->get();
        return response()->json($categories);
    }

    public function editCategory(Request $request) {
        $this->validate($request, [
            'categoryName' => 'required',
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function deleteCategory(Request $request) {

        $this->validate($request, [
            'id' => 'required',
        ]);
        $this->deleteFileFromServer($request->iconImage);
        return Category::where('id', $request->id)->delete();
    }

    /* Category */

    /* Users */
    public function createUser( Request $request ){

        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);

        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        return $user;
    }

    public function getUsers(){
        //return User::where('role_id', '!=', 0)->get();
        return User::get();
    }

    public function editUser( Request $request ){
        $this->validate($request, [
            'fullName' => 'required',
            'email'    => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'role_id' => 'required',
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'role_id' => $request->role_id,
        ];

        if($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        $user = User::where('id', $request->id )->update($data);

        return $user;
    }
    /* Users */

    public function adminLogin( Request $request ){
        $this->validate($request, [
            'email'    => "bail|required|email",
            'password' => 'bail|required|min:6',
        ]);

        if( Auth::attempt( [ 'email' => $request->email, 'password' => $request->password, ] ) ) {
            $user = Auth::user();
            if($user->role->isAdmin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details',
                ], 401);
            }
            return response()->json([
                'msg' => 'You are logged in',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect Login details'
            ], 401);
        }
    }

    /* Role managemnt */
    public function addRole(Request $request) {
        $this->validate($request, [
            'roleName' => 'required'
        ]);
        return Role::create([
            'roleName' => $request->roleName
        ]);
    }

    public function getRoles() {
        //$roles = Role::orderBy('id', 'desc')->get();
        $roles = Role::get();
        return response()->json($roles);
    }

    public function editRole(Request $request) {
        $this->validate($request, [
            'roleName' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName
        ]);
    }

    public function deleteRole(Request $request) {
        $this->validate($request, [
            'roleName' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->delete();
    }

    public function assignRoles( Request $request ){
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required'
        ]);

        return Role::where( 'id', $request->id)->update([
            'permission' => $request->permission,
        ]);
    }
    /* Role management */

    //upload image from editor JS
    public function uploadEditorImage(Request $request){
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,png,gif',
        ]);

        $filename = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $filename);
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => 'http://localhost:8001/uploads/'.$filename,
            ],
        ]);
        //return $filename;
    }

    public function slug(){
        $title = 'This is a nice tutorial';
        return Blog::create([
            'title' => 'test',
            'post' => 'some post',
            'post_excerpt' => 'dsadsadsa',
            'user_id' => 1,
            'metaDescription' => 'abcs'
        ]);
    }

    public function createBlog(Request $request){

        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',

        ]);

        $categories = $request->category_id;
        $blogCategory = [];

        $tags = $request->tag_id;
        $blogTag = [];

        DB::beginTransaction();

        try{
            $blog = Blog::create([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData
            ]);

            //insert blog categories
            foreach($categories as $c){
                array_push($blogCategory, [
                    'category_id' => $c,
                    'blog_id' => $blog->id,
                ]);
            }

            //insert blog tags
            foreach($tags as $t){
                array_push($blogTag, [
                    'tag_id' => $t,
                    'blog_id' => $blog->id,
                ]);
            }

            Blogcategory::insert($blogCategory);

            Blogtag::insert($blogTag);

            DB::commit();

            return 'done';
        } catch( \throwable  $th){
            DB::rollBack();
            return 'notdone';
        }
    }

    public function blogsdata(Request $request){
        return Blog::with(['tag','cat'])->orderBy('id', 'desc')->paginate($request->total);
    }

    public function deleteBlog(Request $request){
        return Blog::where('id', $request->id)->delete();

    }

    public function singleBlogItem(Request $request){
        return Blog::with(['tag','cat'])->where('id', $request->id)->first();
    }
    public function updateBlog( Request $request, $id ){
        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',

        ]);

        $categories = $request->category_id;
        $blogCategory = [];

        $tags = $request->tag_id;
        $blogTag = [];

        DB::beginTransaction();

        try{
            $blog = Blog::where('id', $id)->update([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData
            ]);

            echo 'blog'.$blog;

            //insert blog categories
            foreach($categories as $c){
                array_push($blogCategory, [
                    'category_id' => $c,
                    'blog_id' => $id,
                ]);
            }

            print_r($blogCategory);
            //insert blog tags
            foreach($tags as $t){
                array_push($blogTag, [
                    'tag_id' => $t,
                    'blog_id' => $id,
                ]);
            }

            print_r($blogCategory);
            //delete all previous categories and tags

            Blogcategory::where('blog_id', $id)->delete();
            Blogtag::where('blog_id', $id)->delete();  
            
            Blogcategory::insert($blogCategory);
            Blogtag::insert($blogTag);

            DB::commit();

            return 'done';
        } catch( \throwable  $e){
            DB::rollBack();
            return $e;

            
            return 'notdone';
        }
    }
}

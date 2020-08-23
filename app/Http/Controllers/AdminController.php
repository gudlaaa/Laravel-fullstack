<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if( $user->userType == 'User' ){
            return redirect('/login');
        }
        if( $request->path() == 'login' ){
            return redirect('/');
        }


        return view('welcome');
        return $request->path();
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
            'userType' => 'required',
        ]);

        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'password' => $password,
            'userType' => $request->userType,
        ]);
        return $user;
    }

    public function getUsers(){
        return User::where('userType', '!=', 'user')->get();
    }

    public function editUser( Request $request ){
        $this->validate($request, [
            'fullName' => 'required',
            'email'    => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'userType' => 'required',
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email'    => $request->email,
            'userType' => $request->userType,
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
            if($user->userType == 'User') {
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
}

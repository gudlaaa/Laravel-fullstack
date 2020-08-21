<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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
}

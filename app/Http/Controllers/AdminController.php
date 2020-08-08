<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $resquest) {
        
        return Tag::create([
            'tagName' => $resquest->tagName
        ]);

    }

    public function getTags() {
        $tags = Tag::orderBy('id', 'desc')->get();
        return response()->json($tags);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Test extends Controller
{
    //
    public function test(){
        return response()->json([
            'msg' => 'some error occured'
        ],422);
    }
}

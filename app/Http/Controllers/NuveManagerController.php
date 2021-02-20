<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NuveManager;

class NuveManagerController extends Controller
{
    public function initLanguage(Request $request)
    {   
        $res = NuveManager::getLanguage();
        
        $response = array(
            'status' => $res != null,
            'msg' => $res ? "Language Ready To Use!!" : "There are error when loading the language!!",
        );
        return response()->json($response); 
    }

    public function welcome()
    {        
        return view('welcome');
    }

    public function orthography()
    {
        return view('orthography');
    }

    public function morphotactics()
    {
        return view('morphotactics');
    }
    
    public function nuveTest()
    {
        return view('nuveTest');
    }

}

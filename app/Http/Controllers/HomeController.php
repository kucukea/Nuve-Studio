<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NuveManager;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function uploadLangPost(Request $request){
        $request->validate([
            'code' => 'required',
            'countryCode' => 'required',
            'orthographyFile' => 'required|file',
            'morphotacticsFile' => 'required|file',
            'rootsFile' => 'required|file',
            // 'rNameFile' => 'required|file',
            // 'rAbbrvFile' => 'required|file',
            'suffixesFile' => 'required|file',
        ]);
        
        $path = NuveManager::getStorageLanguagesUrl($request->code . "_" . $request->countryCode);

        $request->orthographyFile->storeAs($path, NuveManager::Orthography);                                                                                                                                                                                          
        $request->morphotacticsFile->storeAs($path, NuveManager::Morphotactics);                                                                                                                                                                                          
        $request->rootsFile->storeAs($path, NuveManager::Roots);                                                                                                                                                                                          
        // $request->rNameFile->storeAs($path, NuveManager::RName);                                                                                                                                                                                          
        // $request->rAbbrvFile->storeAs($path, NuveManager::RAbbrv);                                                                                                                                                                                          
        $request->suffixesFile->storeAs($path, NuveManager::Suffixes);                                                                                                                                                                                          
 
        return back()
            ->with('success','You have successfully upload Language.');
    }

    public function deleteLang(Request $request, $lang){
        $langFolderPath = NuveManager::getStorageLanguagesPath() . '/' . $lang;
        
        Storage::deleteDirectory($langFolderPath);

        return redirect('home');
    }
}

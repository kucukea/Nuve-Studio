<?php

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

use Illuminate\Http\Request;

Route::get('/', 'NuveManagerController@welcome')->name('welcome');
Route::get('/orthography', 'NuveManagerController@orthography')->name('orthography');
Route::get('/morphotactics', 'NuveManagerController@morphotactics')->name('morphotactics');
Route::get('/nuveTest', 'NuveManagerController@nuveTest')->name('nuveTest');

Route::get('/home', 'HomeController@index')->name('home');
Route::post('uploadLang','HomeController@uploadLangPost')->name("uploadLang");
Route::get('deleteLang/{lang}','HomeController@deleteLang')->name("deleteLang");

// Ajax Call
Route::post('/initLanguage','NuveManagerController@initLanguage')->name("initLanguage");



Route::get('/downloadFile/{lang}/{fileName}', function($lang, $fileName){
    $path = App\NuveManager::getLanguagesFiles($lang)[$fileName];
    return Storage::download($path);
})->name("downloadFile")->middleware('auth');

Route::get('/changeCurrentLanguage/{lang}', function($lang){
    App\NuveManager::changeCurrentLanguage($lang);
    return redirect()->route('welcome');
})->name("changeCurrentLanguage");

Route::post('/analyze', function(){
    $lang = App\NuveManager::getLanguage();
    $nuve = App\NuveManager::getNuve($lang);
    $words = explode(" ", request()->_text);
    $inflection = request()->inflection;
    
    
    $inflections = array();
    foreach($lang->suffixes->byId as $suff){
        if(!in_array($suff->group, $inflections))
        $inflections[$suff->id] = $suff->type; // == "" ? "IC_COGUL_lAr" : $suff->group;
    }
    // return response()->json($inflections);
    $res = [];
    foreach($words as $text){
        $solutions = $nuve->analyze($text);
        foreach($solutions as $solution){
            $res[$text][] = $solution->analysis();
        }
        
        if (!empty($res) && $inflection != "all"){
        foreach($res[$text] as $id => $sol){
            $suffs = explode(" ", $sol);
            for($i = 1; $i < count($suffs);$i++){
                if ($inflections[$suffs[$i]] != $inflection){
                    unset($res[$text][$id]);
                    break;
                }
            }
        }
    }
    }

    

    return response()->json($res);
})->name("analyze");

Route::post('/cekimler', function(Request $request){
    $lang = App\NuveManager::getLanguage();
    $nuve = App\NuveManager::getNuve($lang);
    $cekimler = array();
    // $word = $nuve->analyze($request->word);
    // if (empty($word))
    // return false;
    
    // $word = explode(" ", head($word)->analysis())[0];
    // $type = explode("/", $word)[1];
    $type = $request->type;
    $word = $request->word;
    if ($type == "ISIM"){
    $suf = collect($lang->suffixes->byId)->where('group', '=', 'ISIM_SAHIPLIK')->all();
    // dd($lang->suffixes->byId);
    
    foreach($suf as $IC => $s){
        $text = $word . "/". $type." ".$IC;
        
        $textArr = explode(' ', $text);
        $solutionLexicalForm = "";
        $solutionPhases = [];
        $status = false;
        try {
            $resultWord = $nuve->generate($textArr);
            for($i = 0; $i < $resultWord->allomorphCount(); $i++){
                $solutionLexicalForm .= $resultWord->allomorph($i)->surface() . ' ';
            }
            $solutionPhases = $resultWord->getSurfacesAfterEachPhase();
            $status = true;
        } catch (Exception $e) {
            $solutionPhases =  $e->getMessage();
        } catch (Throwable $e) {
            $solutionPhases =  $e->getMessage();
        }
        $cekimler[$text] = array(
            'phases' => last($solutionPhases),
            'lexicalForm' => $solutionLexicalForm,
            'status' => $status,
        );
    }
    foreach($suf as $IC => $s){
        $text = $word . "/". $type." "."IC_COGUL_lAr"." ".$IC;
        
        $textArr = explode(' ', $text);
        $solutionLexicalForm = "";
        $solutionPhases = [];
        $status = false;
        try {
            $resultWord = $nuve->generate($textArr);
            for($i = 0; $i < $resultWord->allomorphCount(); $i++){
                $solutionLexicalForm .= $resultWord->allomorph($i)->surface() . ' ';
            }
            $solutionPhases = $resultWord->getSurfacesAfterEachPhase();
            $status = true;
        } catch (Exception $e) {
            $solutionPhases =  $e->getMessage();
        } catch (Throwable $e) {
            $solutionPhases =  $e->getMessage();
        }
        $cekimler[$text] = array(
            'phases' => last($solutionPhases),
            'lexicalForm' => $solutionLexicalForm,
            'status' => $status,
        );
    }
}else if ($type == "FIIL"){
    $zamanlar = collect($lang->suffixes->byId)->where('group', '=', 'FIIL_ZAMAN')->all();
    $sahisler = collect($lang->suffixes->byId)->where('group', '=', 'EK_FIIL_SAHIS')->all();
    foreach($zamanlar as $zaman => $z){
        foreach($sahisler as $sahis => $s){
            $text = $word . "/". $type." ".$zaman." ".$sahis;
        
        $textArr = explode(' ', $text);
        $solutionLexicalForm = "";
        $solutionPhases = [];
        $status = false;
        try {
            $resultWord = $nuve->generate($textArr);
            for($i = 0; $i < $resultWord->allomorphCount(); $i++){
                $solutionLexicalForm .= $resultWord->allomorph($i)->surface() . ' ';
            }
            $solutionPhases = $resultWord->getSurfacesAfterEachPhase();
            $status = true;
        } catch (Exception $e) {
            $solutionPhases =  $e->getMessage();
        } catch (Throwable $e) {
            $solutionPhases =  $e->getMessage();
        }
        $cekimler[$text] = array(
            'phases' => last($solutionPhases),
            'lexicalForm' => $solutionLexicalForm,
            'status' => $status,
        );
        }
    }
}
return response()->json($cekimler);
})->name('nuve.cekimler');


Route::get('/MKT/{type}/{word}', 'testingController@cekimler')->name('nuve.allCekimler');
Route::post('/MKT', 'testingController@cekimler')->name('nuve.allCekimler');
Route::get('/MKT', 'testingController@mkt');

Route::get('/conjugation', 'testingController@conjugationIndex');
Route::post('/conjugation', 'testingController@conjugation')->name('nuve.conjugation');
Route::get('/testing', 'testingController@testingIndex');
Route::post('/testing', 'testingController@testing')->name('nuve.testing');

Route::get('/analyze/{text}', function($text){
    $nuve = App\NuveManager::getNuve(App\NuveManager::getLanguage());
    $words = explode(" ", $text);
    $res = [];
    foreach($words as $text){
        $solutions = $nuve->analyze($text);
        foreach($solutions as $solution){
            dd($solution->analysis());
            $res[$text][] = $solution->analysis();
        }
    }
    return response()->json($res);
})->name("analyzeGet");

Route::post('/generate', function(){
    $nuve = App\NuveManager::getNuve(App\NuveManager::getLanguage());
    $textArr = explode(' ', request()->_text);
    $solutionLexicalForm = "";
    $solutionPhases = [];
    $status = false;
    try {
        $resultWord = $nuve->generate($textArr);
        for($i = 0; $i < $resultWord->allomorphCount(); $i++){
            $solutionLexicalForm .= $resultWord->allomorph($i)->surface() . ' ';
        }
        $solutionPhases = $resultWord->getSurfacesAfterEachPhase();
        $status = true;
    } catch (Exception $e) {
       $solutionPhases =  $e->getMessage();
    } catch (Throwable $e) {
        $solutionPhases =  $e->getMessage();
    }
    
    $solution = array(
        'phases' => $solutionPhases,
        'lexicalForm' => $solutionLexicalForm,
        'status' => $status,
    );
    return response()->json($solution);
})->name("generate");

/**
 * API Functions
 */

Route::post('/api/{lang}/morphotactics', function($lang){
    if($lang == "tr"){
        App\NuveManager::changeCurrentLanguage("tr_TR");
    }
    else if($lang == "uz"){
        App\NuveManager::changeCurrentLanguage("uz_UZ");
    }

    $nuve = App\NuveManager::getNuve(App\NuveManager::getLanguage());
    $solutions = $nuve->analyze(request()->_text);
    $res = [];
    foreach($solutions as $solution){
        $res[] = $solution->analysis();
    }
    return response()->json($res);
});

Route::post('/api/{lang}/orthography', function($lang){
    if($lang == "tr"){
        App\NuveManager::changeCurrentLanguage("tr_TR");
    }
    else if($lang == "uz"){
        App\NuveManager::changeCurrentLanguage("uz_UZ");
    }

    $nuve = App\NuveManager::getNuve(App\NuveManager::getLanguage());
    $textArr = explode(' ', request()->_text);
    $solutionLexicalForm = "";
    $solutionPhases = [];
    $status = false;
    try {
        $resultWord = $nuve->generate($textArr);
        for($i = 0; $i < $resultWord->allomorphCount(); $i++){
            $solutionLexicalForm .= $resultWord->allomorph($i)->surface() . ' ';
        }
        $solutionPhases = $resultWord->getSurfacesAfterEachPhase();
        $status = true;
    } catch (Exception $e) {
       $solutionPhases =  $e->getMessage();
    } catch (Throwable $e) {
        $solutionPhases =  $e->getMessage();
    }
    
    $solution = array(
        'phases' => $solutionPhases,
        'lexicalForm' => $solutionLexicalForm,
        'status' => $status,
    );
    return response()->json($solution);
});


Auth::routes();


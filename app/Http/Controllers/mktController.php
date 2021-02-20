<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Throwable;
use App\NuveManager;

class testingController extends Controller
{
    //
    public function conjugationIndex(){
        $lang = NuveManager::getLanguage();
        $groups = array();
        foreach($lang->suffixes->byId as $suff){
            if (NuveManager::getCurrentLanguageCode() == "uz_UZ"){
                if(!in_array($suff->type, $groups))
                $groups[] = $suff->type; // == "" ? "IC_COGUL_lAr" : $suff->group;
            } else{
                if(!in_array($suff->group, $groups))
                    $groups[] = $suff->group; // == "" ? "IC_COGUL_lAr" : $suff->group;
            }
}
        return view('conjugation', compact('groups'));
    }
    public function testingIndex(){
        
        return view('testing');
    }

    public function conjugation(Request $request){
        $word = strtolower($request->word);
        $type = $request->type;

        $lang = NuveManager::getLanguage();
        $nuve = NuveManager::getNuve($lang);

        if (empty($request->rule))
            return $this->cekimler($request);

        $testRules = array();
        $rule = explode("+", $request->rule);
        
        // $rule = explode("+", "FIIL+FIIL_ZAMAN+EK_FIIL_SAHIS");
        $text = $word.'/'.$type;
        $testRules[] = $text;

        if (self::orthography($text, $nuve) == "no result")
            return response("no result");

        self::loopRules($text, $rule, $testRules, 1, $lang);
        
        $tests = array();
        $tests[$text] = $word;
        foreach($testRules as $test){
            $res = self::orthography($test, $nuve);
            $morphs = self::morphotactics($res, $nuve);
            $status = in_array($test, $morphs);
            $tests[$test] = array("res" => $res, "status" => $status);
        }
        
        return response()->json($tests);
    }

    public static function cekimler(Request $request, $test = false){
        $word = $request->word;
        $type = $request->type;
        $lang = NuveManager::getLanguage();
        $nuve = NuveManager::getNuve($lang);
        $morph = $lang->morphotactics->_graph;
        $rules = array();
        $newRule = $word.'/'.$type;
        $rules[] = $newRule;
        $loop = 0;
        self::loopMorph($newRule, $type, $rules, $morph, $loop);

        // $t = "kitap/ISIM BAGLAC_dA SORU_mU EKFIIL_TANIMLAMA_DUr";
        // dd(!(strpos($t, $morph->getTransitions("EKFIIL_TANIMLAMA_DUr")[1]->target) !== false));
        $cekimler = array();

        foreach($rules as $rule){
            $cekimler[$rule] =  self::orthography($rule, $nuve);
            if ($test){
                $morphs = self::morphotactics($cekimler[$rule], $nuve);
                $status = in_array($rule, $morphs);
                if ($status) return "true";
                // return "T";
            }
        }
        if ($test) return "false";

        return response()->json($cekimler);
    }
    
    public static function loopRules($text, $rule, &$testRules, $i, $lang){
        if ($i < count($rule)){
            if ($rule[$i] == "IC_COGUL_lAr")
                $suf = array("IC_COGUL_lAr" => "IC_COGUL_lAr");
            else if (NuveManager::getCurrentLanguageCode() == "uz_UZ")
                $suf = collect($lang->suffixes->byId)->where('type', '=', $rule[$i])->all();
            else
                $suf = collect($lang->suffixes->byId)->where('group', '=', $rule[$i])->all();

            foreach($suf as $IC => $s){
                $testRule = $text." ".$IC;
                $testRules[] = $testRule;
                if (isset($rule[$i+1])){
                    self::loopRules($testRule, $rule, $testRules, $i+1, $lang);
                }
        }
    }
    }

    public static function loopMorph($oldRule, $type, &$rules, $morph, $loop){
        $loop++;
        foreach ($morph->getTransitions($type) as $trans){
            $newRule = $oldRule." ".$trans->target;
            $rules[] = $newRule;
            // if ($loop < 4 && !(preg_match("/{$trans->target}/i", $oldRule)))
            if ($loop < 2 && !(strpos($oldRule, $trans->target) !== false))
                self::loopMorph($newRule, $trans->target, $rules, $morph, $loop);
        }
        return $rules;
    }

    public static function orthography($text, $nuve){
        // dd($text);
        
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


        if (is_array($solutionPhases))
            return last($solutionPhases);
        else
            return "no result";

        dd($solutionPhases);
        return array(
            'phases' => last($solutionPhases),
            'lexicalForm' => $solutionLexicalForm,
            'status' => $status,
        );
    }

    public static function morphotactics($text, $nuve){
        $solutions = $nuve->analyze($text);
        $res = array();
        foreach($solutions as $solution){
            $res[] = $solution->analysis();
        }

        return $res;
    }

    public function testing(Request $request){
        $lang = NuveManager::getLanguage();
        $nuve = NuveManager::getNuve($lang);
        $morph = $lang->morphotactics->_graph;
        
        $result = array();
        $loop = 0;
        // dd($lang->roots->byId);
        foreach($lang->roots->byId as $root => $R){
            $loop++;
            if ($loop > $request->cekimMax) break;
            if ($loop < $request->cekimMin) continue;
            $rootArray = explode('/', $root);
            $word = $rootArray[0];
            $type = $rootArray[1];

                $result[$root] = self::testCekimler($word, $type, $morph, $nuve);

        }
        if (empty($result)) return response("finish");
        return response()->json($result);
    }

    
    public static function testCekimler($word, $type, $morph, $nuve){

        $rules = array();
        $newRule = $word.'/'.$type;
        $rules[] = $newRule;
        $loop = 0;
        self::loopMorph($newRule, $type, $rules, $morph, $loop);

        $cekimler = array();
        $ruleLoop = 0;
        foreach($rules as $rule){
            $ruleLoop++;
            $cekimler[$rule] =  self::orthography($rule, $nuve);
                try{
                    $morphs = self::morphotactics($cekimler[$rule], $nuve);
                }catch(Exception $e){
                    // dd($e);
                    continue;
                }
                $status = in_array($rule, $morphs);
                if ($status ) return "true";
        }
        return "false";
    }
    
}

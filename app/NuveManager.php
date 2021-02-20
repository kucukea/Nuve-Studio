<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Argevim\Nuve\Nuve;
use Exception;
use Argevim\Nuve\Lang\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NuveManager extends Model
{   
    public const Orthography = "orthography.xml";
    public const Morphotactics = "morphotactics.xml";
    public const Roots = "roots.csv";
    public const RName = "rName.csv";
    public const RAbbrv = "rAbbrv.csv";
    public const Suffixes = "suffixes.csv";

    public static function serverRootPath(){
        if(self::isThereUploadedLanguage()){
            return base_path() . "/storage/app/";
        }
        else{
            return base_path() . "/vendor/argevim/nuve";
        }
    }

    public static function getNuve($language = null){
        $serverRootPath = self::serverRootPath();   
        $languageId = self::getCurrentLanguageCode();
        $langInfo = explode('_', $languageId);
        $nuve = new Nuve($serverRootPath, self::getStorageLanguagesPath(), $langInfo[0], $langInfo[1], $language);
        return $nuve;
    }
    public static function getCurrentLanguageCode(){
        return  Cache::get('oldNuveLang', 'tr_TR');
    }
    public static function getLanguage($languageId = ""){
        try{
            // Check if the current language not same the old one
            self::changeCurrentLanguage($languageId);
            
            if (!(Cache::has("MKTupdate") && Cache::get("MKTupdate") == "1")){
                Cache::forget('NuveLang');
                Cache::rememberForever("MKTupdate", function(){return "1";});
            }
            if (Cache::has('NuveLang')) {
                $languageSerialize = Cache::get('NuveLang');
                $language = unserialize($languageSerialize);
                return $language;
            }
            
            $languageSerialize = Cache::rememberForever('NuveLang', function () {                
                $serverRootPath = NuveManager::serverRootPath();   
                $nuve;
                if(self::isThereUploadedLanguage()){
                    $langInfo = explode('_', self::getCurrentLanguageCode());
                    $nuve = new Nuve($serverRootPath, self::getStorageLanguagesPath(), $langInfo[0], $langInfo[1]);
                }
                else{
                    $nuve = new Nuve($serverRootPath);
                }
                $language = $nuve->getLanguage();
                return serialize($language);
            });
            $language = unserialize($languageSerialize);
            return $language;
        }
        catch(Exception $e){
            return null;
        }
        return null;
    }

    public static function changeCurrentLanguage($languageId){
        if(empty($languageId) || self::getCurrentLanguageCode() == $languageId){
            return false;
        }

        Cache::forget('NuveLang');
        Cache::forget('oldNuveLang');

        $languageIdCached = Cache::rememberForever('oldNuveLang', function () use ($languageId) {
            $langNow = $languageId;
            return $langNow;
        });
        
        return $languageIdCached == $languageId;
    }

    public static function getStorageLanguagesPath(){
        return "Resources";
    }
    public static function getStorageLanguagesUrl($languageId = ""){
        return self::getStorageLanguagesPath() . "\\" . $languageId . "\\";
    }

    public static function isThereUploadedLanguage(){
        // Check if there are uploaded languages files by user
        $allLanguages = self::getLanguagesThatUploaded();
        return count($allLanguages) > 0 ? $allLanguages : false;
    }

    public static function getLanguagesThatUploaded(){
        $mainFolder = self::getStorageLanguagesUrl();
        $allFolders = Storage::directories($mainFolder);
        $allLanguages = [];
        foreach($allFolders as $folder){
            $allLanguages[] = Str::replaceFirst("Resources/", "", $folder);
        }
        return $allLanguages;
    }

    public static function getLanguagesFiles($languageId){
        $mainFolder = self::getStorageLanguagesUrl($languageId);
        $allFiles = Storage::files($mainFolder);
        $allFilesPaths = [];
        foreach($allFiles as $file){
            $allFilesPaths[Str::replaceFirst("Resources/{$languageId}/", "", $file)] = $file;
        }
        return $allFilesPaths;
    }
}

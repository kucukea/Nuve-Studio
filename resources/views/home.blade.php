@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Language Managment</div>

                <div class="card-body">
                    @php
                        $allLangs = App\NuveManager::isThereUploadedLanguage();
                    @endphp
                    @if($allLangs)
                        @foreach($allLangs as $lang)
                            <div class="row">
                                <div class="col-md-12 p-3 mb-2 bg-primary text-white text-center">
                                    <strong>{{$lang}}</strong>
                                    <a class="DeleteTheLang" href="{{ route('deleteLang', $lang) }}" onclick="if(confirm('Are you sure want to delete this language ({{ $lang }})?')) return true; else return false;">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            
                                <!-- TODO: List files of language -->
                                @php
                                    $allFiles = App\NuveManager::getLanguagesFiles($lang);
                                @endphp
                                <div class="col-md-3 offset-md-1 p-2 mr-4 mb-2 bg-success text-white text-center">
                                    <strong >{{App\NuveManager::Orthography}}</strong><br>
                                    <a class="DownloadLinkWithIcon" href="{{ route('downloadFile', ['lang' => $lang, 'fileName' => App\NuveManager::Orthography] ) }}" target="_blank"><i class="fas fa-download"></i></a>
                                </div>
                                <div class="col-md-3 p-2 mr-4 mb-2 bg-success text-white text-center">
                                    <strong>{{App\NuveManager::Morphotactics}}</strong><br>
                                    <a class="DownloadLinkWithIcon" href="{{ route('downloadFile', ['lang' => $lang, 'fileName' => App\NuveManager::Morphotactics] ) }}" target="_blank"><i class="fas fa-download"></i></a>
                                </div>
                                <div class="col-md-3 p-2 mr-4 mb-2 bg-success text-white text-center">
                                    <strong>{{App\NuveManager::Suffixes}}</strong><br>
                                    <a class="DownloadLinkWithIcon" href="{{ route('downloadFile', ['lang' => $lang, 'fileName' => App\NuveManager::Suffixes] ) }}" target="_blank"><i class="fas fa-download"></i></a>
                                </div>
                                
                                <div class="col-md-3 offset-md-1 p-2 mr-4 mb-2 bg-success text-white text-center">
                                    <strong>{{App\NuveManager::Roots}}</strong><br>
                                    <a class="DownloadLinkWithIcon" href="{{ route('downloadFile', ['lang' => $lang, 'fileName' => App\NuveManager::Roots] ) }}" target="_blank"><i class="fas fa-download"></i></a>
                                </div>
                                <div class="col-md-3 p-2 mr-4 mb-2 bg-success text-white text-center">
                                    <strong>{{App\NuveManager::RName}}</strong><br>
                                    <a class="DownloadLinkWithIcon" href="{{ route('downloadFile', ['lang' => $lang, 'fileName' => App\NuveManager::RName] ) }}" target="_blank"><i class="fas fa-download"></i></a>
                                </div>
                                <div class="col-md-3 p-2 mr-4 mb-2 bg-success text-white text-center">
                                    <strong>{{App\NuveManager::RAbbrv}}</strong><br>
                                    <a class="DownloadLinkWithIcon" href="{{ route('downloadFile', ['lang' => $lang, 'fileName' => App\NuveManager::RAbbrv] ) }}" target="_blank"><i class="fas fa-download"></i></a>
                                </div>
                            </div>

                        @endforeach
                    @else

                        <div class="p-3 mb-2 bg-danger text-white">You don't upload any language until now. There is just Turkish language by default.</div>

                    @endif
                </div>
            </div>
            
            <br><br><br>
            <div class="card">
                <div class="card-header">Upload New Language</div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
 
                        <div class="alert alert-success alert-block">
 
                            <button type="button" class="close" data-dismiss="alert">×</button>
 
                            <strong>{{ $message }}</strong>
 
                        </div>
 
                    @endif
 
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
 
                    <form action="{{ route('uploadLang') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="langCode" class="col-sm-3 col-form-label text-md-left">{{ __('Code') }}</label>

                            <div class="col-md-6">
                                <input id="langCode" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ old('code') }}" required autofocus>
                                <small id="fileHelp" class="form-text text-muted">Language code like (tr)</small>
                                @if ($errors->has('code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="langCountryCode" class="col-sm-3 col-form-label text-md-left">{{ __('Country Code') }}</label>

                            <div class="col-md-6">
                                <input id="langCountryCode" type="text" class="form-control{{ $errors->has('countryCode') ? ' is-invalid' : '' }}" name="countryCode" value="{{ old('countryCode') }}" required>
                                <small id="fileHelp" class="form-text text-muted">Language country code like (TR)</small>
                                @if ($errors->has('countryCode'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('countryCode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-left">{{ __('Orthography File') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control-file" name="orthographyFile" id="orthographyFile" aria-describedby="fileHelp" accept=".xml">
                                <small id="fileHelp" class="form-text text-muted">Please upload "orthography.xml" file.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-left">{{ __('Morphotactics File') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control-file" name="morphotacticsFile" id="morphotacticsFile" aria-describedby="fileHelp" accept=".xml">
                                <small id="fileHelp" class="form-text text-muted">Please upload "morphotactics.xml" file.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-left">{{ __('Roots File') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control-file" name="rootsFile" id="rootsFile" aria-describedby="fileHelp" accept=".csv">
                                <small id="fileHelp" class="form-text text-muted">Please upload "roots.csv" file.</small>
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-left">{{ __('Root Names File') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control-file" name="rNameFile" id="rNameFile" aria-describedby="fileHelp" accept=".csv">
                                <small id="fileHelp" class="form-text text-muted">Please upload "rName.csv" file.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-left">{{ __('Root Abbrv File') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control-file" name="rAbbrvFile" id="rAbbrvFile" aria-describedby="fileHelp" accept=".csv">
                                <small id="fileHelp" class="form-text text-muted">Please upload "rAbbrv.csv" file.</small>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-md-left">{{ __('Suffixes File') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control-file" name="suffixesFile" id="suffixesFile" aria-describedby="fileHelp" accept=".csv">
                                <small id="fileHelp" class="form-text text-muted">Please upload "suffixes.csv" file.</small>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-3">
                                 <button type="submit" class="btn btn-primary">Start Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

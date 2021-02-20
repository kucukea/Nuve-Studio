<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- provide the csrf token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <link href="{{ asset('css/preloader.css') }}" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                color: #ffffff;
                font-weight: bold;
            }

            .sub-title{
                font-size: 30px;
                color: #ffffff;
                font-weight: bold;
            }

            .links a {
                color: #ffffff;
                padding: 0 25px;
                font-size: 20px;
                font-weight: bold;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .WhenLangReady{
                display: none;
            }

            .currentLanguage{
                color: red !important;
            }

        </style>
    </head>
    <body>
        @include('preloader')
        <div class="flex-center position-ref full-height WhenLangReady">
            @if (Route::has('login'))
                <div class="top-right links">
                    @php
                #App\NuveManager::changeCurrentLanguage("tr_TR");
                        $currentLanguage = App\NuveManager::getCurrentLanguageCode();
                        $allLangs = App\NuveManager::isThereUploadedLanguage();
                    @endphp
                    @if($allLangs)
                        @foreach($allLangs as $lang)
                            @if($currentLanguage == $lang)
                                <a class="currentLanguage" href="#">{{ $lang }}</a>
                            @else
                                <a href="{{ route('changeCurrentLanguage', $lang) }}">{{ $lang }}</a>
                            @endif
                        @endforeach
                    @endif

                    <a>|</a>
					@if($currentLanguage)
						<a href="{{ url('https://nuvestudio.com/downloads/'.$currentLanguage.'/'.$currentLanguage.'.zip') }}">&nbsp;</a>
						<a>&nbsp;</a>
					@endif
						
                    @auth
                        <a href="{{ url('/home') }}">Management</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    NUVE
                </div>
                <!-- <div class="sub-title m-b-md">
                    {{ $currentLanguage }}
                </div> -->

                <div class="links">
                    <a href="{{ route('orthography') }}">Orthography</a>
                    <a class="m-b-md" href="{{ route('morphotactics') }}">Morphotactics</a>
                    <a class="m-b-md" href="#">Translation</a>
                    <a class="m-b-md" href="#">Stemming</a>
                    <a class="m-b-md" href="{{ route('nuve.conjugation')}}">Conjugation</a>
                    <a class="m-b-md" href="{{ route('nuve.testing')}}">Testing</a>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>  
        <script src="{{ asset('js/preloader.js') }}"></script>  
        
        <script>
            $(document).ready(function(){
                callInitLang();                
            });
        </script>
    </body>
</html>

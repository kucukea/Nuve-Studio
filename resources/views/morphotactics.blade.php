@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Morphotactics ({{App\NuveManager::getCurrentLanguageCode()}})</div>

                 <div class="card-body">
                    <form>
                    <div class="form-group row">
                        <label for="text" class="col-sm-1 col-form-label text-md-right">{{ __('Text') }}</label>

                        <div class="col-md-11">
                            <input id="strText" type="text" class="form-control" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-5 offset-md-1">
                            <select id="inflection" name="inflection" class="form-control">
                                <option value="all">All</option>
                                <option value="Y">Inflectional</option>
                                <option value="N">Derivational</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="button" class="btn btn-primary getResultBtn" value="{{ __('Get Result') }}" />
                        </div>
                    </div>
                </form>
                </div>
            </div>
            
            <br><br>
            <div class="card">
                <div class="card-header">Result</div>

                 <div class="card-body">
                    <div class="row ResultsHere">
                         
                    </div>
                 </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.getResultBtn').click(function(){
                $("#preloaders").fadeIn(100);
                $('.ResultsHere').html("");
                $fadeInTime = 1000;
                var text = $('#strText').val();
                var inflection = $('#inflection').val();
                @if (App\NuveManager::getCurrentLanguageCode() == "uz_UZ" || App\NuveManager::getCurrentLanguageCode() == "uzb_UZB")
                    text = text.replace("'", "‘");
                @endif
                callOrthographyResult(text, inflection, function(data){
                    console.log(data);
                    var words = text.split(" ");
                    words.forEach(function(word){
                        $('.ResultsHere').append('<div style="display: none;font-size:20px;font-style:italic;font-weight:bold;" class="col-md-12 p-3 mb-2 bg-warning text-center">' + word + '</div>');
                        $('.ResultsHere div[style*="display: none"]').fadeIn($fadeInTime);
                        $fadeInTime += 200;
                        if (data[word] && data[word].length > 0){
                            data[word].forEach(function(element) {
                            $('.ResultsHere').append('<div style="display: none;" class="col-md-12 p-3 mb-2 bg-success text-white text-center"><strong>' + element + '</strong></div>');
                            $('.ResultsHere div[style*="display: none"]').fadeIn($fadeInTime);
                        });
                    }
                    else{
                        $('.ResultsHere').append('<div style="display: none;" class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + "No Result!!" + '</strong></div>');
                        $('.ResultsHere div[style*="display: none"]').fadeIn($fadeInTime);
                    }
                    }); 

                    $("#preloaders").fadeOut(2000);
                });
            });
            
            $("#strText").keypress(function(e){
                if (e.which == 13){
                    e.preventDefault();
                    $('.getResultBtn').click();
                }
            });
        });
    </script>
@endsection
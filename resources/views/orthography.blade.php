@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Orthography</div>

                <div class="card-body">
                    <form>
                    <div class="form-group row">
                        <label for="text" class="col-sm-1 col-form-label text-md-right">{{ __('Text') }}</label>

                        <div class="col-md-11">
                            <input id="strText" type="text" class="form-control" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-11 offset-md-1">
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
                callMorphotacticsResult($('#strText').val(), function(data){
                    console.log(data);
                    if(data.status){
                        $fadeInTime = 1000;
                        $('.ResultsHere').append('<div style="display: none;" class="col-md-12 p-3 mb-2 bg-primary text-white text-center"><strong> Lexical Form : ' + data.lexicalForm + '</strong></div>');
                        $('.ResultsHere div[style*="display: none"]').fadeIn($fadeInTime);
                        $fadeInTime += 800;
                        var phase = 1;
                        data.phases.forEach(function(element) {
                            $('.ResultsHere').append('<div style="display: none;" class="col-md-12 p-3 mb-2 bg-success text-white text-center"><strong>After Phase ' + phase++ + ' : ' + element + '</strong></div>');
                            $('.ResultsHere div[style*="display: none"]').fadeIn($fadeInTime);
                            $fadeInTime += 800;
                        });
                    }
                    else{
                        $('.ResultsHere').append('<div style="display: none;" class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + data + '</strong></div>');
                        $('.ResultsHere div[style*="display: none"]').fadeIn("slow");
                    }

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
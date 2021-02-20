@extends('layouts.app')
@section('styles')
<style>

.invisible{
  display: none;
}
.form-control, .getResultBtn{
    margin-top:10px;
}
.resultContainer{
    width:100%;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nuve Test ({{App\NuveManager::getCurrentLanguageCode()}})</div>

                 <div class="card-body">
                    <form id="cekimlerForm">
                        @csrf
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <select class="form-control" name="type">
                                <option value="ISIM">ISIM</option>
                                <option value="FIIL">FIIL</option>
                            </select>
                        </div>

                        <div class="col-sm-9">
                            <input id="strText" name="word" type="text" class="form-control" placeholder="Kelime" required autofocus>
                        </div>
                        

                        <div class="col-sm-10">
                            <input id="ruleText" name="rule" type="text" class="form-control" required placeholder="Kural ex: FIIL+FIIL_ZAMAN+EK_FIIL_SAHIS">
                        </div>
                        
                        <div class="col-sm-2">
                            <input type="button" class="btn btn-primary getResultBtn" style="width:100%" value="Start" />
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        
                    </div>
                </form>
                </div>
            </div>
            
            <br><br>
            <div class="card">
                <div class="card-header">Result 
                    <div class="btn-group" id="resultButtonGroup" role="group" style="float:right;">
                      <button type="button" data-target="cekimlerContainer" class="btn btn-primary">ÇEKİMLER</button>
                      <button type="button" data-target="testContainer" class="btn btn-secondary">TEST</button>
                    </div>
                </div>

                 <div class="card-body">
                    <div class="row ResultsHere">
                         <div class="resultContainer " id="cekimlerContainer"></div>
                         <div class="resultContainer invisible" id="testContainer"></div>
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
                allCekimler();
                return;
                $("#preloaders").fadeIn(100);
                $('#testContainer').html("");
                $.ajax({
                    url:'{{route("nuve.cekimler")}}',
                    data: $("#cekimlerForm").serialize(),
                    type: "post",
                    timeout:10000,
                    success: function(data){
                        console.log(data);
                        var table = "<table class='table table-striped'><thead><th>KURAL</th><th>ÇEKİMLER</th></thead><tbody>";
                        jQuery.each(data, function(index, item){
                            table += "<tr><td>"+index+"</td><td>"+item.phases+"</td></tr>";
                        });
                        table += "</tbody></table>";
                        $('#testContainer').html(table);
                    }, error: function(e){
                        $('#testContainer').html('<div class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + "No Result!!" + '</strong></div>');
                        console.log(e);
                    }
                });
                $("#preloaders").fadeOut(2000);
            });
            
          
            
            $("#strText").keypress(function(e){
                if (e.which == 13){
                    e.preventDefault();
                    $('.getResultBtn').click();
                }
            });
            
            
            $("#ruleText").keypress(function(e){
                if (e.which == 13){
                    e.preventDefault();
                    $('.getResultBtn').click();
                }
            });
            
            $("#resultButtonGroup button").click(function(){
                $("#resultButtonGroup button").removeClass("btn-primary").addClass("btn-secondary");
                $(".resultContainer").addClass("invisible");
                $(this).addClass("btn-primary").removeClass("btn-secondary");
                $("#"+$(this).data('target')).removeClass("invisible");
            });
        });

        function allCekimler(){
                $("#preloaders").fadeIn(100);
                $('#cekimlerContainer').html("");
                $('#testContainer').html("");
                $.ajax({
                    url:'{{route("nuve.allCekimler")}}',
                    data: $("#cekimlerForm").serialize(),
                    type: "post",
                    timeout:60000,
                    success: function(data){
                        console.log(data);
                        var table = "<table class='table table-striped'><thead><th>KURAL</th><th>ÇEKİMLER</th></thead><tbody>";
                        jQuery.each(data.cekimler, function(index, item){
                            table += "<tr><td>"+index+"</td><td>"+item+"</td></tr>";
                        });
                        table += "</tbody></table>";
                        $('#cekimlerContainer').html(table);


                        var table = "<table class='table table-striped'><thead><th>KURAL</th><th>ÇEKİMLER</th><th>STATUS</th></thead><tbody>";
                        jQuery.each(data.tests, function(index, item){
                            table += "<tr><td>"+index+"</td><td>"+item.res+"</td><td>"+item.status+"</td></tr>";
                        });
                        table += "</tbody></table>";
                        $('#testContainer').html(table);


                        $("#preloaders").fadeOut(2000);
                    }, error: function(e){
                        $('#cekimlerContainer').html('<div class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + "No Result!!" + '</strong></div>');
                        console.log(e);
                    }
                });
            }
    </script>
@endsection
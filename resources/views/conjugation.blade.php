@extends('layouts.app')
@section('styles')
<style>

.invisible{
  display: none;
}
.form-control, .getResultBtn, .input-group-prepend{
    margin-top:10px;
}
.resultContainer{
    width:100%;
}
#addRuleGroup{
    cursor: pointer;
}
.input-group-text{
    color:#fff;
}
#ruleGroups{
    position: absolute;
    margin-top: -2.1rem;
    width: 200px;
    background-color: rgba(0,0,0,.8);
    border-radius: 5px;
    margin-left: 1.35rem;
    z-index: 10;
    display: none;
}
#ruleGroups ul{
    list-style: none;
    padding-left:10px;
    padding-right:10px;
    margin-bottom: 5px;
}
#ruleGroups ul li{
    color: #fff;
    font-weight: bold;
    padding: 5px;
    cursor: pointer;
    border-bottom: 1px solid #fff;
}

#ruleGroups ul li:last-child{
    border-bottom: 0;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Conjugation ({{App\NuveManager::getCurrentLanguageCode()}})</div>

                 <div class="card-body">
                    <form id="cekimlerForm">
                        @csrf
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <select class="form-control" name="type" id="typeSelect">
                                <option value="ISIM">ISIM</option>
                                <option value="FIIL">FIIL</option>
                            </select>
                        </div>

                        <div class="col-sm-9">
                            <input id="strText" name="word" type="text" class="form-control" placeholder="Stem" required autofocus>
                        </div>
                        

                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend" id="addRuleGroup">
                                <div class="input-group-text bg-warning"><i class="fas fa-plus"></i></div>
                            </div>
                            <input id="ruleText" name="rule" type="text" class="form-control" required placeholder="Formula" value="ISIM">
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <input type="button" class="btn btn-primary getResultBtn" style="width:100%" value="Generate" />
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        
                    </div>
                </form>
                </div>
            </div>

            <div id="ruleGroups">
                <ul>
                    @foreach($groups as $group)
                    <li data-group="{{$group}}">{{$group}}</li>
                    @endforeach
                </ul>
            </div>


            <br><br>
            <div class="card">
                <div class="card-header">Result 
                </div>

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
            $("#typeSelect").change(function(){
                $("#ruleText").val($(this).val());
            });

            $("#addRuleGroup").click(function(){
                $("#ruleGroups").slideToggle();
            });

            $("#ruleGroups ul li").click(function(){
                $("#ruleText").val($("#ruleText").val() + "+"+$(this).data('group'));
                $("#ruleGroups").slideToggle();
            });

            $('.getResultBtn').click(function(){
                allCekimler();
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
        });

        function allCekimler(){
                $("#preloaders").fadeIn(100);
                $('#cekimlerContainer').html("");
                $('#testContainer').html("");
                var trueCounter = 0;
                var falseCounter = 0;
                $.ajax({
                    url:'{{route("nuve.conjugation")}}',
                    data: $("#cekimlerForm").serialize(),
                    type: "post",
                    timeout:60000,
                    success: function(data){
                        console.log(data);
                        if (data == "no result"){
                            $('.ResultsHere').html('<div class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + "No Result!!" + '</strong></div>');    
                        $("#preloaders").fadeOut(2000);
                        return;
                        }
                        var table = "<table class='table table-striped'><thead><th>FORMULA</th><th>SURFACE</th><th>TEST RESULT</th></thead><tbody>";
                        jQuery.each(data, function(index, item){
                            table += "<tr><td>"+index+"</td><td>"+item.res+"</td><td>"+item.status+"</td></tr>";
                            if (item.status == true) trueCounter++;
                            else falseCounter++;
                        });
                        table += "</tbody></table>";
                        $('.ResultsHere').html(table);

                        var allCounter = trueCounter + falseCounter;
                        $('.ResultsHere').append("<p>"+allCounter+" Results: "+trueCounter+" True, "+falseCounter+" False.</p>");

                        $("#preloaders").fadeOut(2000);
                    }, error: function(e){
                        $('.ResultsHere').html('<div class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + "No Result!!" + '</strong></div>');
                        $("#preloaders").fadeOut(2000);
                        console.log(e);
                    }
                });
            }
    </script>
@endsection
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
                <div class="card-header">Testing ({{App\NuveManager::getCurrentLanguageCode()}})</div>

                 <div class="card-body">
                    <form id="cekimlerForm">
                        @csrf
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <input type="button" class="btn btn-primary getResultBtn" style="width:100%" value="Generate" />
                        </div>
                    </div>

                </form>
                </div>
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
  
            $('.getResultBtn').click(function(){
                allCekimler();
            });
            
          
   
        });

var table = "";
var cekimMin = 0;
var cekimMax = 2000;
var trueCounter = 0;
var falseCounter = 0;
        function allCekimler(){
                $("#preloaders").fadeIn(100);
                $('#cekimlerContainer').html("");
                $('#testContainer').html("");
                
                if (cekimMin == 0) {
                    table = "<thead><th>ROOT</th><th>TEST RESULT</th></thead><tbody>";
                    trueCounter = 0;
                    falseCounter = 0;
                }
                $.ajax({
                    url:'{{route("nuve.testing")}}',
                    data: $("#cekimlerForm").serialize() + "&cekimMin="+cekimMin+ "&cekimMax="+cekimMax,
                    type: "post",
                    timeout:180000,
                    success: function(data){
                        console.log(data);
                        if (data == "no result"){
                            $('.ResultsHere').html('<div class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + "No Result!!" + '</strong></div>');    
                        $("#preloaders").fadeOut(2000);
                        return;
                        }
                        if (data == "finish"){
                            table += "</tbody>";
                        // $('.ResultsHere').html(table);
                        $('.ResultsHere').html('<p style="width:100%"><a href="#" class="headerButton downloadButton" onclick="downloadReport()" ><i class="fa fa-download" aria-hidden="true"></i>Download Report</a></p>');

                        var allCounter = trueCounter + falseCounter;
                        $('.ResultsHere').append("<p>"+allCounter+" Results: "+trueCounter+" True, "+falseCounter+" False.</p>");

                        cekimMin = 0;
                        cekimMax = 2000;
                        $("#preloaders").fadeOut(2000);
                            return;
                        }

                        jQuery.each(data, function(index, item){
                            table += "<tr><td>"+index+"</td><td>"+item+"</td></tr>";
                            if (item == "true") trueCounter++;
                            else falseCounter++;
                        });
                        cekimMin += 2001;
                        cekimMax += 2000;
                        var allCounter = trueCounter + falseCounter;
                        $('.ResultsHere').html("<p>"+allCounter+" Results: "+trueCounter+" True, "+falseCounter+" False.</p>");

                        allCekimler();
                    }, error: function(e){
                        $('.ResultsHere').html('<div class="col-md-12 p-3 mb-2 bg-danger text-white text-center"><strong>' + "No Result!!" + '</strong></div>');
                        $("#preloaders").fadeOut(2000);
                        console.log(e);
                    }
                });
            }

            
  function downloadReport() {
    var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head>';
    tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';

    tab_text = tab_text + '<x:Name>Testing</x:Name>';

    tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
    tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
                    
    tab_text = tab_text + "<table border='1px solid #555;'>";
    tab_text = tab_text + table;
    
    tab_text = tab_text + '</table></body></html>';

                    
    var data_type = 'data:application/vnd.ms-excel';
    
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    
        var d = new Date();
        var month = d.getMonth()+1;
        var day = d.getDate();
        var today =   (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
        
        var filename = 'testing.xls';
            var blob = new Blob([tab_text], {
                type: "application/csv;charset=utf-8;"
            });

            if (window.navigator.msSaveBlob) {
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                    navigator.msSaveBlob(blob, filename);
                }
            } else {
                var csvUrl = URL.createObjectURL(blob);
                $('.downloadButton').attr('href', csvUrl);
        //        $('#downloadButton').attr('href', data_type + ', ' + encodeURIComponent(tab_text));
                $('.downloadButton').attr('download', filename);
            }
        
}
    </script>
@endsection
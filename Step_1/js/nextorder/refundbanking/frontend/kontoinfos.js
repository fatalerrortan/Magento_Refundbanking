/** für Eingabe Kontoinfos*/




var $j = jQuery.noConflict();

 $j(document).ready(function(){

     $j("#rma_submit").click(function(){
         var postUrl = $j("#controllerUrl").val();
         var iban = $j("#iban").val();
         var bic = $j("#bic").val();

         var postData = new FormData();
         postData.append("iban", iban);
         postData.append("bic", bic);

         $j.ajax({
             type:"POST",
             url:postUrl,
             dataType:"text",
             contentType:false,
             cache:false,
             processData:false,
             data:postData,
             success:function(responseText){
                //alert(responseText);
             }
         });

     });

     var isConfig = $j("#isConfig").val();
     //alert(isConfig);
     if(isConfig != 0){

         $j("select[id^='resolution_id']").each(function(){

            $j(this).attr("onchange", "showKontoInfosTab(this)");
         });
     }
 });

    function showKontoInfosTab(tag){
        if(tag.value == 2) {
            $j("#kontoinfos").show();
        }
        else{
            $j("#kontoinfos").hide();
        }
}


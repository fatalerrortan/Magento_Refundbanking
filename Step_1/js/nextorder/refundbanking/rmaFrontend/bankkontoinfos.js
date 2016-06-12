

var $j = jQuery.noConflict();

 $j(document).ready(function(){

     $j("#bank-information").hide();
     $j("#rma-form-validate > div:nth-child(9)").hide();
     var orderPaymentCheck = $j("#orderPaymentCheck").val();

     $j("#rma_submit").click(function(){

         var postUrl = $j("#controllerUrl").val();
         var inhaber = $j("#kontoinhaber").val();
         var iban = $j("#iban").val();
         var bic = $j("#bic").val();

         var postData = new FormData();
         postData.append("inhaber", inhaber);
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

     $j("select[id^='resolution_id']").change(function () {
         if(this.value == 2 && orderPaymentCheck == 1){

             $j("#bank-information").show();
             $j("#rma-form-validate > div:nth-child(9)").show();
         }else {

             var valueArray = new Array();
             $j("select[id^='resolution_id']").each(function(){
                 valueArray.push($j(this).val());
             });
            // alert(valueArray);
             if($j.inArray('2', valueArray) == -1){

                 $j("#bank-information").hide();
                 $j("#rma-form-validate > div:nth-child(9)").hide();
             }
         }
     });
 });




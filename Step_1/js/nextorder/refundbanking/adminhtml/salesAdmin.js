var $j = jQuery.noConflict();

$j(document).ready(function(){

    var refundSumme = $j("#refundSumme").val();
    //alert(refundSumme);
    if(refundSumme == 0){
        $j("#sepa_download").hide();
    }
    //$j("#sepa_download").click(function(){
    //    var sepaLink = $j("#sepa_link").val();
    //   //alert(sepaLink);
    //    window.location.replace(sepaLink);
    //});
    $j("#sepa_download_credit").click(function(){
        var sepaLink = $j("#sepa_link_credit").val();
        window.location.replace(sepaLink);
    });
});
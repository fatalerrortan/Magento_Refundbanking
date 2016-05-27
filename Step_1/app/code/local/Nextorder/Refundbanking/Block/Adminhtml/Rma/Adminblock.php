<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 03/11/15
 * Time: 13:44
 */

class Nextorder_Refundbanking_Block_Adminhtml_Rma_Adminblock extends Mirasvit_Rma_Block_Adminhtml_Rma_Edit_Form{

    //public $refundSumme = 0;
    public function getKontoInfo(){

        $rma = Mage::registry('current_rma');
        $orderNr = $rma->getOrder()->getIncrementId();

        $orderInstanz = Mage::getModel('sales/order')->loadByIncrementId($orderNr);

       /*
        foreach($orderInstanz->getCollection() as $test){
            $testArray[] = $test;
        }
         */
        //$refundSumme = (float)$testArray[20]->getData('base_total_offline_refunded');
        $refundSumme = 0;
        $customerID =$orderInstanz->getData('customer_id');
        $iban_kunde = Mage::getModel('customer/customer')->load($customerID)->getData('iban');
        $bic_kunde = Mage::getModel('customer/customer')->load($customerID)->getData('bic');

        $payment_code = $orderInstanz->getPayment()->getMethodInstance()->getCode();
        $payment_title = $orderInstanz->getPayment()->getMethodInstance()->getTitle();

        $helper= Mage::helper("refundbanking/data");
        $kontoInfos_shop = $helper-> getKontonForRefund($payment_code);
        $iban_shop = $kontoInfos_shop['iban'];
        $bic_shop = $kontoInfos_shop['bic'];
        $refundCheck = 0;
        foreach (Mage::helper('rma')->getRmaItems($rma) as $item){
            $resolutionId = $item->getResolutionId();
            if($resolutionId == 2){
                $refundCheck++;
                //$this->refundSumme += (double)Mage::getModel('catalog/product')->loadByAttribute('sku', $item->getProductSku())->getPrice();
            }
        }
        if($refundCheck == 0){

            return "<h3>!Keine Forderung an Refund!</h3>";
        }

        $urlForXML = $helper->getSepaXML($iban_kunde, $bic_kunde, $iban_shop, $bic_shop, $orderNr, $refundSumme);

        return "Kundenkonto Iban: " . $iban_kunde. " und BIC: " . $bic_kunde . "<br/>

                Shopkonto Iban: " . $iban_shop . " und BIC: " . $bic_shop. "<br/>

                Payment:" . $payment_title . "<br/>

                Refund Summe ohne Transportskosten: ". $refundSumme." Euro <br/>

                <a href=".$urlForXML.">DOWNLOAD</a><br/>";

        //return $iban_kunde;


       // return $testArray;

    }
}
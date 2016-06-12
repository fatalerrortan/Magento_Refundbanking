<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 10/10/15
 * Time: 00:31
 */
class Nextorder_Refundbanking_Model_Observer{

    public function _beforeConfigSave(){

        $helper = Mage::helper('refundbanking/data');
        $falseConfigs = $helper->getFalseParams();
        if(count($falseConfigs) > 0){

            foreach($falseConfigs as $falseConfig){
                if(empty($falseConfig)){
                    continue;
                }
                Mage::getSingleton('adminhtml/session')->addError("ERROR: MEHR als ein Konto sind an ".$falseConfig." zugeweisen worden!");
                Mage::getSingleton('adminhtml/session')->addSuccess("!Bitte Stellen Sie ein bestimmtes Konto an ".$falseConfig." weiter ein!");
            }
        }
    }

    public function _afterCreditmemoSave(Varien_Event_Observer $event){

        $base_path = Mage::getBaseDir('base');
        $orgin_string = str_replace(PHP_EOL,'',file_get_contents($base_path."/app/code/local/Nextorder/Refundbanking/Helper/sepaCheck.txt"));
        if($orgin_string == 0){
            return true;
        }else{

        $incrementCreditID = $event->getEvent()->getDataObject()->getCreditmemo()->getIncrementId();
        if(file_exists($base_path . "/media/Sepa_Gutschrift/".$incrementCreditID.".xml")){
//            Mage::log( "it works!!!!!!!!!!!: test ", null, 'xulin.log');
            return true;
        }
        else{
            $adminUser = Mage::getSingleton('admin/session')->getUser()->getUsername();
//        $order = $event->getEvent()->getOrder();

        $Grand_Total = $event->getEvent()->getDataObject()->getCreditmemo()->getData('grand_total');
//        $Base_Total = $event->getEvent()->getDataObject()->getCreditmemo()->getData('base_grand_total');
//        Mage::log($event->getEvent()->getDataObject()->getCreditmemo()->getData() , null, 'xulin.log');
        $helper= Mage::helper("refundbanking/data");
        $orderNr = $event->getEvent()->getDataObject()->getCreditmemo()->getData('order_id');
        $order = Mage::getModel('sales/order')->load($orderNr);
        $payment_code = $order->getPayment()->getMethodInstance()->getCode();

            $paymentPools = array('ops_cc','paypal_billing_agreement','paypal_express');
//        Mage::log( "it works +1", null, 'xulin.log');

        if($helper->isConfig($payment_code)){

            $kontoInfos_shop = $helper->getKontonForRefund($payment_code);
            $customer = Mage::getModel('customer/customer')->load($order->getData('customer_id'));


            $urlForXML = $helper->getSepaXML($customer->getData('debit_payment_acount_name'), $customer->getId(), $customer->getData('debit_payment_account_iban'), $customer->getData('debit_payment_account_swift'),
                $kontoInfos_shop['inhaber'], $kontoInfos_shop['iban'], $kontoInfos_shop['bic'], $order->getIncrementId(), $incrementCreditID, $Grand_Total);
            Mage::getSingleton('adminhtml/session')->addSuccess("Sepa XML Gutschrit ist von User " . $adminUser . " erstellt.<a href='" . str_replace('index.php/', '', Mage::getUrl()) . "media/Sepa_Gutschrift/" . $incrementCreditID . ".xml' download> Zum Download </a>");
            $event->getEvent()->getDataObject()->getCreditmemo()->addComment("Sepa XML Gutschrit ist von User " . $adminUser . " erstellt.<a href='" . str_replace('index.php/', '', Mage::getUrl()) . "media/Sepa_Gutschrift/" . $incrementCreditID . ".xml' download> Zum Download </a>", true, true);
                }
            }
        }
    }
}
?>

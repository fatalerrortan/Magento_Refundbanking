<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 10/10/15
 * Time: 00:31
 */
class Nextorder_Refundbanking_Model_Observer{

//    protected $indexCount = 0;

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


//        $testVar = Mage::registry('test_var');
//        Mage::log( "new_VAR kommt: ". $testVar, null, 'xulin.log');
//        Mage::unregister('test_var');
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
//        $payment_title = $order->getPayment()->getMethodInstance()->getTitle();


//            $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_CLOSED);
//            $order->addStatusHistoryComment("My comment why the status was changed", Mage_Sales_Model_Order::STATE_CLOSED);
//            $order->setData('state', Mage_Sales_Model_Order::STATE_CLOSED);
//            $order->addStatusToHistory('closed', 'Put!!!!!!!!!!!!!!!!!!!!here', false);
//            $order->save();

            $paymentPools = array('ops_cc','paypal_billing_agreement','paypal_express');
//        Mage::log( "it works +1", null, 'xulin.log');

        if(!in_array($payment_code, $paymentPools)){

            $kontoInfos_shop = $helper->getKontonForRefund($payment_code);
            $customer = Mage::getModel('customer/customer')->load($order->getData('customer_id'));


            $urlForXML = $helper->getSepaXML($customer->getName(), $customer->getId(), $customer->getData('debit_payment_account_iban'), $customer->getData('debit_payment_account_swift'),
                $kontoInfos_shop['inhaber'], $kontoInfos_shop['iban'], $kontoInfos_shop['bic'], $order->getIncrementId(), $incrementCreditID, $Grand_Total);
            Mage::getSingleton('adminhtml/session')->addSuccess("Sepa XML Gutschrit ist von User " . $adminUser . " erstellt.<a href='" . str_replace('index.php/', '', Mage::getUrl()) . "media/Sepa_Gutschrift/" . $incrementCreditID . ".xml' download> Zum Download </a>");

//            $order = $event->getData();
//            Mage::log( "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!".$order, null, 'refund.log');


            $event->getEvent()->getDataObject()->getCreditmemo()->addComment("Sepa XML Gutschrit ist von User " . $adminUser . " erstellt.<a href='" . str_replace('index.php/', '', Mage::getUrl()) . "media/Sepa_Gutschrift/" . $incrementCreditID . ".xml' download> Zum Download </a>", true, true);
            }
//            return $this->_statusChange($orderNr);
        }

        }

    }

    public function _statusChange(){
//        $order = Mage::getModel('sales/order')->load($orderNr);
//        $order->addStatusToHistory(Mage_Sales_Model_Order::STATE_CLOSED)->save();
//        $order->addStatusToHistory('closed', 'Put your comment here', false);

        Mage::log( "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!12312312312", null, 'refund.log');
        return true;
    }

}
?>

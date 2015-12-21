<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 08/11/15
 * Time: 21:36
 */

class Nextorder_Refundbanking_Block_Adminhtml_Sales_Creditmemo extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Totals{

        public function getCreditMemo(){

            $_totals = $this->getTotals('footer');
            $refundSumme = (float)$_totals[2]->getData('value');
            $orderID = $this->getRequest()->getParam('order_id');
            $order = Mage::getModel('sales/order')->load($orderID);
            $orderInkreNr = $order->getIncrementId();
            $customerID = $order->getData('customer_id');
            $customer = Mage::getModel('customer/customer')->load($customerID);
            $customerName = $customer->getName();
            $iban_kunde = $customer->getData('iban');
            $bic_kunde = $customer->getData('bic');
            $payment_code = $order->getPayment()->getMethodInstance()->getCode();
            $payment_title = $order->getPayment()->getMethodInstance()->getTitle();
            $helper = Mage::helper("refundbanking/data");
            $kontoInfos_shop = $helper->getKontonForRefund($payment_code);
            $iban_shop = $kontoInfos_shop['iban'];
            $bic_shop = $kontoInfos_shop['bic'];
            $inhaber = $kontoInfos_shop['inhaber'];
            $helper= Mage::helper("refundbanking/data");
            $urlForXML = $helper->getSepaXML($customerName, $customerID, $iban_kunde, $bic_kunde, $inhaber,$iban_shop, $bic_shop, $orderInkreNr, $refundSumme);
//
//            if (empty($iban_kunde) || empty($bic_kunde)) {
//                return "Bitte fragen Sie Ihre kunden nach Kontoninfos für Refund!";
//            } elseif (empty($iban_shop) || empty($bic_shop)) {
//                return "Bitte weisen Sie der Zahlungsart '" . $payment_title . "' ein Konto für Refund zu!</br>
//                        Konfigurationsseite: Admin->System->Configuration->Nextorder Extension(Rückzahlungskonto für Zahlungsart)";
//            }
//            if($refundSumme == 0){
//                return "<input type='hidden' id='refundSumme' value='".$refundSumme."'>";
//            }
//            else {
//                return "<input type='hidden' id='sepa_link' value='".$urlForXML."'>";
//            }
        }

        public function forTest(){
//
//            $_totals = $this->getTotals('');
//            $refund_Sub = (float)$_totals[0]->value;
//            $refund_Shipping = (float)$_totals[1]->value;
//            $refund_Total = $refund_Sub+$refund_Shipping."<br/>";
//            echo "Refund Total: " . $refund_Total  ."<br/>";
//            echo "IncrementOrderId: " . $this->getOrder()->getData('increment_id')."<br/>";
//            echo "IncrementCreditMemoId: ".$this->getCreditmemo()->getData('increment_id');
        }
}
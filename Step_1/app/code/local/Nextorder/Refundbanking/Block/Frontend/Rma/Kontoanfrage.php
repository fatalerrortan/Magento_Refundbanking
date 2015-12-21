<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 26/10/15
 * Time: 16:04
 */
    class Nextorder_Refundbanking_Block_Frontend_Rma_Kontoanfrage extends  Mirasvit_Rma_Block_Rma_New
    {

        public function printKontoInfos(){

            $BestellID = Mage::app()->getRequest()->getParam('order_id');
            //$order = Mage::getModel('sales/order')->loadByIncrementId('100000234');
            if (!empty($BestellID)) {
                $order = Mage::getModel('sales/order')->load($BestellID);
                $orderCheck = $order->getPayment()->getMethodInstance();
                $payment = $orderCheck->getTitle();
                $payment_code = $orderCheck->getCode();
                $helper = Mage::helper('refundbanking/data');

                $resultTable = "<table border='2px'>
                                <tr>
                                    <td>Zahlunsart von Kunden</td>
                                    <td>in Admin bereits konfiguriert?</td>
                                 </tr>
                                 <tr>
                                     <td>" . $payment . "</td>
                                     <td>" . $helper->isConfig($payment_code)[0] . "</td>
                                 </tr>
                             </table>
                            ";

                return array($resultTable, $helper->isConfig($payment_code)[1]);
            }
        }

        public function getItemIdForJs(){


        }

    }
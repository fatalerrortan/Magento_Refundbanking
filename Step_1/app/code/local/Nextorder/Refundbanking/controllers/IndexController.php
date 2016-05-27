<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 07/10/15
 * Time: 14:04
 */
//require_once "Amasty/Customerattr/controllers/Adminhtml/ManageController.php";
    class Nextorder_Refundbanking_IndexController extends Mage_Core_Controller_Front_Action{

        public function indexAction(){

//                $customer = Mage::getModel('customer/customer')->load(93743);
//                echo $customer->getData('debit_payment_account_iban') ." und ".$customer->getData('debit_payment_account_swift');
           $test =  Mage::getModel('sales/order_status')->getResourceCollection()->getData();
            var_dump($test);
        }
    }
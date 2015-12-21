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

//            $this->loadLayout();
//            $this->renderLayout();

            // For Test

//            $helper = Mage::helper('refundbanking/data');
//            print_r($helper->ini_Sepa_XML());
            $incrementId = 100000002;
            $collection = Mage::getResourceModel('sales/order_creditmemo_collection')
                ->addFieldToFilter('order_id', 2); /* here we have no load memo by order id not increment id.Increment id is the creditmemo increment id not order increment id. */

            foreach($collection as $item) {

                $creditMessage = Mage::getResourceModel('sales/order_creditmemo_comment_collection')
                    ->addAttributeToFilter('parent_id', $item->getId()); /* here we need to use parent_id not entity_id.*/

            }
            var_dump($creditMessage->getData());
            exit();
        }
    }
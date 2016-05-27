<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 07/10/15
 * Time: 14:04
 */
//require_once "Amasty/Customerattr/controllers/Adminhtml/ManageController.php";
    class Nextorder_Refundbanking_IndexController extends Mage_Core_Controller_Front_Action
    {

        public function indexAction()
        {
            $base_path = Mage::getBaseDir('base');
             if(file_exists($base_path . "/media/Sepa_Gutschrift/Gutschrift-1026-15-401.xml")){
                echo "exist";
             }else{
                 echo "nothing";
             }
        }
    }
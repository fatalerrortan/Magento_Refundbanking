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
            $postData = $this->getRequest()->getPost('sepa');
            file_put_contents($base_path."/app/code/local/Nextorder/Refundbanking/Helper/sepaCheck.txt", $postData);
            echo "fertig";
        }

        public function index_1Action(){

            $jetzt = date("Y-m-d H:i:s");
            $ini_data = new DateTime($jetzt);
            $cdata = str_replace('+0000','',$ini_data->format(DateTime::ISO8601));
            $remove = strstr($ini_data->modify('+1 day')->format(DateTime::ISO8601),'T',true);

            echo str_replace('T','',strstr($ini_data->modify('+1 day')->format(DateTime::ISO8601),'T',true));
        }
    }
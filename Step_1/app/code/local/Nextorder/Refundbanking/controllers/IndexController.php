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
//            $jetzt = date("Y-m-d H:i:s");
//            $ini_data = new DateTime($jetzt);
//            $cdata = str_replace('+0000','',$ini_data->format(DateTime::ISO8601));
//            $defaultTermin = str_replace('+0000','', $ini_data->modify('+1 day')->format(DateTime::ISO8601));
//            echo $cdata.' und '.$defaultTermin;

            $string = 'DE 12312 123213 32435 123123 34535';
            echo str_replace(' ','',$string);
        }
    }
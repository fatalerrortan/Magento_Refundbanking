<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 02/11/15
 * Time: 12:35
 */
//require_once Mage::getModuleDir('controllers', "Amasty_Customerattr").DS."Adminhtml/ManageController.php";

class Nextorder_Refundbanking_AttrController extends Mage_Core_Controller_Front_Action{

        public function setkontoAction(){

            $kontoinfos = $this->getRequest()->getPost();
            if(!empty($kontoinfos)){

                $helper= Mage::helper('refundbanking/data');
                $helper->setKontoInfos($kontoinfos['inhaber'], str_replace(' ','',$kontoinfos['iban']), str_replace(' ','',$kontoinfos['bic']));
            }
        }
}
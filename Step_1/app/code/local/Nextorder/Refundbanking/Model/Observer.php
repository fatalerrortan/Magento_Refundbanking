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

            $incrementCreditID = $event->getEvent()->getDataObject()->getCreditmemo()->getIncrementId();
            Mage::getSingleton('adminhtml/session')->addSuccess("Sepa XML Gutschrift ist bereits unter ". Mage::getBaseDir('base')."/media/Sepa_Gtuschrift/Gutschrift_".$incrementCreditID.".xml erstellt");
            $adminUser = Mage::getSingleton('admin/session')->getUser()->getUsername();
//            $event->getEvent()->getDataObject()->getCreditmemo()->addComment("Sepa XML Gutschrit ist von User ".$adminUser." erstellt.<a href='".str_replace('index.php/','',Mage::getUrl())."media/Sepa_Gtuschrift/Gutschrift_".$incrementCreditID.".xml' download> Zum Download </a>" ,true,true);
            $event->getEvent()->getDataObject()->getCreditmemo()->addComment("Sepa XML Gutschrit ist von User ".$adminUser." erstellt.<a href='".Mage::getUrl()."media/Sepa_Gtuschrift/Gutschrift_".$incrementCreditID.".xml' download> Zum Download </a>" ,true,true);

        }
}
?>
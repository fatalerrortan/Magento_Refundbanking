<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 11/10/15
 * Time: 00:01
 */

    class Nextorder_Refundbanking_Model_Validate extends Mage_Core_Model_Config_Data{

        public $countArray = array();

        public function save(){

            $config_params = Mage::getStoreConfig('kontoconfigbutton/option/kontotable', Mage::app()->getStore());
            //'sectionName/groupName/fieldName'
            //$config_params = unserialize($config_params);
            //die(print_r($config_params));

            /*
            if (!$switch1) {
                Mage::getConfig()->saveConfig('kontoconfigbutton/kontoconfigtab/kontotable', true);
                //Mage::getConfig()->reinit();
                //Mage::app()->reinitStores();
            }
            */

            if ($config_params) {

                $config_params = unserialize($config_params);


                if (is_array($config_params)) {
                    foreach($config_params as $config_param) {
                        $zahlungsart = $config_param['zahlungsart'];
                        $iban = $config_param['iban'];
                        $bic = $config_param['bic'];
                        $einsatz = $config_param['einsatz'];

                        $this->countArray[] = $zahlungsart . "_" . $einsatz;
                    }
                    $cardinalityCheck = array_count_values($this->countArray);

                    if($cardinalityCheck['paypal_ja'] > 1){
                        Mage::getSingleton('adminhtml/session')->addError("ERROR: MEHR als ein Konton kann nur an Paypal zugeweisen werden!");
                        Mage::getSingleton('adminhtml/session')->addSuccess('!Bitte Stellen Sie ein bestimmtes Konto an Paypal weiter ein!');
                        //$helper = Mage::helper("kontoconfig/data");
                        //$helper->reloadPage();
                    }
                    if($cardinalityCheck['eckarte_ja'] > 1){
                        Mage::getSingleton('adminhtml/session')->addError("ERROR: MEHR als ein Konton kann an EC-Karte zugeweisen werden!");
                        Mage::getSingleton('adminhtml/session')->addSuccess('!Bitte Stellen Sie ein bestimmtes Konto an EC-Karte weiter ein!');
                    }
                    if($cardinalityCheck['kreditkarte_ja'] > 1){
                        Mage::getSingleton('adminhtml/session')->addError("ERROR: MEHR als ein Konton kann an Kreditkarte zugeweisen werden!");
                        Mage::getSingleton('adminhtml/session')->addSuccess('!Bitte Stellen Sie ein bestimmtes Konto an Kreditkarte weiter ein!');
                    }
                    if($cardinalityCheck['nokonto_ja']){
                        Mage::getSingleton('adminhtml/session')->addError("ERROR: Das gewählte Konto wird noch nicht an eine Zahlungsart zugewiesen");
                        Mage::getSingleton('adminhtml/session')->addSuccess('!Bitte Stellen Sie eine bestimmte Zahlungsart an das Konto weiter ein!');
                    }
                }
            }

           // return parent::save();

        }
    }
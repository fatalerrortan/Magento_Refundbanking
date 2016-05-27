<?php
//$ExternalLibPath=Mage::getModuleDir('', 'Nextorder_Refundbanking').DS.'Helper'.DS.'sepa_lib'.DS.'SEPA'.DS.'XMLGenerator.php';
//include_once($ExternalLibPath);
class Nextorder_Refundbanking_Helper_Data extends Mage_Core_Helper_Abstract{

		public $countArray = array('label' => '', 'einsatz' => '');

		public function getAllActivePaymentMethod(){

			$ActivePaymentMethods = Mage::getModel('payment/config')->getAllMethods();

			$paymentArray =array(array('label'=> '!Keine Zuweisung!', 'value'=>'nokonto'));

			foreach ($ActivePaymentMethods as $paymentCode=>$paymentModel) {

				$paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
				if(!empty($paymentTitle)) {

					$paymentArray[] = array(
						'label' => $paymentTitle,
						'value' => $paymentCode,
					);
				}
			}
			return $paymentArray;
		}
		/**
		 * @return array Collection for Config Params
		 */
		public function getAllConfigs(){
			$config_params = Mage::getStoreConfig('kontoconfigbutton/option/kontotable', Mage::app()->getStore());
			//'sectionName/groupName/fieldName
			if ($config_params) {
				$config_params = unserialize($config_params);
				if (is_array($config_params)){
					return $config_params;
				}
			}
		}

		public function getFalseParams(){
			$resultArray = array();
			//$leerInput = array();

			$config_params = $this->getAllConfigs();

			foreach($config_params as $config_param) {
						$zahlungsart = $config_param['zahlungsart'];
						$iban = $config_param['iban'];
						$bic = $config_param['bic'];
						$einsatz = $config_param['einsatz'];
						$inhaber = $config_param['inhaber'];

					if((empty($bic) && (string)$einsatz = 'ja') || (empty($iban) && (string)$einsatz = 'ja') || (empty($inhaber) && (string)$einsatz = 'ja')){

						$leerInput = Mage::getStoreConfig('payment/'. $zahlungsart .'/title');
						Mage::getSingleton('adminhtml/session')->addWarning("Bitte geben Sie eine vollkommene Kontoninfos für " .$leerInput. " an");
					}
					$this->countArray[] = $einsatz . "_" . $zahlungsart;
					}
					$cardinalityCheck = array_count_values($this->countArray);

					foreach($cardinalityCheck as $label => $cadri){

						if($cadri > 1 && preg_match('/ja_/',$label)){

							$resultArray[] = Mage::getStoreConfig('payment/'. substr($label, 3) .'/title');
						}
					}
					return $resultArray;

		}

		public function isConfig($payment_code){
			$config_array = array();
			$config_params = $this->getAllConfigs();
			foreach($config_params as $config_param){
				$einsatz = (string)$config_param['einsatz'];
				$zahlungsart = (string)$config_param['zahlungsart'];
				if($einsatz == 'ja'){
					$config_array[] = $zahlungsart;
				}
			}
			if(in_array($payment_code, $config_array)){

				return array("Die Zahlungsart der Kunden ist schon im Admin konfiguriert worden!", 1);
			}
			else{
				return array("Keiner Treffer im Admin!!!!", 0);
			}
		}

		public function setKontoInfos($iban, $bic){

			$customer_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
			$customer_data = Mage::getModel('customer/customer')->load($customer_id);
			$customer_data->setData('debit_payment_account_iban',$iban)->setData('debit_payment_account_swift',$bic)->save();
		}

		public function getKontonForRefund($payment_code){

			$config_params = $this->getAllConfigs();
			foreach($config_params as $config_param){
				$einsatz = (string)$config_param['einsatz'];
				$zahlungsart = (string)$config_param['zahlungsart'];
				if($einsatz == 'ja' && $zahlungsart == $payment_code){

					return array("iban" => $config_param['iban'],
									"bic" => $config_param['bic'],
									"inhaber" => $config_param['inhaber']
								);
				}
			}
		}

		public function getSepaXML($name_kunde, $kundennr,$iban_kunde, $bic_kunde, $name_shop, $iban_shop, $bic_shop, $orderNr,$creditmemoInkreID ,$refundSumme){

			$base_path = Mage::getBaseDir('base');
			if(!is_dir($base_path."/media/Sepa_Gutschrift")) {
				mkdir($base_path . "/media/Sepa_Gutschrift", 0777);
			}
			$sepaXML = $this->ini_Sepa_XML($name_kunde, $kundennr,$iban_kunde, $bic_kunde, $name_shop ,$iban_shop, $bic_shop, $orderNr, $refundSumme);
//			Mage::log("Nach Sepa Email", null, 'xulin.log');
			file_put_contents($base_path."/media/Sepa_Gutschrift/".$creditmemoInkreID.".xml", $sepaXML);
			return true;
		}

		public function ini_Sepa_XML($name_kunde, $kundennr, $iban_kunde, $bic_kunde, $name_shop, $iban_shop, $bic_shop, $orderNr, $refundSumme){
			$jetzt = date("Y-m-d H:i:s");
			//$defaultTermin = $datetime->modify('+1 day');
			$ini_data = new DateTime($jetzt);
			$cdata = $ini_data->format(DateTime::ISO8601);
			$defaultTermin = $ini_data->modify('+1 day')->format("Y-m-d");https://www.google.de/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#q=download+failed+forbidden++magento

			$xml = "<?xml version='1.0' encoding='iso-8859-1'?>
<Document xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='urn:iso:std:iso:20022:tech:xsd:pain.001.002.03 pain.001.002.03.xsd' xmlns='urn:iso:std:iso:20022:tech:xsd:pain.001.002.03'>
  <CstmrCdtTrfInitn>
    <GrpHdr>
      <MsgId>Message-ID-". $kundennr ."</MsgId>
      <CreDtTm>". $cdata ."</CreDtTm>
      <NbOfTxs>1</NbOfTxs>
      <CtrlSum>". $refundSumme ."</CtrlSum>
      <InitgPty>
        <Nm>". $name_shop ."</Nm>
      </InitgPty>
    </GrpHdr>
    <PmtInf>
      <PmtInfId>". $orderNr ."</PmtInfId>
      <PmtMtd>TRF</PmtMtd>
      <NbOfTxs>1</NbOfTxs>
      <CtrlSum>". $refundSumme ."</CtrlSum>
      <PmtTpInf>
        <SvcLvl>
          <Cd>SEPA</Cd>
        </SvcLvl>
      </PmtTpInf>
      <ReqdExctnDt>" .$defaultTermin. "</ReqdExctnDt>
      <Dbtr>
        <Nm>". $name_shop ."</Nm>
      </Dbtr>
      <DbtrAcct>
        <Id>
          <IBAN>". $iban_shop ."</IBAN>
        </Id>
      </DbtrAcct>
      <DbtrAgt>
        <FinInstnId>
          <BIC>". $bic_shop ."</BIC>
        </FinInstnId>
      </DbtrAgt>
      <ChrgBr>SLEV</ChrgBr>
      <CdtTrfTxInf>
        <PmtId>
          <EndToEndId>check_". $orderNr ."</EndToEndId>
        </PmtId>
        <Amt>
          <InstdAmt Ccy='EUR'>". $refundSumme ."</InstdAmt>
        </Amt>
        <CdtrAgt>
          <FinInstnId>
            <BIC>". $bic_kunde ."</BIC>
          </FinInstnId>
        </CdtrAgt>
        <Cdtr>
          <Nm>". $name_kunde ."</Nm>
        </Cdtr>
        <CdtrAcct>
          <Id>
            <IBAN>". $iban_kunde ."</IBAN>
          </Id>
        </CdtrAcct>
        <RmtInf>
          <Ustrd>Refund Fuer die Bestellung ". $orderNr ."</Ustrd>
        </RmtInf>
      </CdtTrfTxInf>
    </PmtInf>
  </CstmrCdtTrfInitn>
</Document>";
		return $xml;

		}

}


?>

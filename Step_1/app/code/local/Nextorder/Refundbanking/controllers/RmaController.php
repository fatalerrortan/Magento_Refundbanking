<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 26/10/15
 * Time: 14:36
 */

//require_once 'Mirasvit/Rma/controllers/RmaController.php';
require_once Mage::getModuleDir('controllers', "Mirasvit_Rma").DS."RmaController.php";
class Nextorder_Refundbanking_RmaController extends Mirasvit_Rma_RmaController {

    public function newAction(){

        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
        //echo "FOR TEST!!!!!!!!!!!!!!!!!!!";
    }
}
?>
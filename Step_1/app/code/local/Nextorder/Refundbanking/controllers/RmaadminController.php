<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 03/11/15
 * Time: 13:31
 */
//require 'app/code/local/Mirasvit/Rma/Block/Adminhtml/Rma/Edit/Renderer/Mfile.php';

class Nextorder_Refundbanking_RmaadminController extends Mirasvit_Rma_Block_Adminhtml_Rma_Edit_Form
{

    public function editAction()
    {
        $this->loadLayout();
        $this->renderLayout();
        echo "for Test!!!!!!!!!!!!!!";
    }
}
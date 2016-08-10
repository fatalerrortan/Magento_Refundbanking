<?php

class Nextorder_Refundbanking_Block_Adminhtml_Sales_Order_Creditmemo_Create_Items extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Create_Items
{

    public $customerCheck = '';

    protected function _prepareLayout(){

        $onclick = "submitAndReloadArea($('creditmemo_item_container'),'".$this->getUpdateUrl()."')";
        $this->setChild(
            'update_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                'label'     => Mage::helper('sales')->__('Update Qty\'s'),
                'class'     => 'update-button',
                'onclick'   => $onclick,
            ))
        );

        if ($this->getCreditmemo()->canRefund()) {
            if ($this->getCreditmemo()->getInvoice() && $this->getCreditmemo()->getInvoice()->getTransactionId()) {
                $this->setChild(
                    'submit_button',
                    $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                        'label'     => Mage::helper('sales')->__('Refund'),
                        'class'     => 'save submit-button',
                        'onclick'   => 'disableElements(\'submit-button\');submitCreditMemo()',
                    ))
                );
            }
            $this->setChild(
                'submit_offline',
                $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                    'label'     => Mage::helper('sales')->__('Refund Offline'),
                    'class'     => 'save submit-button',
                    'onclick'   => 'disableElements(\'submit-button\');submitCreditMemoOffline()',
                ))
            );

        }
        else {
            $this->setChild(
                'submit_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                    'label'     => Mage::helper('sales')->__('Refund Offline'),
                    'class'     => 'save submit-button',
                    'onclick'   => 'disableElements(\'submit-button\');submitCreditMemoOffline()',
                ))
            );
//            Sepa Button
            $order = $this->getOrder();
            $payment_code = $order->getPayment()->getMethodInstance()->getCode();
            $paymentPools = array('ops_cc','paypal_billing_agreement','paypal_express');

            if(Mage::helper("refundbanking/data")->isConfig($payment_code)){

                $customer = Mage::getModel('customer/customer')->load($this->getOrder()->getCustomerId());
                if((!empty($customer->getData('debit_payment_account_iban'))) && (!empty($customer->getData('debit_payment_account_swift')))){

                    $this->setChild(
                        'submit_button_sepa',
                        $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
                            'label'     => Mage::helper('sales')->__('Erstattung By SEPA'),
                            'class'     => 'save submit-button',
                            'onclick'   => 'disableElements(\'submit-button\');submitCreditMemoSepaOffline()',
                        ))
                    );

                }else{
                    Mage::getSingleton('adminhtml/session')->addWarning("Bitte Fragen Sie die Iban und Bic vom Kunden zur SEPA-Gutschrift nach！");
                    $this->customerCheck = 0;
                }
            }
        }

        return parent::_prepareLayout();
    }

    public function _checkCustomerData(){

        return $this->customerCheck;
    }
}

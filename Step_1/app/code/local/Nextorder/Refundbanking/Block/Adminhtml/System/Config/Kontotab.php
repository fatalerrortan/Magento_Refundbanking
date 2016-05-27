<?php
/**
 * Created by PhpStorm.
 * User: tiemanntan
 * Date: 08/10/15
 * Time: 11:47
 */
    class Nextorder_Refundbanking_Block_Adminhtml_System_Config_Kontotab
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract{

        protected $_itemRendererForZA;
        protected $_itemRendererForEinsatz;

        public function _prepareToRender()
        {
            $this->addColumn('zahlungsart', array(
                'label' => Mage::helper('refundbanking')->__('Zahlungsart'),
                'renderer' => $this->_getRendererForZA(),
                'style' => 'width:100px',
            ));
            $this->addColumn('iban', array(
                'label' => Mage::helper('refundbanking')->__('IBAN'),
                'style' => 'width:100px',
            ));
            $this->addColumn('bic', array(
                'label' => Mage::helper('refundbanking')->__('BIC'),
                'style' => 'width:100px',
            ));

            $this->addColumn('inhaber', array(
                'label' => Mage::helper('refundbanking')->__('Kontoinhaber(Name)'),
                'style' => 'width:100px',
            ));

            $this->addColumn('einsatz', array(
                'label' => Mage::helper('refundbanking')->__('Einsatz'),
                'renderer' => $this->_getRendererForEinsatz(),
            ));

            $this->_addAfter = false;
            $this->_addButtonLabel = Mage::helper('refundbanking')->__('hinzufügen');
        }
        protected function  _getRendererForZA(){

            if (!$this->_itemRendererForZA) {
                $this->_itemRendererForZA = $this->getLayout()->createBlock(
                    'refundbanking/adminhtml_system_config_zahlungsart', '',
                    array('is_render_to_js_template' => true)
                );
            }
            return $this->_itemRendererForZA;
        }
        protected function  _getRendererForEinsatz(){

            if (!$this->_itemRendererForEinsatz) {
                $this->_itemRendererForEinsatz = $this->getLayout()->createBlock(
                    'refundbanking/adminhtml_system_config_einsatz', '',
                    array('is_render_to_js_template' => true)
                );
            }
            return $this->_itemRendererForEinsatz;
        }
        protected function _prepareArrayRow(Varien_Object $row){

            $row->setData(
                'option_extra_attr_' . $this->_getRendererForZA()
                    ->calcOptionHash($row->getData('zahlungsart')),
                'selected="selected"'
            );

            $row->setData(
                'option_extra_attr_' . $this->_getRendererForEinsatz()
                    ->calcOptionHash($row->getData('einsatz')),
                'selected="selected"'
            );
        }
    }
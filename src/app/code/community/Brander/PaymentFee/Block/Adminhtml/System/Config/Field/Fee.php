<?php
/**
 * Created by Brander
 * Based on Module from "Magentix" (https://github.com/magentix/Fee)
 *
 * @category   Brander
 * @package    Brander_PaymentFee
 * @author     Roman Shopin (roman.s@devromans.com)
 * @license    This work is free software, you can redistribute it and/or modify it
 */
/**
 * Class Brander_PaymentFee_Block_Adminhtml_System_Config_Field_Fee
 */
class Brander_PaymentFee_Block_Adminhtml_System_Config_Field_Fee extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract {
    protected $_columns = array();
    protected $_typeRenderer;
    protected $_searchFieldRenderer;

    public function __construct() {
        parent::__construct();
    }

    protected function _prepareToRender() {
        $this->_typeRenderer        = NULL;
        $this->_searchFieldRenderer = NULL;
        $this->addColumn('payment_method', array('label' => Mage::helper('payment_fee')->__('Payment Method')));
        $this->addColumn('fee', array('label' => Mage::helper('payment_fee')->__('Fee')));
        $this->addColumn('description', array('label' => Mage::helper('payment_fee')->__('Description')));
        $this->_addAfter       = FALSE;
        $this->_addButtonLabel = Mage::helper('payment_fee')->__('Add Fee');
    }

    protected function _renderCellTemplate($columnName) {
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
        if ($columnName == "payment_method") {
            return $this->_getPaymentRenderer()
                   ->setName($inputName)
                   ->setTitle($columnName)
                   ->setExtraParams('style="width:260px"')
                   ->setClass('validate-select')
                   ->setOptions(Mage::getModel("adminhtml/system_config_source_payment_allowedmethods")->toOptionArray(NULL))
                   ->toHtml();
        } elseif ($columnName == "fee") {
            $this->_columns[$columnName]['class'] = 'input-text required-entry validate-number';
            $this->_columns[$columnName]['style'] = 'width:50px';
        }

        return parent::_renderCellTemplate($columnName);
    }

    protected function _getPaymentRenderer() {
        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock('payment_fee/adminhtml_system_config_render_select')->setIsRenderToJsTemplate(TRUE);
        }

        return $this->_typeRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row) {
        $row->setData('option_extra_attr_' . $this->_getPaymentRenderer()->calcOptionHash($row->getData('payment_method')), 'selected="selected"');
    }
}

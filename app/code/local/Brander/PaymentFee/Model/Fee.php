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
 * Class Brander_PaymentFee_Model_Fee
 */
class Brander_PaymentFee_Model_Fee extends Mage_Core_Model_Abstract {
    /**
     * Total Code
     */
    const TOTAL_CODE = 'fee';
    /**
     * @var array
     */
    public $methodFee = NULL;

    /**
     * Constructor
     */
    public function __construct() {
        $this->_getMethodFee();
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return array
     */
    protected function _getMethodFee() {
        if (is_null($this->methodFee)) {
            $this->methodFee = Mage::helper('payment_fee')->getFee();
        }

        return $this->methodFee;
    }

    /**
     * Check if fee can be apply
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return bool
     */
    public function canApply(Mage_Sales_Model_Quote_Address $address) {
        /* @var $helper Brander_PaymentFee_Helper_Data */
        $helper = Mage::helper('payment_fee');
        /* @var $quote Mage_Sales_Model_Quote */
        $quote = $address->getQuote();
        if ($helper->isEnabled()) {
            if ($method = $quote->getPayment()->getMethod()) {
                if (isset($this->methodFee[$method])) {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    /**
     * Calculate Payment Fee
     * @param Mage_Sales_Model_Quote_Address $address
     * @return float|int
     */
    public function getFee(Mage_Sales_Model_Quote_Address $address) {
        /* @var $helper Brander_PaymentFee_Helper_Data */
        $helper = Mage::helper('payment_fee');
        /* @var $quote Mage_Sales_Model_Quote */
        $quote   = $address->getQuote();
        $method  = $quote->getPayment()->getMethod();
        $fee     = $this->methodFee[$method]['fee'];
        $feeType = $helper->getFeeType();
        if ($feeType == Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED) {
            return $fee;
        } else {
            $totals = $quote->getTotals();
            $sum    = 0;
            foreach ($totals as $total) {
                if ($total->getCode() != self::TOTAL_CODE) {
                    $sum += (float)$total->getValue();
                }
            }

            return ($sum * ($fee / 100));
        }
    }

    /**
     * Retrieve Total Title from Store Config
     * @param string $method
     * @param Mage_Sales_Model_Quote $quote
     * @return string
     */
    public function getTotalTitle($method = '', Mage_Sales_Model_Quote $quote = null) {
        $title = '';
        if (!$method) {
            $method = $quote->getPayment()->getMethod();
        }
        if ($method) {
            if (isset($this->methodFee[$method]) && $this->methodFee[$method]['description']) {
                $title = $this->methodFee[$method]['description'];
            }
        }
        if (!$title) {
            /* @var $helper Brander_PaymentFee_Helper_Data */
            $helper = Mage::helper('payment_fee');
            $title  = $helper->__($helper->getConfig('default_description'));
        }

        return $title;
    }
}
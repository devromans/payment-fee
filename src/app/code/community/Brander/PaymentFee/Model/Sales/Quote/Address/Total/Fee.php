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
 * Class Brander_PaymentFee_Model_Sales_Quote_Address_Total_Fee
 */
class Brander_PaymentFee_Model_Sales_Quote_Address_Total_Fee extends Mage_Sales_Model_Quote_Address_Total_Abstract {
    /**
     * @var string
     */
    protected $_code = 'fee';

    /**
     * Collect fee address amount
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Brander_PaymentFee_Model_Sales_Quote_Address_Total_Fee
     */
    public function collect(Mage_Sales_Model_Quote_Address $address) {
        parent::collect($address);
        $this->_setAmount(0);
        $this->_setBaseAmount(0);
        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }
        /* @var $quote Mage_Sales_Model_Quote */
        $quote = $address->getQuote();
        /* @var $feeModel Brander_PaymentFee_Model_Fee */
        $feeModel = Mage::getModel('payment_fee/fee');
        if ($feeModel->canApply($address)) {
            $exist_amount = $quote->getFeeAmount();
            $fee          = $feeModel->getFee($address);
            $balance      = $fee - $exist_amount;
            $address->setFeeAmount($balance);
            $address->setBaseFeeAmount($balance);
            $quote->setFeeAmount($balance);
            $address->setGrandTotal($address->getGrandTotal() + $address->getFeeAmount());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseFeeAmount());
        }

        return $this;
    }

    /**
     * Add fee information to address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Brander_PaymentFee_Model_Sales_Quote_Address_Total_Fee
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $amount = Mage::helper('payment_fee')->getFee();
        $paymentMethod = $address->getQuote()->getPayment();

        if ($amount != 0 && $address->getAddressType() == 'shipping' && is_object($paymentMethod)) {    // billing & shipping address
            $title = Mage::getModel('payment_fee/fee')->getTotalTitle(null, $address->getQuote());

            $methodCode = $paymentMethod->getMethodInstance()->getCode();
            if (!isset($amount[$methodCode])) {
                return $this;
            }

            $address->addTotal(
                array(
                    'code' => $this->getCode(),
                    'title' => $amount[$methodCode]['description'],
                    'value' => $amount[$methodCode]['fee']
                )
            );
            return $this;
        }
    }
}

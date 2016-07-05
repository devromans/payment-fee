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
 * Class Brander_PaymentFee_Model_Sales_Order_Total_Invoice_Fee
 */
class Brander_PaymentFee_Model_Sales_Order_Total_Invoice_Fee extends Mage_Sales_Model_Order_Invoice_Total_Abstract {
    /**
     * Collect invoice total
     *
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @return Brander_PaymentFee_Model_Sales_Order_Total_Invoice_Fee
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
        $order = $invoice->getOrder();
        $feeAmountLeft     = $order->getFeeAmount() - $order->getFeeAmountInvoiced();
        $baseFeeAmountLeft = $order->getBaseFeeAmount() - $order->getBaseFeeAmountInvoiced();
        if (abs($baseFeeAmountLeft) < $invoice->getBaseGrandTotal()) {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $feeAmountLeft);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFeeAmountLeft);
        } else {
            $feeAmountLeft     = $invoice->getGrandTotal() * -1;
            $baseFeeAmountLeft = $invoice->getBaseGrandTotal() * -1;
            $invoice->setGrandTotal(0);
            $invoice->setBaseGrandTotal(0);
        }
        $invoice->setFeeAmount($feeAmountLeft);
        $invoice->setBaseFeeAmount($baseFeeAmountLeft);

        return $this;
    }
}

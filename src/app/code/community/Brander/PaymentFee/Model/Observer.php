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
 * Class Brander_PaymentFee_Model_Observer
 */
class Brander_PaymentFee_Model_Observer extends Mage_Core_Model_Abstract {
    /**
     * Set fee amount invoiced to the order
     *
     * @param Varien_Event_Observer $observer
     * @return Brander_PaymentFee_Model_Observer
     */
    public function invoiceSaveAfter(Varien_Event_Observer $observer) {
        $invoice = $observer->getEvent()->getInvoice();
        if ($invoice->getBaseFeeAmount()) {
            $order = $invoice->getOrder();
            $order->setFeeAmountInvoiced($order->getFeeAmountInvoiced() + $invoice->getFeeAmount());
            $order->setBaseFeeAmountInvoiced($order->getBaseFeeAmountInvoiced() + $invoice->getBaseFeeAmount());
        }

        return $this;
    }

    /**
     * Set fee amount refunded to the order
     *
     * @param Varien_Event_Observer $observer
     * @return Brander_PaymentFee_Model_Observer
     */
    public function creditmemoSaveAfter(Varien_Event_Observer $observer) {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        if ($creditmemo->getFeeAmount()) {
            $order = $creditmemo->getOrder();
            $order->setFeeAmountRefunded($order->getFeeAmountRefunded() + $creditmemo->getFeeAmount());
            $order->setBaseFeeAmountRefunded($order->getBaseFeeAmountRefunded() + $creditmemo->getBaseFeeAmount());
        }

        return $this;
    }

    /**
     * Update PayPal Total
     *
     * @param Varien_Event_Observer $observer
     * @return Brander_PaymentFee_Model_Observer
     */
    public function updatePaypalTotal(Varien_Event_Observer $observer) {
        $cart = $observer->getEvent()->getPaypalCart();
        $cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL, $cart->getSalesEntity()->getFeeAmount());

        return $this;
    }
}

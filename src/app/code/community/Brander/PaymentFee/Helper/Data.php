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
 * Class Brander_PaymentFee_Helper_Data
 */
class Brander_PaymentFee_Helper_Data extends Mage_Core_Helper_Abstract {
    /**
     *  Path to Store Config
     */
    const XML_PATH_SYSTEM_CONFIG = "payment_fee/payment_fee/";
    /**
     * @var array
     */
    public $fee = NULL;

    /**
     * Check if Extension is Enabled
     * @return bool
     */
    public function isEnabled() {
        return $this->getConfig('enabled');
    }

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '') {
        if ($field) {
            return Mage::getStoreConfig(self::XML_PATH_SYSTEM_CONFIG . $field, Mage::app()->getStore());
        }

        return NULL;
    }

    /**
     * Retrieve Fee type from Store config (Percent or Fixed)
     * @return string
     */
    public function getFeeType() {
        return $this->getConfig('fee_type');
    }

    /**
     * Retrieve and unserialize Payment Method and their Fees array from Store Config
     * @return array
     */
    public function getFee() {
        if (is_null($this->fee)) {
            $fees = (array)unserialize($this->getConfig('fee'));
            foreach ($fees as $fee) {
                $this->fee[$fee['payment_method']] = array(
                    'fee'         => $fee['fee'],
                    'description' => $fee['description']
                );
            }
        }

        return $this->fee;
    }
}

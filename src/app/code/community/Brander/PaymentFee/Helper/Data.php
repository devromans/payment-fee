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
     *
     * @param string $field
     * @param null|int $storeId
     * @return mixed|null
     */
    public function getConfig($field = '', $storeId=null) {
        if ($field) {
            if ($storeId ==null) {
                $storeId = $this->getCurrentStoreId();
            }
            return Mage::getStoreConfig(self::XML_PATH_SYSTEM_CONFIG . $field, $storeId);
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
     *
     * @param null|int $storeId
     * @return array
     */
    public function getFee($storeId = null) {
        if (is_null($this->fee)) {
            $fees = (array)unserialize($this->getConfig('fee', $storeId));
            foreach ((array)$fees as $fee) {
                $this->fee[$fee['payment_method']] = array(
                    'fee'         => $fee['fee'],
                    'description' => $fee['description']
                );
            }
        }

        return $this->fee;
    }

    /**
     * Get Current Store id
     * @return int
     */
    public function getCurrentStoreId()
    {
        $storeId = Mage::getSingleton('adminhtml/session_quote')->getStoreId();
        if ($storeId){ // if order is created from adminpanel
            return $storeId;
        }else{ // if order is created from frontend
            return Mage::app()->getStore();
        }
    }
}

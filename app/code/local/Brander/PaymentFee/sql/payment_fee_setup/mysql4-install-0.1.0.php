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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$orderTable        = $this->getTable('sales/order');
$invoiceTable      = $this->getTable('sales/invoice');
$creditmemoTable   = $this->getTable('sales/creditmemo');
$quoteAddressTable = $this->getTable('sales/quote_address');
$installer->getConnection()->addColumn($orderTable, 'fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($orderTable, 'base_fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($orderTable, 'fee_amount_refunded', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($orderTable, 'base_fee_amount_refunded', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($orderTable, 'fee_amount_invoiced', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($orderTable, 'base_fee_amount_invoiced', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($invoiceTable, 'fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($invoiceTable, 'base_fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($creditmemoTable, 'fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($creditmemoTable, 'base_fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($quoteAddressTable, 'fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->getConnection()->addColumn($quoteAddressTable, 'base_fee_amount', 'DECIMAL( 10, 2 ) NOT NULL');
$installer->endSetup();
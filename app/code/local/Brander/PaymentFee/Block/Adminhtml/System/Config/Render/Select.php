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
 * Class Brander_PaymentFee_Block_Adminhtml_System_Config_Render_Select
 */
class Brander_PaymentFee_Block_Adminhtml_System_Config_Render_Select extends Mage_Core_Block_Html_Select {
    public function _toHtml() {
        return trim(preg_replace('/\s+/', ' ', parent::_toHtml()));
    }
}

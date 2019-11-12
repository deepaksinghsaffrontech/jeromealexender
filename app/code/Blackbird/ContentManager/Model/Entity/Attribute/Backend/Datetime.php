<?php
/**
* Blackbird ContentManager Module
*
 * NOTICE OF LICENSE
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to contact@bird.eu so we can send you a copy immediately.
 *
 * @category        Blackbird
* @package         Blackbird_ContentManager
* @copyright       Copyright (c) 2018 Blackbird (https://black.bird.eu)
 * @author          Blackbird Team
* @license         https://www.advancedcontentmanager.com/license/
 */

namespace Blackbird\ContentManager\Model\Entity\Attribute\Backend;

/**
 * Class Datetime
 *
 * @package Blackbird\ContentManager\Model\Entity\Attribute\Backend
 */
class Datetime extends \Magento\Eav\Model\Entity\Attribute\Backend\Datetime
{
    /**
     * Formatting date value before save
     *
     * Should set (bool, string) correct type for empty value from html form,
     * necessary for further process, else date string
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     * @throws \Exception
     */
    public function beforeSave($object)
    {
        $attributeName = $this->getAttribute()->getName();
        $_formated = $object->getData($attributeName . '_is_formated');

        // We delete a language
        if (!$object->getData($attributeName)) {
            return $this;
        }

        if (!$_formated && $object->hasData($attributeName)) {
            try {
                // If date field is not required set the value to NULL instead of setting the date field to current timestamp
                if(!$object->getData($attributeName)) {
                    $value = null;
                } else {
                    $value = $this->_localeDate->date($object->getData($attributeName), null,false);
                    $value = date('Y-m-d g:i A', $value->getTimestamp());
                }
            } catch (\Exception $e) {
                throw new \Exception(__('Invalid date time'));
            }

            if (!$value) {
                $value = $object->getData($attributeName);
            }

            $object->setData($attributeName, $value);
            $object->setData($attributeName . '_is_formated', true);
        }

        return $this;
    }
}

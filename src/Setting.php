<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Data\KeyAwareTrait;
use Dhii\Type\TypeInterface;
use Dhii\Util\String\DescriptionAwareTrait;
use Dhii\Util\String\LabelAwareTrait;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Basic implementation of a setting.
 *
 * @since [*next-version*]
 */
class Setting implements SettingInterface
{
    /**
     * Provides key awareness.
     *
     * @since [*next-version*]
     */
    use KeyAwareTrait;

    /**
     * Provides label awareness.
     *
     * @since [*next-version*]
     */
    use LabelAwareTrait;

    /**
     * Provides description awareness.
     *
     * @since [*next-version*]
     */
    use DescriptionAwareTrait;

    /**
     * Provides type awareness, aliased for value type.
     *
     * @since [*next-version*]
     */
    use TypeAwareTrait {
        TypeAwareTrait::_getType as _getValueType;
        TypeAwareTrait::_setType as _setValueType;
    }

    /**
     * Provides setting type awareness.
     *
     * @since [*next-version*]
     */
    use SettingTypeAwareTrait;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $key         The key of the setting.
     * @param string|Stringable $label       The label for the setting.
     * @param string|Stringable $description The description for the setting.
     * @param TypeInterface     $valueType   The value type for the setting.
     * @param string|Stringable $settingType The setting type.
     */
    public function __construct($key, $label, $description, TypeInterface $valueType, $settingType)
    {
        $this->_setKey($key)
             ->_setLabel($label)
             ->_setDescription($description)
             ->_setValueType($valueType)
             ->_setSettingType($settingType);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getKey()
    {
        return $this->_getKey();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getLabel()
    {
        return $this->_getLabel();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getDescription()
    {
        return $this->_getDescription();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getValueType()
    {
        return $this->_getValueType();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getSettingType()
    {
        return $this->_getSettingType();
    }
}

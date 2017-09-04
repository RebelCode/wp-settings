<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Common functionality for things that are aware of a setting type.
 *
 * @since [*next-version*]
 */
trait SettingTypeAwareTrait
{
    /**
     * The setting type.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable
     */
    protected $settingType;

    /**
     * Retrieves the setting type.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable The setting type.
     */
    protected function _getSettingType()
    {
        return $this->settingType;
    }

    /**
     * Sets the setting type.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $settingType The setting type.
     *
     * @return $this
     */
    protected function _setSettingType($settingType)
    {
        $this->settingType = $settingType;

        return $this;
    }
}

<?php

namespace RebelCode\WordPress\Admin\Settings;

/**
 * Common functionality for things that are aware of a setting.
 *
 * @since [*next-version*]
 */
trait SettingAwareTrait
{
    /**
     * The setting.
     *
     * @since [*next-version*]
     *
     * @var SettingInterface
     */
    protected $setting;

    /**
     * Retrieves the setting.
     *
     * @since [*next-version*]
     *
     * @return SettingInterface The setting instance.
     */
    protected function _getSetting()
    {
        return $this->setting;
    }

    /**
     * Sets the setting.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface $setting The setting instance.
     *
     * @return $this
     */
    protected function _setSetting($setting)
    {
        $this->setting = $setting;

        return $this;
    }
}

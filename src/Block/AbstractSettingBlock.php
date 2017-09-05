<?php

namespace RebelCode\WordPress\Admin\Settings\Block;

use Dhii\Util\String\StringableInterface as Stringable;
use RebelCode\WordPress\Admin\Settings\SettingInterface;

/**
 * Abstract common functionality for blocks that render settings.
 *
 * @since [*next-version*]
 */
abstract class AbstractSettingBlock
{
    /**
     * Renders the block.
     *
     * @since [*next-version*]
     *
     * @return Stringable|string
     */
    protected function _render()
    {
        $setting = $this->_getSetting();
        $output  = $this->_renderSetting($setting);

        return $output;
    }

    /**
     * Retrieves the setting.
     *
     * @since [*next-version*]
     *
     * @return SettingInterface The setting instance.
     */
    abstract protected function _getSetting();

    /**
     * Renders a setting instance.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface $setting The setting to render.
     *
     * @return string|Stringable The output.
     */
    abstract protected function _renderSetting(SettingInterface $setting);
}

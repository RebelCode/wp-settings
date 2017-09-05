<?php

namespace RebelCode\WordPress\Admin\Settings\Block;

use Dhii\Output\ContextRendererInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use RebelCode\WordPress\Admin\Settings\SettingInterface;

/**
 * Abstract functionality for blocks that render a setting by delegating to a renderer.
 *
 * @since [*next-version*]
 */
abstract class AbstractDelegateSettingBlock extends AbstractSettingBlock
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _renderSetting(SettingInterface $setting)
    {
        $renderer = $this->_getRendererForSetting($setting);
        $output   = $renderer->render($setting);

        return $output;
    }

    /**
     * Retrieves the renderer for a setting.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface $setting The setting to retrieve a renderer for.
     *
     * @return ContextRendererInterface The renderer for the given setting.
     */
    abstract protected function _getRendererForSetting(SettingInterface $setting);
}

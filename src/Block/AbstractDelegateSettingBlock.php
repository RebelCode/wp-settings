<?php

namespace RebelCode\WordPress\Admin\Settings\Block;

use Dhii\Output\ContextRendererInterface;
use Dhii\Output\RendererInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use RebelCode\WordPress\Admin\Settings\SettingInterface;

/**
 * Abstract functionality for blocks that render a setting's field by delegating to a renderer.
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
        $fieldRenderer = $this->_getFieldRendererFor($setting);
        $output        = $this->_renderElement($setting, $fieldRenderer);

        return $output;
    }

    /**
     * Retrieves the field renderer for a setting.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface $setting The setting to retrieve a field renderer for.
     *
     * @return ContextRendererInterface The field renderer for the given setting.
     */
    abstract protected function _getFieldRendererFor(SettingInterface $setting);

    /**
     * Renders the full settings element using a given field renderer.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface         $setting       The setting to retrieve a field renderer for.
     * @param ContextRendererInterface $fieldRenderer The renderer that can render the field.
     *
     * @return string|Stringable The render output.
     */
    abstract protected function _renderElement(
        SettingInterface $setting,
        ContextRendererInterface $fieldRenderer
    );
}

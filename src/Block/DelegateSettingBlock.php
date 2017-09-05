<?php

namespace RebelCode\WordPress\Admin\Settings\Block;

use Dhii\Output\BlockInterface;
use Dhii\Output\ContextRendererInterface;
use RebelCode\WordPress\Admin\Settings\SettingAwareTrait;
use RebelCode\WordPress\Admin\Settings\SettingInterface;

/**
 * A simple implementation of a setting block that delegates to a single renderer.
 *
 * @since [*next-version*]
 */
class DelegateSettingBlock extends AbstractDelegateSettingBlock implements BlockInterface
{
    /**
     * Provides setting awareness.
     *
     * @since [*next-version*]
     */
    use SettingAwareTrait;

    /**
     * The delegate renderer instance.
     *
     * @since [*next-version*]
     *
     * @var ContextRendererInterface
     */
    protected $renderer;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface         $setting  The setting instance to render.
     * @param ContextRendererInterface $renderer The renderer instance to which rendering will be delegated.
     */
    public function __construct(SettingInterface $setting, ContextRendererInterface $renderer)
    {
        $this->_setSetting($setting)
             ->_setRenderer($renderer);
    }

    /**
     * Sets the renderer instance.
     *
     * @since [*next-version*]
     *
     * @param ContextRendererInterface $renderer The renderer instance.
     *
     * @return $this
     */
    protected function _setRenderer(ContextRendererInterface $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getRendererForSetting(SettingInterface $setting)
    {
        return $this->renderer;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function render()
    {
        return $this->_render();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function __toString()
    {
        return $this->_render();
    }
}

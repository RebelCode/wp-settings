<?php

namespace RebelCode\WordPress\Admin\Settings\Block;

use Dhii\Output\BlockInterface;
use RebelCode\WordPress\Admin\Settings\SettingAwareTrait;
use RebelCode\WordPress\Admin\Settings\SettingInterface;

/**
 * Base functionality for a setting block.
 *
 * This implementation renders a setting which it is aware of internally.
 *
 * @since [*next-version*]
 */
abstract class AbstractBaseSettingBlock extends AbstractSettingBlock implements BlockInterface
{
    /**
     * Provides setting awareness.
     *
     * @since [*next-version*]
     */
    use SettingAwareTrait;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface $setting The setting instance to render.
     */
    public function __construct(SettingInterface $setting)
    {
        $this->_setSetting($setting);
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

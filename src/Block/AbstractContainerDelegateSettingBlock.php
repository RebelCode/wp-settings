<?php

namespace RebelCode\WordPress\Admin\Settings\Block;

use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use RebelCode\WordPress\Admin\Settings\SettingInterface;

/**
 * Abstract functionality for setting blocks that uses a container to retrieve the setting instance and the
 * renderer to delegate rendering to.
 *
 * @since [*next-version*]
 */
abstract class AbstractContainerDelegateSettingBlock extends AbstractDelegateSettingBlock
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getSetting()
    {
        return $this->_getContainer()->get($this->_getSettingKey());
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getRendererForSetting(SettingInterface $setting)
    {
        return $this->_getContainer()->get($this->_getRendererKey($setting));
    }

    /**
     * Retrieves the container.
     *
     * @since [*next-version*]
     *
     * @return ContainerInterface The container instance.
     */
    abstract protected function _getContainer();

    /**
     * Retrieves the setting key.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable The setting key.
     */
    abstract protected function _getSettingKey();

    /**
     * Retrieves the renderer DI key for a setting.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface $setting The
     *
     * @return Stringable|string The renderer key.
     */
    abstract protected function _getRendererKey(SettingInterface $setting);
}

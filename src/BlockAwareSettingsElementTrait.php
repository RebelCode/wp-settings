<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Block\BlockInterface;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * A settings element that is aware of a block.
 *
 * @since [*next-version*]
 */
trait BlockAwareSettingsElementTrait
{
    /**
     * The block instance.
     *
     * @since [*next-version*]
     *
     * @var BlockInterface
     */
    protected $block;

    /**
     * Retrieves the block.
     *
     * @since [*next-version*]
     *
     * @return BlockInterface The block instance.
     */
    protected function _getBlock()
    {
        return $this->block;
    }

    /**
     * Sets the block.
     *
     * @since [*next-version*]
     *
     * @param BlockInterface $block The new block instance.
     *
     * @return $this
     */
    protected function _setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * Renders the settings element.
     *
     * @since [*next-version*]
     *
     * @param mixed $context Optional render context.
     *
     * @return Stringable|string The rendered output.
     */
    protected function _renderElement($context = null)
    {
        return $this->_getBlock()->render($context);
    }
}

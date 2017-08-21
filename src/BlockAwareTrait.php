<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Block\BlockInterface;

/**
 * Something that is aware of a block.
 *
 * @since [*next-version*]
 */
trait BlockAwareTrait
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
}

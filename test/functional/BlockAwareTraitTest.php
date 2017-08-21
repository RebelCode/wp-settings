<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use Dhii\Block\BlockInterface;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\BlockAwareTrait}.
 *
 * @since [*next-version*]
 */
class BlockAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\BlockAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Creates a block mock instance.
     *
     * @since [*next-version*]
     *
     * @return BlockInterface
     */
    public function createBlock()
    {
        $mock = $this->mock('Dhii\Block\BlockInterface')
            ->render()
            ->__toString();

        return $mock->new();
    }

    public function testGetSetBlock()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setBlock($block = $this->createBlock());

        $this->assertSame(
            $block,
            $reflect->_getBlock(),
            'The retrieved and set block instances are not the same.'
        );
    }
}

<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use Dhii\Block\BlockInterface;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\BlockAwareSettingsElementTrait}.
 *
 * @since [*next-version*]
 */
class BlockAwareSettingsElementTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\BlockAwareSettingsElementTrait';

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
     * @param string $output The mock output for the block.
     *
     * @return BlockInterface
     */
    public function createMockBlock($output = '')
    {
        $mock = $this->mock('Dhii\Block\BlockInterface')
            ->render($output)
            ->__toString($output);

        return $mock;
    }

    /**
     * Tests the block getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetBlock()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $block   = $this->createMockBlock()->new();

        $reflect->_setBlock($block);

        $this->assertSame(
            $block,
            $reflect->_getBlock(),
            'The retrieved and set block instances are not the same.'
        );
    }

    /**
     * Tests the render element method to ensure that the block's rendering is invoked.
     *
     * @since [*next-version*]
     */
    public function testRenderElement()
    {
        $context = 'some render context';

        // Expect block render methOd to be called
        $block = $this->createMockBlock()
            ->render([$this->equalTo($context)], $this->anything(), $this->once())
            ->new();

        // Mock block getter
        $subject = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME,
           [],
           '',
           false,
           true,
           true,
           [
               '_getBlock'
           ]
        );
        $subject->method('_getBlock')->willReturn($block);
        $reflect = $this->reflect($subject);

        $reflect->_renderElement($context);
    }
}

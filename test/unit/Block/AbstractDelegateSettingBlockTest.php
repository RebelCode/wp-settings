<?php

namespace RebelCode\WordPress\Admin\Settings\Block\UnitTest;

use Dhii\Output\RendererInterface;
use RebelCode\WordPress\Admin\Settings\Block\AbstractDelegateSettingBlock;
use RebelCode\WordPress\Admin\Settings\SettingInterface;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\Block\AbstractDelegateSettingBlock}.
 *
 * @since [*next-version*]
 */
class AbstractDelegateSettingBlockTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\Block\AbstractDelegateSettingBlock';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return AbstractDelegateSettingBlock
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->_getSetting()
                     ->_getRendererForSetting()
                     ->new();

        return $mock;
    }

    /**
     * Creates a new mocked setting instance.
     *
     * @since [*next-version*]
     *
     * @return SettingInterface
     */
    public function createSetting()
    {
        // Create mock
        $mock = $this->mock('RebelCode\WordPress\Admin\Settings\SettingInterface')
                     ->getKey()
                     ->getLabel()
                     ->getDescription()
                     ->getValueType()
                     ->getSettingType();

        return $mock->new();
    }

    /**
     * Creates a new mocked renderer instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string $output The render output of the renderer.
     *
     * @return RendererInterface The created renderer.
     */
    public function createRenderer($output = '')
    {
        $mock = $this->mock('Dhii\Output\RendererInterface')
                     ->render($output);

        return $mock->new();
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the render method to ensure that all appropriate internal methods are invoked.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        $reflect = $this->reflect($subject);

        $subject->expects($this->once())
                ->method('_getSetting')
                ->willReturn($setting = $this->createSetting());

        $subject->expects($this->once())
                ->method('_getRendererForSetting')
                ->with($setting)
                ->willReturn($renderer = $this->createRenderer());

        $reflect->_render();
    }

    /**
     * Tests the render method to ensure that the output is equivalent to the output of the delegate renderer.
     *
     * @since [*next-version*]
     */
    public function testRenderOutput()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        $reflect = $this->reflect($subject);
        $output  = uniqid('output-');

        $subject->method('_getSetting')->willReturn($setting = $this->createSetting());
        $subject->method('_getRendererForSetting')->willReturn($renderer = $this->createRenderer($output));

        $this->assertEquals($output, $reflect->_render(), 'Render output does not match expected output.');
    }
}

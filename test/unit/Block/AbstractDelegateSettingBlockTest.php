<?php

namespace RebelCode\WordPress\Admin\Settings\Block\UnitTest;

use Dhii\Output\ContextRendererInterface;
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
                     ->_renderElement()
                     ->_getFieldRenderer()
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
     * @return ContextRendererInterface The created renderer.
     */
    public function createContextRenderer($output = '')
    {
        $mock = $this->mock('Dhii\Output\ContextRendererInterface')
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
    public function testRenderSetting()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        $reflect = $this->reflect($subject);

        $setting  = $this->createSetting();
        $renderer = $this->createContextRenderer();

        $subject->expects($this->once())
                ->method('_getFieldRenderer')
                ->with($setting)
                ->willReturn($renderer);

        $subject->expects($this->once())
                ->method('_renderElement')
                ->with($setting, $renderer);

        $reflect->_renderSetting($setting);
    }

    /**
     * Tests the render method to ensure that the output is equivalent to that of the abstract _renderElement() method.
     *
     * @since [*next-version*]
     */
    public function testRenderOutput()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        $reflect = $this->reflect($subject);
        $output  = uniqid('output-');

        $setting  = $this->createSetting();
        $renderer = $this->createContextRenderer();

        $subject->expects($this->once())
                ->method('_getFieldRenderer')
                ->with($setting)
                ->willReturn($renderer);

        $subject->method('_renderElement')
                ->willReturn($output);

        $this->assertEquals(
            $output,
            $reflect->_renderSetting($setting),
            'Render output does not match expected output.'
        );
    }
}

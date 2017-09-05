<?php

namespace RebelCode\WordPress\Admin\Settings\Block\UnitTest;

use PHPUnit_Framework_MockObject_MockObject;
use Xpmock\TestCase;
use RebelCode\WordPress\Admin\Settings\Block\DelegateSettingBlock;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\Block\DelegateSettingBlock}.
 *
 * @since [*next-version*]
 */
class DelegateSettingBlockTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\Block\DelegateSettingBlock';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return DelegateSettingBlock
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->new();

        return $mock;
    }

    /**
     * Creates a new mocked setting instance.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createSetting()
    {
        return $this->getMockForAbstractClass('RebelCode\WordPress\Admin\Settings\SettingInterface');
    }

    /**
     * Creates a new mocked renderer instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string $output The render output of the renderer.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createContextRenderer($output = '')
    {
        return $this->getMockForAbstractClass('Dhii\Output\ContextRendererInterface');
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

        $this->assertInstanceOf(
            'Dhii\Output\BlockInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );

        $this->assertInstanceOf(
            'Dhii\Util\String\StringableInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );
    }

    /**
     * Tests the constructor and render method to ensure that the setting and renderer instances given to the
     * constructor are the same instances used for rendering, and that the output matches the renderer's output.
     *
     * @since [*next-version*]
     */
    public function testConstructorAndRender()
    {
        $subject = new DelegateSettingBlock(
            $setting = $this->createSetting(),
            $renderer = $this->createContextRenderer()
        );

        $renderer->expects($this->once())
                 ->method('render')
                 ->with($setting)
                 ->willReturn($output = uniqid('output-'));

        $this->assertEquals($output, $subject->render(), 'Render output is incorrect');
    }
}

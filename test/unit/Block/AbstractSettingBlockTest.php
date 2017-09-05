<?php

namespace RebelCode\WordPress\Admin\Settings\Block\UnitTest;

use RebelCode\WordPress\Admin\Settings\SettingInterface;
use Xpmock\TestCase;
use RebelCode\WordPress\Admin\Settings\Block\AbstractSettingBlock;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\Block\AbstractSettingBlock}.
 *
 * @since [*next-version*]
 */
class AbstractSettingBlockTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\Block\AbstractSettingBlock';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return AbstractSettingBlock
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->_getSetting()
            ->_renderSetting()
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
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME, $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the render method to ensure that the appropriate internal methods are invoked.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        $reflect = $this->reflect($subject);

        // Mock _getSetting return value
        $subject->method('_getSetting')->willReturn($setting = $this->createSetting());
        // Expect _renderSetting to be called once with the setting as argument
        $subject->expects($this->once())
                ->method('_renderSetting')
                ->with($setting)
                ->willReturn(null);

        $reflect->_render();
    }

    /**
     * Tests the render method to ensure that the result of the _renderSetting() method is returned as output.
     *
     * @since [*next-version*]
     */
    public function testRenderSetting()
    {
        $subject = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
        $reflect = $this->reflect($subject);

        // Mock _getSetting return value
        $subject->method('_getSetting')->willReturn($setting = $this->createSetting());
        // Mock _getSetting return value
        $subject->method('_renderSetting')->willReturn($output = uniqid('output-'));

        $this->assertEquals($output, $reflect->_render(), 'Render output is incorrect');
    }
}

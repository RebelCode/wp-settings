<?php

namespace RebelCode\WordPress\Admin\Settings\Block\UnitTest;

use Dhii\Output\ContextRendererInterface;
use RebelCode\WordPress\Admin\Settings\SettingInterface;
use Xpmock\TestCase;
use RebelCode\WordPress\Admin\Settings\Block\AbstractDelegateSettingBlock;

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
        return $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
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
        $mock = $this->mock('RebelCode\WordPress\Admin\Settings\SettingInterface')
                     ->getKey()
                     ->getLabel()
                     ->getDescription()
                     ->getValueType()
                     ->getSettingType();

        return $mock->new();
    }

    /**
     * Creates a new mocked context renderer.
     *
     * @since [*next-version*]
     *
     * @return ContextRendererInterface
     */
    public function createContextRenderer()
    {
        // Create mock
        $mock = $this->mock('Dhii\Output\ContextRendererInterface')
                     ->render();

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
     * Tests the render setting method to ensure that all the appropriate internal methods are invoked
     * and that the output matches the return of _renderElement().
     *
     * @since [*next-version*]
     *
     */
    public function testRenderSetting()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $setting  = $this->createSetting();
        $renderer = $this->createContextRenderer();
        $expected = uniqid('output-');

        $subject->expects($this->once())
                ->method('_getFieldRendererFor')
                ->with($setting)
                ->willReturn($renderer);

        $subject->expects($this->once())
            ->method('_renderElement')
            ->with($setting, $renderer)
            ->willReturn($expected);

        $this->assertEquals(
            $expected,
            $reflect->_renderSetting($setting),
            'Expected and rendered output do not match'
        );
    }
}

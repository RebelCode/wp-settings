<?php

namespace RebelCode\WordPress\Admin\Settings\Block\UnitTest;

use PHPUnit_Framework_MockObject_MockObject;
use RebelCode\WordPress\Admin\Settings\SettingInterface;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractContainerDelegateSettingBlockTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\Block\AbstractContainerDelegateSettingBlock';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance()
    {
        return $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
    }

    /**
     * Creates a mocked container instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createContainer()
    {
        return $this->getMockForAbstractClass('Psr\Container\ContainerInterface');
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
     * Tests the setting getter method to ensure all the appropriate internal methods are invoked and that the
     * retrieved setting instance comes from the container.
     *
     * @since [*next-version*]
     */
    public function testGetSetting()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $settingKey = uniqid('setting-');
        $setting    = $this->createSetting();
        $container  = $this->createContainer();

        // Expect _getSettingKey to be called once with a mocked setting key return value
        $subject->expects($this->once())
                ->method('_getSettingKey')
                ->willReturn($settingKey);
        // Expect _getContainer to be invoked once with a mocked container return value
        $subject->expects($this->once())
                ->method('_getContainer')
                ->willReturn($container = $this->createContainer());
        // Expect the container's get() method to be called once with a mocked return value
        $container->expects($this->once())
                  ->method('get')
                  ->with($settingKey)
                  ->willReturn($setting);

        $this->assertSame(
            $setting,
            $reflect->_getSetting(),
            'Retrieved setting is not the same instance retrieved from container'
        );
    }

    /**
     * Tests the renderer getter method to ensure all the appropriate internal methods are invoked and that the
     * retrieved renderer instance comes from the container.
     *
     * @since [*next-version*]
     */
    public function testGetRendererForSetting()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $setting     = $this->createSetting();
        $rendererKey = uniqid('renderer-');
        $renderer    = $this->createRenderer();
        $container   = $this->createContainer();

        // Expect _getRendererKey to be called once with a mocked renderer key return value
        $subject->expects($this->once())
                ->method('_getRendererKey')
                ->willReturn($rendererKey);
        // Expect _getContainer to be invoked once with a mocked container return value
        $subject->expects($this->once())
                ->method('_getContainer')
                ->willReturn($container = $this->createContainer());
        // Expect the container's get() method to be called once with a mocked return value
        $container->expects($this->once())
                  ->method('get')
                  ->with($rendererKey)
                  ->willReturn($renderer);

        $this->assertSame(
            $renderer,
            $reflect->_getRendererForSetting($setting),
            'Retrieved renderer is not the same instance retrieved from container'
        );
    }
}

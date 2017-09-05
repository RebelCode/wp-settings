<?php

namespace RebelCode\WordPress\Admin\Settings\Block\UnitTest;

use RebelCode\WordPress\Admin\Settings\SettingInterface;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\Block\AbstractBaseSettingBlock}.
 *
 * @since [*next-version*]
 */
class AbstractBaseSettingBlockTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\Block\AbstractBaseSettingBlock';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param SettingInterface $setting The setting to pass to the constructor.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance($setting = null)
    {
        $args = ($setting === null)
            ? []
            : [$setting];

        return $this->getMockForAbstractClass(
            static::TEST_SUBJECT_CLASSNAME,
            $args,
            '',
            count($args) > 0
        );
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
     * Tests the constructor to ensure that the setting given at construction time is the same instance as the setting
     * used during rendering.
     *
     * @since [*next-version*]
     */
    public function testConstructor()
    {
        $setting = $this->createSetting();
        $subject = $this->createInstance($setting);
        $reflect = $this->reflect($subject);

        $subject->expects($this->once())
                ->method('_renderSetting')
                ->with($setting);

        $subject->render();
    }
}

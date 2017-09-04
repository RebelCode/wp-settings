<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use RebelCode\WordPress\Admin\Settings\SettingInterface;
use Xpmock\TestCase;
use RebelCode\WordPress\Admin\Settings\SettingAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SettingAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\SettingAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

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

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the setting type getter and setter methods to ensure correct value assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetSetting()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $setting = $this->createSetting();

        $reflect->_setSetting($setting);

        $this->assertEquals(
            $setting,
            $reflect->_getSetting(),
            'Set and retrieved setting instances are not the same.'
        );
    }
}

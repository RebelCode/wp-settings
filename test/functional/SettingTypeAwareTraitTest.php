<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use Xpmock\TestCase;
use RebelCode\WordPress\Admin\Settings\SettingTypeAwareTrait;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\SettingTypeAwareTrait}.
 *
 * @since [*next-version*]
 */
class SettingTypeAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\SettingTypeAwareTrait';

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
    public function testGetSetSettingType()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $settingType = uniqid('setting-type-');

        $reflect->_setSettingType($settingType);

        $this->assertEquals(
            $settingType,
            $reflect->_getSettingType(),
            'Set and retrieved setting type are not the same.'
        );
    }
}

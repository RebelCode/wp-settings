<?php

namespace RebelCode\WordPress\Admin\UnitTest;

use Dhii\Type\TypeInterface;
use RebelCode\WordPress\Admin\Settings\Setting as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings}.
 *
 * @since [*next-version*]
 */
class SettingTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\Setting';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->new();

        return $mock;
    }

    /**
     * Creates a mocked type instance.
     *
     * @since [*next-version*]
     *
     * @return TypeInterface
     */
    public function createType()
    {
        $mock = $this->mock('Dhii\Type\TypeInterface')
                     ->validate();

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

        $this->assertInstanceOf(
            'RebelCode\WordPress\Admin\Settings\SettingInterface',
            $subject,
            'Test subject does not implement expected interface.'
        );
    }

    public function testConstructorAndGetters()
    {
        $subject = new TestSubject(
            $key = 'my-key',
            $label = 'My Label',
            $desc = 'My setting description text',
            $valueType = $this->createType(),
            $settingType = 'my-setting-type'
        );

        $this->assertSame($key, $subject->getKey(), 'Set the retrieved key are not the same.');
        $this->assertSame($label, $subject->getLabel(), 'Set the retrieved label are not the same.');
        $this->assertSame($desc, $subject->getDescription(), 'Set the retrieved description are not the same.');
        $this->assertSame($valueType, $subject->getValueType(), 'Set the retrieved value type are not the same.');
        $this->assertSame($settingType, $subject->getSettingType(), 'Set the retrieved setting type are not the same.');
    }
}

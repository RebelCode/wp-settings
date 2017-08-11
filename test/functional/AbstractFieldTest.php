<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use Exception;
use RebelCode\WordPress\Admin\Settings\AbstractField;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\AbstractField}.
 *
 * @since [*next-version*]
 */
class AbstractFieldTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\AbstractField';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param string $render The field render output.
     * @param array  $errors The validation errors.
     *
     * @return AbstractField
     */
    public function createInstance($render = '', $errors = [])
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->_renderField($render)
                     ->_getValidationErrors($errors)
                     ->_createCouldNotRenderException(function() {
                         return new Exception();
                     });

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
            'Subject is not a valid instance'
        );
    }

    /**
     * Tests the render method to ensure that all the important information is included in the output.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $subject = $this->createInstance($field = '<p>rendered field</p>', []);
        $reflect = $this->reflect($subject);

        $reflect->_setKey($key = 'test-field');
        $reflect->_setLabel($label = 'My Testing Field');
        $reflect->_setDescription($desc = "This is a field used in a test. It's mine, hence it's a mine field.");

        $rendered = $reflect->_render();

        $this->assertContains($key, $rendered);
        $this->assertContains($label, $rendered);
        $this->assertContains($desc, $rendered);
        $this->assertContains($field, $rendered);
    }

    /**
     * Tests the render method with validation errors to ensure that an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testRenderFail()
    {
        $subject = $this->createInstance($field = '<p>rendered field</p>', [
            'Some error',
            'Because something always goes wrong'
        ]);
        $reflect = $this->reflect($subject);

        $this->setExpectedException('\Exception');

        $reflect->_render();
    }
}

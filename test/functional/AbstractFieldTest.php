<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use Dhii\Validation\Exception\ValidationFailedException;
use Exception;
use RebelCode\WordPress\Admin\Settings\AbstractField;
use Xpmock\MockWriter;
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
     * @return MockWriter
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->_renderField()
                     ->_getValidationErrors()
                     ->_createCouldNotRenderException()
                     ->_createValidationException()
                     ->_createValidationFailedException();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance()->new();

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
        $mock = $this->createInstance();
        // Expect field render method to be called once
        $mock->_renderField($this->once());

        $subject = $mock->new();
        $reflect = $this->reflect($subject);

        $reflect->_setKey($key = 'test-field');
        $reflect->_setLabel($label = 'My Testing Field');
        $reflect->_setDescription($desc = "This is a field used in a test. It's mine, hence it's a mine field.");

        $rendered = $reflect->_render();

        $this->assertContains($key, $rendered);
        $this->assertContains($label, $rendered);
        $this->assertContains($desc, $rendered);
    }

    /**
     * Tests the render method with a render context value to assert whether the context is received.
     *
     * @since [*next-version*]
     */
    public function testRenderWithContext()
    {
        $ctxVal  = 'some render context value';
        $mock = $this->createInstance();
        // Expect field render method to be called once with the context value
        $mock->_renderField([$this->identicalTo($ctxVal)], $this->anything(), $this->once());

        $subject = $mock->new();
        $reflect = $this->reflect($subject);

        $reflect->_render($ctxVal);
    }

    /**
     * Tests the render method with validation errors to ensure that an exception is created.
     *
     * @since [*next-version*]
     */
    public function testRenderFail()
    {
        $ctx      = 'some context value';
        $vMessage = 'Validation of render context failed. This is expected since it is the behavior under test';
        $rMessage = 'Failed to render the test subject. This is expected since it is the behaviour under test.';

        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->_renderField()
                     ->_createValidationException()
                     // Expect the validation errors getter method to be called with the context as argument, and
                     // mock the return value to simulate validation errors
                     ->_getValidationErrors(
                         [$ctx],
                         $this->returnValue(['some', 'validation', 'errors']),
                         $this->once()
                     )
                     // Expect the validation failure exception factory to be called. Mock the return value to
                     // simulate validation errors.
                     ->_createValidationFailedException(
                         [$this->anything()],
                         $this->returnValue(new ValidationFailedException($vMessage)),
                         $this->once()
                     )
                     ->_createCouldNotRenderException(
                         [$this->anything()],
                         $this->returnValue(new Exception($rMessage)),
                         $this->any()
                     );

        // Expect the RENDER exception to be thrown (not the validation exception)
        $this->setExpectedException('\Exception', $rMessage);

        $subject = $mock->new();
        $reflect = $this->reflect($subject);

        $reflect->_render($ctx);
    }
}

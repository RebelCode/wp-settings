<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use ReflectionClass;
use Xpmock\MockWriter;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\AbstractSection}.
 *
 * @since [*next-version*]
 */
class AbstractSectionTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\AbstractSection';

    /**
     * Creates a new mock of the test subject.
     *
     * @since [*next-version*]
     *
     * @param string     $key    The section's key.
     * @param string     $label  The section's label.
     * @param array|null $fields The fields for this section.
     *
     * @return MockWriter
     */
    public function createInstance($key = '', $label = '', $fields = null)
    {
        $mock  = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                      ->_getKey($key)
                      ->_getLabel($label)
                      ->_createCouldNotRenderException()
                      ->_createValidationException()
                      ->_createValidationFailedException();

        if (is_array($fields)) {
            $mock->_getFields($fields);
        }

        return $mock;
    }

    /**
     * Creates a field mock instance.
     *
     * @since [*next-version*]
     *
     * @param string $key   The key of the field.
     * @param string $label The label of the field.
     *
     * @return MockWriter
     */
    public function createField($key, $label)
    {
        $mock = $this->mock('RebelCode\WordPress\Admin\Settings\FieldInterface')
                     ->getKey($key)
                     ->getLabel($label)
                     ->getDescription()
                     ->validate()
                     ->render();

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
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'Subject is not a valid instance'
        );
    }

    /**
     * Tests the fields setter methods to ensure that the method that adds fields is called.
     *
     * @since [*next-version*]
     */
    public function testSetFields()
    {
        $field1 = $this->createField('first', 'First field')->new();
        $field2 = $this->createField('second', 'Second field')->new();
        $fields = [$field1, $field2];

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods(
                         [
                             '_addField',
                             '_createValidationException',
                             '_createValidationFailedException',
                             '_createCouldNotRenderException',
                         ]
                     )
                     ->getMock();

        $mock->expects($this->exactly(2))
             ->method('_addField')
             ->withConsecutive([$this->identicalTo($field1)], [$this->identicalTo($field2)]);

        $reflect   = new ReflectionClass(static::TEST_SUBJECT_CLASSNAME);
        $refMethod = $reflect->getMethod('_setFields');
        $refMethod->setAccessible(true);

        $refMethod->invokeArgs($mock, [$fields]);
    }

    /**
     * Tests the field addition and getter methods to ensure that fields are added and retrieved correctly.
     *
     * @since [*next-version*]
     */
    public function testGetAddField()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $field1 = $this->createField('first', 'First field')->new();
        $field2 = $this->createField('second', 'Second field')->new();

        $reflect->_addField($field1);
        $this->assertContains($field1, $reflect->_getFields(), 'Field 1 was not added to the section.');

        $reflect->_addField($field2);
        $this->assertContains($field2, $reflect->_getFields(), 'Field 2 was not added to the section.');
    }

    /**
     * Tests the render method to ensure that the rendered output contains all of the required content and that the
     * fields' render methods are called.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $field1 = $this->createField('first', 'First field')
                       ->render($this->once())// Expect field 1 render method to be called
                       ->new();
        $field2 = $this->createField('second', 'Second field')
                       ->render($this->once())// Expect field 2 render method to be called
                       ->new();

        $key     = 'section-test-key';
        $label   = 'Section Test Label';
        $subject = $this->createInstance($key, $label, [$field1, $field2])->new();

        $reflect  = $this->reflect($subject);
        $rendered = $reflect->_render();

        $this->assertContains($key, $rendered, 'Render does not contain the section key.');
        $this->assertContains($label, $rendered, 'Render does not contain the section label.');
    }

    /**
     * Tests the render method with a context to assert whether fields receive the context value or whether an
     * exception is thrown if the context value is not provided by the context.
     *
     * @since [*next-version*]
     */
    public function testRenderWithContext()
    {
        $ctxVal1 = 'some value for first field';
        $ctxVal2 = 'some value for second field';

        $field1 = $this->createField('first', 'First field')
                       ->render([$ctxVal1], $this->anything(), $this->once())
                       ->new();
        $field2 = $this->createField('second', 'Second field')
                       ->render([$ctxVal2], $this->anything(), $this->once())
                       ->new();

        $key     = 'section-test-key';
        $label   = 'Section Test Label';
        $subject = $this->createInstance($key, $label, [$field1, $field2])->new();

        // Create context mock
        $ctxMock = $this->getMockBuilder('Dhii\Data\Container\ContainerInterface')
                        ->setMethods(['get', 'has'])
                        ->getMock();
        // Expect get() to be called first time with field 1 key and return the value
        $ctxMock->expects($this->at(0))
                ->method('get')
                ->with($this->identicalTo($field1->getKey()))
                ->willReturn($ctxVal1);
        // Expect get() to be called second time with field 2 key and return the value
        $ctxMock->expects($this->at(1))
                ->method('get')
                ->with($this->identicalTo($field2->getKey()))
                ->willReturn($ctxVal2);

        $this->reflect($subject)->_render($ctxMock);
    }
}

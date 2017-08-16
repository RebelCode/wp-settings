<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use RebelCode\WordPress\Admin\Settings\FieldInterface;
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
     * @return MockWriter
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->_createCouldNotRenderException();

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
     * @return FieldInterface
     */
    public function createField($key, $label)
    {
        $mock = $this->mock('RebelCode\WordPress\Admin\Settings\FieldInterface')
                     ->getLabel($label)
                     ->getDescription()
                     ->getKey($key)
                     ->validate()
                     ->render(sprintf('%1%s = $2%s', $key, $label));

        return $mock->new();
    }

    /**
     * Creates a context data container mock instance.
     *
     * @since [*next-version*]
     *
     * @param array $values The values.
     *
     * @return FieldInterface
     */
    public function createContext(array $values)
    {
        $mock = $this->mock('Dhii\Data\Container\ContainerInterface')
                     ->has(
                         function($key) use ($values) {
                             return isset($values[$key]);
                         }
                     )
                     ->get(
                         function($key) use ($values) {
                             if (isset($values[$key])) {
                                 return $values[$key];
                             }
                             throw $this->mock('Psr\Container\NotFoundExceptionInterface')
                                        ->new();
                         }
                     );

        return $mock->new();
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance()
                        ->new();

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
        $mock = $this->getMockForAbstractClass(
            static::TEST_SUBJECT_CLASSNAME,
            [],
            'AbstractSection',
            false,
            false,
            true,
            [
                '_createCouldNotRenderException',
                '_addField',
            ],
            false
        );

        $field1 = $this->createField('first', 'First field');
        $field2 = $this->createField('second', 'Second field');

        $mock
            ->expects($this->exactly(2))
            ->method('_addField')
            ->withConsecutive([$field1], [$field2]);

        $this->reflect($mock)
             ->_setFields([$field1, $field2]);
    }

    /**
     * Tests the field addition method to ensure that fields are added.
     *
     * @since [*next-version*]
     */
    public function testAddField()
    {
        $subject = $this->createInstance()
                        ->new();
        $reflect = $this->reflect($subject);

        $field1 = $this->createField('first', 'First field');
        $field2 = $this->createField('second', 'Second field');

        $reflect->_addField($field1);
        $this->assertContains($field1, $reflect->fields);

        $reflect->_addField($field2);
        $this->assertContains($field2, $reflect->fields);
    }

    /**
     * Tests the fields getter method to ensure that the fields are correctly retrieved.
     *
     * @since [*next-version*]
     */
    public function testGetFields()
    {
        $subject = $this->createInstance()
                        ->new();
        $reflect = $this->reflect($subject);

        $reflect->fields = [
            $field1 = $this->createField('first', 'First field'),
            $field2 = $this->createField('second', 'Second field'),
        ];

        $expected = [$field1, $field2];

        $this->assertEquals($expected, $reflect->_getFields(), '', 0.0, 10, true);
    }

    /**
     * Tests the render method to ensure that the rendered output contains all of the required content.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $mock   = $this->createInstance();
        $key    = 'section-test-key';
        $label  = 'Section Test Label';
        $field1 = $this->createField('first', 'First field');
        $field2 = $this->createField('second', 'Second field');

        $mock
            ->_getKey($key)
            ->_getLabel($label)
            ->_getFields([$field1, $field2]);

        $subject  = $mock->new();
        $reflect  = $this->reflect($subject);
        $rendered = $reflect->_render();

        $this->assertContains($key, $rendered, 'Render does not contain the section key.');
        $this->assertContains($label, $rendered, 'Render does not contain the section label.');
        $this->assertContains($field1->render(), $rendered, 'Render does not contain the first field.');
        $this->assertContains($field2->render(), $rendered, 'Render does not contain the second field.');
    }

    /**
     * Tests the render method with a context to assert whether fields receive the context value or whether an
     * exception is thrown if the context value is not provided by the context.
     *
     * @since [*next-version*]
     */
    public function testRenderWithContext()
    {
        $field1  = $this->createField('first', 'First field');
        $field2  = $this->createField('second', 'Second field');
        $field3  = $this->createField('third', 'Third field');
        $ctxVal1 = 'some value for first field';
        $ctxVal2 = 'some value for second field';
        $ctxVal3 = $this->mock('Psr\Container\NotFoundExceptionInterface')->new();

        $subject = $this->createInstance()
            ->_getFields([$field1, $field2, $field3])
            ->new();
        $reflect = $this->reflect($subject);

        $context = $this->mock('Dhii\Data\Container\ContainerInterface')
            // Expect get() to be called first time with field 1 key and return the value
            ->get([$field1->getKey()], $ctxVal1, $this->at(0))
            // Expect get() to be called first time with field 2 key and return the value
            ->get([$field2->getKey()], $ctxVal2, $this->at(1))
            // Expect get() to be called first time with field 3 key and throw exception
            ->get([$field3->getKey()], $ctxVal3, $this->at(2))
            ->has(true)
            ->new();

        // Expect field 1 render method to be called with the context value
        $field1->mock()
               ->render([$ctxVal1], null, $this->once());

        // Expect field 2 render method to be called with the context value
        $field2->mock()
               ->render([$ctxVal1], null, $this->once());

        $reflect->_render($context);
    }
}

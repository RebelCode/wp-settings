<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use PHPUnit_Framework_MockObject_MockObject;
use RebelCode\WordPress\Admin\Settings\FieldInterface;
use ReflectionClass;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\AbstractScreen}.
 *
 * @since [*next-version*]
 */
class AbstractScreenTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\AbstractScreen';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param string     $key      The screen key.
     * @param string     $label    The screen label.
     * @param array|null $sections The sections for this screen.
     *
     * @return MockWriter
     */
    public function createInstance($key = '', $label = '', $sections = null)
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                     ->_getKey($key)
                     ->_getLabel($label)
                     ->_renderNonceField()
                     ->_createCouldNotRenderException()
                     ->_createValidationException()
                     ->_createValidationFailedException()
                     ->_renderNonceField();

        if (is_array($sections)) {
            $mock->_getSections($sections);
        }

        return $mock;
    }

    /**
     * Creates a section mock instance.
     *
     * @since [*next-version*]
     *
     * @param string           $key    The key of the section.
     * @param string           $label  The label of the section.
     * @param FieldInterface[] $fields The fields in this section.
     *
     * @return MockWriter
     */
    public function createSection($key, $label, $fields = [])
    {
        $mock = $this->mock('RebelCode\WordPress\Admin\Settings\SectionInterface')
                     ->getLabel($label)
                     ->getKey($key)
                     ->getFields($fields)
                     ->validate()
                     ->render();

        return $mock;
    }

    /**
     * Creates a render context mock with fake return values and expectations.
     *
     * @since [*next-version*]
     *
     * @param array $expectations An numerically indexed array where the keys should be the expected invocation
     *                            index, whilst the values should be sub-arrays containing 2 elements: the expected key
     *                            to be passed to get() as the first element and the fake return value as the second.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createRenderContext(array $expectations = [])
    {
        $ctxMock = $this->getMockBuilder('Dhii\Data\Container\ContainerInterface')
                        ->setMethods(['get', 'has'])
                        ->getMock();

        foreach ($expectations as $_index => $_expectation) {
            list($_expectedKey, $_expectedVal) = $_expectation;

            $ctxMock->expects($this->at($_index))
                    ->method('get')
                    ->with($this->identicalTo($_expectedKey))
                    ->willReturn($_expectedVal);
        }

        return $ctxMock;
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
     * Tests the sections setter methods to ensure that the method that adds sections is called.
     *
     * @since [*next-version*]
     */
    public function testSetSections()
    {
        $section1 = $this->createSection('first', 'First section')->new();
        $section2 = $this->createSection('second', 'Second section')->new();
        $sections = [$section1, $section2];

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods(
                [
                    '_addSection',
                    '_createValidationException',
                    '_createValidationFailedException',
                    '_createCouldNotRenderException',
                    '_renderNonceField'
                ]
            )
            ->getMock();

        $mock->expects($this->exactly(2))
             ->method('_addSection')
             ->withConsecutive([$this->identicalTo($section1)], [$this->identicalTo($section2)]);

        $reflect   = new ReflectionClass(static::TEST_SUBJECT_CLASSNAME);
        $refMethod = $reflect->getMethod('_setSections');
        $refMethod->setAccessible(true);

        $refMethod->invokeArgs($mock, [$sections]);
    }

    /**
     * Tests the section addition method to ensure that sections are added.
     *
     * @since [*next-version*]
     */
    public function testGetAddSection()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $section1 = $this->createSection('first', 'First section')->new();
        $section2 = $this->createSection('second', 'Second section')->new();

        $reflect->_addSection($section1);
        $this->assertContains($section1, $reflect->_getSections(), 'Section 1 was not added to the screen.');

        $reflect->_addSection($section2);
        $this->assertContains($section2, $reflect->_getSections(), 'Section 2 was not added to the screen.');
    }

    /**
     * Tests the nonce getter and setter methods to ensure correct value assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetNonce()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $nonce = $this->mock('RebelCode\WordPress\Nonce\NonceInterface')
                      ->getId()
                      ->getCode()
                      ->new();

        $reflect->_setNonce($nonce);

        $this->assertSame(
            $nonce,
            $reflect->_getNonce(),
            'Retrieved and set nonce instances are not the same.'
        );
    }

    /**
     * Tests the render method to ensure that the rendered output contains all of the required content and that the
     * sections' render methods are called.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $section1 = $this->createSection('first', 'First section')
                       ->render($this->once())// Expect section 1 render method to be called
                       ->new();
        $section2 = $this->createSection('second', 'Second section')
                       ->render($this->once())// Expect section 2 render method to be called
                       ->new();

        $key     = 'screen-test-key';
        $label   = 'Screen Test Label';
        $subject = $this->createInstance($key, $label, [$section1, $section2])->new();

        $reflect  = $this->reflect($subject);
        $rendered = $reflect->_render();

        $this->assertContains($key, $rendered, 'Render does not contain the screen key.');
        $this->assertContains($label, $rendered, 'Render does not contain the screen label.');
    }

    /**
     * Tests the render method with a context to assert whether sections receive the context value or whether an
     * exception is thrown if the context value is not provided by the context.
     *
     * @since [*next-version*]
     */
    public function testRenderWithContext()
    {
        $ctxVal1 = ['ctx values', 'for the', 'first section'];
        $ctxVal2 = ['ctx values', 'for the', 'second section'];

        $section1 = $this->createSection('first', 'First field')
                       ->render([$ctxVal1], $this->anything(), $this->once())
                       ->new();
        $section2 = $this->createSection('second', 'Second field')
                       ->render([$ctxVal2], $this->anything(), $this->once())
                       ->new();

        $key     = 'section-test-key';
        $label   = 'Section Test Label';
        $subject = $this->createInstance($key, $label, [$section1, $section2])->new();

        // Create context mock
        $ctxMock = $this->createRenderContext(
            [
                // invocation index => [key passed to get(), value returned by get()]
                0 => [$section1->getKey(), $ctxVal1],
                1 => [$section2->getKey(), $ctxVal2]
            ]
        );

        $this->reflect($subject)->_render($ctxMock);
    }
}

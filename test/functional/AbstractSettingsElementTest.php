<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use ArrayObject;
use DateTime;
use Dhii\Data\Container\ContainerInterface;
use Dhii\Data\ValueAwareInterface;
use Xpmock\MockWriter;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\AbstractSettingsElement}.
 *
 * @since [*next-version*]
 */
class AbstractSettingsElementTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\AbstractSettingsElement';

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
                     ->_renderElement()
                     ->_createCouldNotRenderException();

        return $mock;
    }

    /**
     * Creates a container mock for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param mixed|null $get The value to retrieve when calling `get()`.
     * @param bool       $has The return value of the `has()` method.
     *
     * @return ContainerInterface
     */
    public function createContainer($get = null, $has = true)
    {
        $mock = $this->mock('\Dhii\Data\Container\ContainerInterface')
                     ->get($get)
                     ->has($has);

        return $mock->new();
    }

    /**
     * Creates a value aware instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param mixed|null $value The value that the instance should provide.
     *
     * @return ValueAwareInterface
     */
    public function createValueAware($value = null)
    {
        $mock = $this->mock('\Dhii\Data\ValueAwareInterface')
                     ->getValue($value);

        return $mock->new();
    }

    /**
     * Creates a value aware instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string $string The string that the instance should be cast into.
     *
     * @return ValueAwareInterface
     */
    public function createStringable($string = '')
    {
        $mock = $this->mock('\Dhii\Util\String\StringableInterface')
                     ->__toString($string);

        return $mock->new();
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
     * Tests the render context key getter method to ensure that it returns the element's key by default.
     *
     * @since [*next-version*]
     */
    public function testGetRenderContextKey()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $reflect->_setKey($key = 'my-123-key');

        $this->assertEquals($key, $reflect->_getRenderContextKey());
    }

    /**
     * Tests the render context normalization method with a container containing the value to be used.
     *
     * @since [*next-version*]
     */
    public function testNormalizeRenderContextWithContainerHasValue()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $key       = 'my-key';
        $value     = 'my-value';
        $container = $this->createContainer($value, true);
        $reflect->_setKey($key);

        $this->assertEquals($value, $reflect->_normalizeRenderContext($container));
    }

    /**
     * Tests the render context normalization method with a container NOT containing the value to be used.
     *
     * @since [*next-version*]
     */
    public function testNormalizeRenderContextWithContainerNoValue()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $key       = 'my-key';
        $value     = null;
        $container = $this->createContainer($value, false);

        $this->assertEquals($container, $reflect->_normalizeRenderContext($container));
    }

    /**
     * Tests the render context normalization method with a value aware instance.
     *
     * @since [*next-version*]
     */
    public function testNormalizeRenderContextWithValueAware()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $valueAware = $this->createValueAware($value = 'my test value');

        $this->assertEquals($value, $reflect->_normalizeRenderContext($valueAware));
    }

    /**
     * Tests the render context normalization method with a stringable instance.
     *
     * @since [*next-version*]
     */
    public function testNormalizeRenderContextWithStringable()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $stringable = $this->createStringable($string = 'my test string');

        $this->assertEquals($string, $reflect->_normalizeRenderContext($stringable));
    }

    /**
     * Tests the render context normalization method with an array.
     *
     * @since [*next-version*]
     */
    public function testNormalizeRenderContextWithArray()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $array = [
            'zero'  => 0,
            'one'   => 1,
            'two'   => 2,
            'three' => 3,
        ];
        $reflect->_setKey('two');

        $this->assertEquals(2, $reflect->_normalizeRenderContext($array));
    }

    /**
     * Tests the render context normalization method with an object that supports array access.
     *
     * @since [*next-version*]
     */
    public function testNormalizeRenderContextWithArrayAccess()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $arrayObject = new ArrayObject([
            'zero'  => 0,
            'one'   => 1,
            'two'   => 2,
            'three' => 3,
        ]
        );
        $reflect->_setKey('two');

        $this->assertEquals(2, $reflect->_normalizeRenderContext($arrayObject));
    }

    /**
     * Tests the render context normalization method with a non-supported value.
     *
     * @since [*next-version*]
     */
    public function testNormalizeRenderContextWithNonSupportedValue()
    {
        $subject = $this->createInstance()->new();
        $reflect = $this->reflect($subject);

        $number = 123456;
        $string = 'test string';
        $misc   = new DateTime();

        $this->assertEquals($number, $reflect->_normalizeRenderContext($number));
        $this->assertEquals($string, $reflect->_normalizeRenderContext($string));
        $this->assertEquals($misc, $reflect->_normalizeRenderContext($misc));
    }

    /**
     * Tests the render method to ensure that context normalization and block retrieval are invoked.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $subject = $this->createInstance()
                        ->_normalizeRenderContext($this->once())
                        ->_renderElement($this->once())
                        ->new();
        $reflect = $this->reflect($subject);

        $reflect->_render();
    }
}

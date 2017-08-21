<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use Dhii\Type\TypeInterface;
use RebelCode\WordPress\Admin\Settings\TypeAwareTrait;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\TypeAwareTrait}.
 *
 * @since [*next-version*]
 */
class TypeAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\TypeAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TypeAwareTrait
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Creates a mock instance for a type.
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
     * Tests the type getter and setter methods to ensure correct instance assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetType()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setType($type = $this->createType());

        $this->assertSame(
            $type,
            $reflect->_getType(),
            'The retrieved and set type instances are not the same.'
        );
    }
}

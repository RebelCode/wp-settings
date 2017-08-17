<?php

namespace RebelCode\WordPress\Admin\Settings\FuncTest;

use Dhii\Validation\Exception\ValidationFailedException;
use Dhii\Validation\ValidatorInterface;
use RebelCode\WordPress\Admin\Settings\CompositeValidatorTrait;
use Xpmock\TestCase;

/**
 * Tests {@see \RebelCode\WordPress\Admin\Settings\CompositeValidatorTrait}.
 *
 * @since [*next-version*]
 */
class CompositeValidatorTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\WordPress\Admin\Settings\CompositeValidatorTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param ValidatorInterface[] $validators The children validators.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance($validators = [])
    {
        // Create mock
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);
        $mock->method('_getChildValidators')
             ->willReturn($validators);

        return $mock;
    }

    /**
     * Creates a validator for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param array $errors The validation errors.
     *
     * @return ValidatorInterface
     */
    public function createValidator($errors = [])
    {
        $exception = count($errors)
            ? $this->createValidationFailedException($errors)
            : null;

        $mock = $this->mock('Dhii\Validation\ValidatorInterface')
                     ->validate(
                         function() use ($exception) {
                             if ($exception) {
                                 throw $exception;
                             }
                         }
                     );

        return $mock->new();
    }

    /**
     * Creates an exception instance for failed validation.
     *
     * @since [*next-version*]
     *
     * @param array $errors The validation errors.
     *
     * @return ValidationFailedException
     */
    public function createValidationFailedException($errors = [])
    {
        return new ValidationFailedException('', 0, null, null, $errors);
    }

    /**
     * Tests the validation errors getter method to ensure that the children validator errors are correctly included.
     *
     * @since [*next-version*]
     */
    public function testGetValidationErrors()
    {
        $subject = $this->createInstance(
            [
                'first-one'         => $this->createValidator(
                    $ve1 = [
                        'This is the first validation error',
                        'Whoops. Another error',
                    ]
                ),
                'special-validator' => $this->createValidator(
                    $ve2 = [
                        'Lorem ipsum dolor sit amet',
                    ]
                ),
                $this->createValidator(
                    $ve3 = [
                        'consectetur adipiscing elit',
                        'ed do eiusmod tempor incididunt ut',
                        'labore et dolore magna aliqua',
                    ]
                ),
            ]
        );
        $reflect = $this->reflect($subject);

        $expected = array_merge($ve1, $ve2, $ve3);
        $errors   = $reflect->_getValidationErrors(null);

        $this->assertEquals($expected, $errors, 'Validation errors do not match the children errors.');
    }

    /**
     * Tests the validation errors getter method with grouping enabled to ensure that the children validator errors are
     * correctly included and mapped by child keys.
     *
     * @since [*next-version*]
     */
    public function testGetValidationErrorsWithGrouping()
    {
        $subject = $this->createInstance(
            [
                'first-one'         => $this->createValidator(
                    $ve1 = [
                        'This is the first validation error',
                        'Whoops. Another error',
                    ]
                ),
                'special-validator' => $this->createValidator(
                    $ve2 = [
                        'Lorem ipsum dolor sit amet',
                    ]
                ),
                $this->createValidator(
                    $ve3 = [
                        'consectetur adipiscing elit',
                        'ed do eiusmod tempor incididunt ut',
                        'labore et dolore magna aliqua',
                    ]
                ),
            ]
        );
        $reflect = $this->reflect($subject);

        $expected = [
            'first-one'         => $ve1,
            'special-validator' => $ve2,
            0                   => $ve3,
        ];
        $errors   = $reflect->_getValidationErrors(null, true);

        $this->assertEquals($expected, $errors, 'Validation errors do not match the children errors.');
    }
}

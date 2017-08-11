<?php

namespace RebelCode\WordPress\Admin\Settings;

use Countable;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Validation\ValidatorInterface;
use Traversable;

/**
 * Common functionality for validators that are made up of children validators.
 *
 * @since [*next-version*]
 */
trait CompositeValidatorTrait
{
    /**
     * Retrieve a list of reasons that make the subject invalid.
     *
     * An empty list means that the subject is valid.
     * This is what actually performs the validation.
     *
     * @since [*next-version*]
     *
     * @return array|Countable|Traversable The list of validation errors.
     */
    protected function _getValidationErrors($subject)
    {
        $errors = [];

        foreach ($this->_getChildrenValidators() as $_key => $_validator) {
            $errors[$_key] = $this->_getValidationErrorsForChild($subject, $_validator);
        }

        return $errors;
    }

    /**
     * Retrieves the validation errors for a child validator.
     *
     * @since [*next-version*]
     *
     * @param mixed|null         $value The value to validate.
     * @param ValidatorInterface $child The child validator instance.
     *
     * @return array|Stringable[]|string[]|Traversable
     */
    protected function _getValidationErrorsForChild($value, ValidatorInterface $child)
    {
        try {
            $child->validate($value);
        } catch (ValidationFailedExceptionInterface $exception) {
            return $exception->getValidationErrors();
        }

        return [];
    }

    /**
     * Retrieves the children validators.
     *
     * @since [*next-version*]
     *
     * @return ValidatorInterface[]|Traversable The validator instances, mapped via unique keys.
     */
    abstract protected function _getChildrenValidators();
}

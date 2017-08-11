<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Data\KeyAwareTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Validation\AbstractValidatorBase;

/**
 * Abstract common functionality for a settings element.
 *
 * @since [*next-version*]
 */
abstract class AbstractSettingsElement extends AbstractValidatorBase
{
    /*
     * Provides key getter and setter methods.
     *
     * @since [*next-version*]
     */
    use KeyAwareTrait;

    /**
     * The label.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable
     */
    protected $label;

    /**
     * Retrieves the label for this settings element.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable
     */
    protected function _getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the label for this settings element.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $label The label.
     *
     * @return $this
     */
    protected function _setLabel($label)
    {
        $this->label = $label;

        return $this;
    }
}

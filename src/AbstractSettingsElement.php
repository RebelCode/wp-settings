<?php

namespace RebelCode\WordPress\Admin\Settings;

use ArrayAccess;
use Dhii\Data\Container\ContainerInterface;
use Dhii\Data\KeyAwareTrait;
use Dhii\Data\ValueAwareInterface;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Util\String\LabelAwareTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Validation\AbstractValidatorBase;
use Exception;

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

    /*
     * Provides the label property with getter and setter methods.
     *
     * @since [*next-version*]
     */
    use LabelAwareTrait;
}

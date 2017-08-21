<?php

namespace RebelCode\WordPress\Admin\Settings;

use ArrayAccess;
use Dhii\Data\Container\ContainerInterface;
use Dhii\Data\KeyAwareTrait;
use Dhii\Data\ValueAwareInterface;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Util\String\LabelAwareTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception;

/**
 * Abstract common functionality for a settings element.
 *
 * @since [*next-version*]
 */
abstract class AbstractSettingsElement
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

    /*
     * Provides basic string translating capabilities via `$this->__()`.
     *
     * @since [*next-version*]
     */
    use StringTranslatingTrait;

    /**
     * Renders the settings element.
     *
     * @since [*next-version*]
     *
     * @param mixed $pContext The render context.
     *
     * @return string|Stringable The rendered content.
     */
    protected function _render($pContext = null)
    {
        $context = $this->_normalizeRenderContext($pContext);

        return (string) $this->_renderElement($context);
    }

    /**
     * Normalizes the render context.
     *
     * @since [*next-version*]
     *
     * @param mixed $context The render context.
     *
     * @return string The normalized render context value to use for this instance.
     */
    protected function _normalizeRenderContext($context)
    {
        $key = $this->_getRenderContextKey();

        if ($context instanceof ContainerInterface && $context->has($key)) {
            return $context->get($key);
        }

        if ($context instanceof ValueAwareInterface) {
            return $context->getValue();
        }

        if (is_array($context) || $context instanceof ArrayAccess) {
            return $context[$key];
        }

        if ($context instanceof Stringable) {
            return (string) $context;
        }

        return $context;
    }

    /**
     * Retrieves the key to use when fetching a value from a composite render context.
     *
     * @since [*next-version*]
     *
     * @return string
     */
    protected function _getRenderContextKey()
    {
        return $this->_getKey();
    }

    /**
     * Renders the settings element.
     *
     * @since [*next-version*]
     *
     * @param mixed $context Optional render context.
     *
     * @return Stringable|string
     */
    abstract protected function _renderElement($context = null);

    /**
     * Creates an exception for when the renderer fails to render.
     *
     * @since [*next-version*]
     *
     * @param string     $message The exception message.
     * @param int|string $code    The exception error code.
     * @param Exception  $inner   The previous exception in the chain.
     *
     * @return CouldNotRenderExceptionInterface
     */
    abstract protected function _createCouldNotRenderException($message, $code, Exception $inner);
}

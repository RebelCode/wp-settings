<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\I18n\StringTranslatingTrait;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Util\String\DescriptionAwareTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;

/**
 * Abstract common functionality for settings fields.
 *
 * @since [*next-version*]
 */
abstract class AbstractField extends AbstractSettingsElement
{
    /*
     * Provides basic string translating capabilities via `$this->__()`.
     *
     * @since [*next-version*]
     */
    use StringTranslatingTrait;

    /*
     * Provides the description property with getter and setter methods.
     *
     * @since [*next-version*]
     */
    use DescriptionAwareTrait;

    /**
     * Renders the field.
     *
     * @since [*next-version*]
     *
     * @param mixed|null $context The rendering context; the value to render for this settings field.
     *
     * @return string|Stringable
     *
     * @throws CouldNotRenderExceptionInterface If the field failed to render.
     */
    protected function _render($context = null)
    {
        $ctxValue = $this->_normalizeRenderContext($context);

        try {
            $this->_validate($ctxValue);
        } catch (ValidationFailedExceptionInterface $exception) {
            throw $this->_createCouldNotRenderException(
                $this->__('Context value is not valid'), 0, $exception
            );
        }

        return sprintf(
            '<tr>%1$s %2$s</tr>',
            $this->_renderLabelCell($ctxValue),
            $this->_renderContentCell($ctxValue)
        );
    }

    /**
     * Renders the label cell.
     *
     * @since [*next-version*]
     *
     * @param mixed $context The context value.
     *
     * @return string|Stringable The rendered label cell.
     */
    protected function _renderLabelCell($context)
    {
        return sprintf(
            '<th scope="row"><label for="%1$s">%2$s</label></th>',
            $this->_getKey(),
            $this->_getLabel()
        );
    }

    /**
     * Renders the content cell.
     *
     * @since [*next-version*]
     *
     * @param mixed $context The context value.
     *
     * @return string The rendered content cell.
     */
    protected function _renderContentCell($context)
    {
        return sprintf(
            '<td>%1$s <p class="description">%2$s</p></td>',
            $this->_renderField($context),
            $this->_getDescription()
        );
    }

    /**
     * Renders the field UI component.
     *
     * @since [*next-version*]
     *
     * @param mixed|null $value The value to render for this settings field.
     *
     * @return string|Stringable
     *
     * @throws CouldNotRenderExceptionInterface If the field failed to render.
     * @throws RendererExceptionInterface If the field failed to render.
     */
    abstract protected function _renderField($value);
}

<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Data\Container\ContainerInterface;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Output\Exception\CouldNotRenderExceptionInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\NotFoundExceptionInterface;
use Traversable;

/**
 * Abstract common functionality for settings sections.
 *
 * @since [*next-version*]
 */
abstract class AbstractSection extends AbstractSettingsElement
{
    /*
     * Provides basic string translating capabilities via `$this->__()`.
     *
     * @since [*next-version*]
     */
    use StringTranslatingTrait;

    /*
     * Provides functionality for validation using children validators.
     *
     * @since [*next-version*]
     */
    use CompositeValidatorTrait {
        _getValidationErrors as _getValidationErrorsGroupable;
    }

    /**
     * The fields in this section.
     *
     * @since [*next-version*]
     *
     * @var FieldInterface[]|Traversable
     */
    protected $fields;

    /**
     * Retrieves the fields in this section.
     *
     * @since [*next-version*]
     *
     * @return FieldInterface[]|Traversable
     */
    protected function _getFields()
    {
        return $this->fields;
    }

    /**
     * Sets the fields for this section.
     *
     * @since [*next-version*]
     *
     * @param FieldInterface[]|Traversable $fields The field instances.
     *
     * @return $this
     */
    protected function _setFields($fields)
    {
        foreach ($fields as $_field) {
            $this->_addField($_field);
        }

        return $this;
    }

    /**
     * Adds a field to this section.
     *
     * @since [*next-version*]
     *
     * @param FieldInterface $field The field instance to add.
     *
     * @return $this
     */
    protected function _addField(FieldInterface $field)
    {
        $this->fields[$field->getKey()] = $field;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getChildValidators()
    {
        return $this->_getFields();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getValidationErrors($subject)
    {
        return $this->_getValidationErrorsGroupable($subject, false);
    }

    /**
     * Renders the section and its fields.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context The values to render for the fields.
     *
     * @throws CouldNotRenderExceptionInterface If the non-null context does not provide a value for a field.
     *
     * @return string
     */
    protected function _render(ContainerInterface $context = null)
    {
        $heading = $this->_renderHeading($context);
        $content = $this->_renderContent($context);

        return sprintf('%1$s %2$s', $heading, $content);
    }

    /**
     * Renders the heading for this section.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context The values to render for the fields.
     *
     * @return string|Stringable
     */
    protected function _renderHeading(ContainerInterface $context = null)
    {
        return sprintf('<h2>%s</h2>', $this->_getLabel());
    }

    /**
     * Renders the contents of the section.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context The values to render for the fields.
     *
     * @throws CouldNotRenderExceptionInterface If the non-null context does not provide a value for a field.
     *
     * @return string|Stringable
     */
    protected function _renderContent(ContainerInterface $context = null)
    {
        $rFields = $this->_renderAllFields($context);
        $table   = sprintf(
            '<table id="%1$s" class="form-table"><tbody>%2$s</tbody></table>',
            $this->_getKey(),
            $rFields
        );

        return $table;
    }

    /**
     * Renders the fields for this section.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context The values to render for the fields.
     *
     * @throws CouldNotRenderExceptionInterface If the non-null context does not provide a value for a field.
     *
     * @return string|Stringable
     */
    protected function _renderAllFields(ContainerInterface $context = null)
    {
        $render = '';

        foreach ($this->_getFields() as $_field) {
            $render .= $this->_renderSingleField($_field, $context);
        }

        return $render;
    }

    /**
     * Renders a single field.
     *
     * @since [*next-version*]
     *
     * @param FieldInterface          $field   The field instance to render.
     * @param ContainerInterface|null $context Optional render context containing the value to render for the field.
     *
     * @throws CouldNotRenderExceptionInterface If the non-null context does not provide a value of the field.
     *
     * @return Stringable|string
     */
    protected function _renderSingleField(FieldInterface $field, ContainerInterface $context = null)
    {
        $key = $field->getKey();

        try {
            $val = ($context !== null)
                ? $context->get($key)
                : null;
        } catch (NotFoundExceptionInterface $exception) {
            throw $this->_createCouldNotRenderException(
                $this->__('Context does not have a value for key "%s"', [$key]),
                0,
                $exception
            );
        }

        return $field->render($val);
    }
}

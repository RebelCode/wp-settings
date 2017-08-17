<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Data\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use RebelCode\WordPress\Nonce\NonceInterface;
use Traversable;

/**
 * Abstract common functionality for settings screens.
 *
 * @since [*next-version*]
 */
abstract class AbstractScreen extends AbstractSettingsElement
{
    /*
     * Provides functionality for validation using children validators.
     *
     * @since [*next-version*]
     */
    use CompositeValidatorTrait {
        _getValidationErrors as _getValidationErrorsGroupable;
    }

    /**
     * The key of the "options page" hidden field.
     *
     * @since [*next-version*]
     */
    const K_HF_OPTIONS_PAGE = 'options_page';

    /**
     * The key of the "action" hidden field.
     *
     * @since [*next-version*]
     */
    const K_HF_ACTION = 'action';

    /**
     * The value of the "action" hidden field.
     *
     * @since [*next-version*]
     */
    const HF_ACTION = 'update';

    /**
     * The form's action attribute value.
     *
     * @since [*next-version*]
     */
    const ATTR_FORM_ACTION = 'options.php';

    /**
     * The form's method attribute value.
     *
     * @since [*next-version*]
     */
    const ATTR_FORM_METHOD = 'post';

    /**
     * The sections for this screen.
     *
     * @since [*next-version*]
     *
     * @var SectionInterface[]|Traversable
     */
    protected $sections;

    /**
     * The nonce for this settings screen.
     *
     * @since [*next-version*]
     *
     * @var NonceInterface
     */
    protected $nonce;

    /**
     * Retrieves the sections for this screen.
     *
     * @since [*next-version*]
     *
     * @return SectionInterface[]|Traversable
     */
    protected function _getSections()
    {
        return $this->sections;
    }

    /**
     * Sets the sections for this screen.
     *
     * @since [*next-version*]
     *
     * @param SectionInterface[]|Traversable $sections The sections for this screen.
     *
     * @return $this
     */
    protected function _setSections($sections)
    {
        foreach ($sections as $_section) {
            $this->_addSection($_section);
        }

        return $this;
    }

    /**
     * Adds a section to this screen.
     *
     * @since [*next-version*]
     *
     * @param SectionInterface $_section The section instance to add to this screen.
     *
     * @return $this
     */
    protected function _addSection(SectionInterface $_section)
    {
        $this->sections[$_section->getKey()] = $_section;

        return $this;
    }

    /**
     * Retrieves the nonce instance for this settings screen.
     *
     * @since [*next-version*]
     *
     * @return NonceInterface
     */
    protected function _getNonce()
    {
        return $this->nonce;
    }

    /**
     * Sets the nonce instance for this settings screen.
     *
     * @since [*next-version*]
     *
     * @param NonceInterface $nonce The nonce instance.
     *
     * @return $this
     */
    protected function _setNonce($nonce)
    {
        $this->nonce = $nonce;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getChildValidators()
    {
        return $this->_getSections();
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
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context
     *
     * @return string
     */
    protected function _render(ContainerInterface $context = null)
    {
        $header  = $this->_renderHeader();
        $content = $this->_renderContent($context);

        return sprintf('%1$s %2$s', $header, $content);
    }

    /**
     * Renders the header of the screen.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable
     */
    protected function _renderHeader()
    {
        return sprintf('<h1>%s</h1>', $this->_getLabel());
    }

    /**
     * Renders the content of the screen.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context The values to render for the sections and fields for this screen.
     *
     * @return string
     */
    protected function _renderContent(ContainerInterface $context = null)
    {
        $hiddenFields = $this->_renderHiddenFields();
        $nonceField   = $this->_renderNonceField();
        $sections     = $this->_renderSections($context);
        $form         = sprintf(
            '<form action="%1$s" method="%2$s">%3$s %4$s %5$s</form>',
            static::ATTR_FORM_ACTION,
            static::ATTR_FORM_METHOD,
            $hiddenFields,
            $nonceField,
            $sections
        );

        return $form;
    }

    /**
     * Renders the hidden fields of the screen's form.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable
     */
    protected function _renderHiddenFields()
    {
        $render = '';

        foreach ($this->_getHiddenFields() as $_key => $_val) {
            $render .= sprintf('<input type="hidden" id="%1$s" name="%1$s" value="%2$s" />', $_key, $_val);
        }

        return $render;
    }

    /**
     * Retrieves the hidden fields for this screen.
     *
     * @since [*next-version*]
     *
     * @return array
     */
    protected function _getHiddenFields()
    {
        return [
            static::K_HF_OPTIONS_PAGE => $this->_getKey(),
            static::K_HF_ACTION       => static::HF_ACTION,
        ];
    }

    /**
     * Renders the sections for this screen.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface|null $context The value sets to render for the sections.
     *
     * @return string|Stringable
     */
    protected function _renderSections(ContainerInterface $context = null)
    {
        $render = '';

        foreach ($this->_getSections() as $_section) {
            $render .= $this->_renderSingleSection($_section, $context);
        }

        return $render;
    }

    /**
     * Renders a single section for this screen.
     *
     * @since [*next-version*]
     *
     * @param SectionInterface        $section The section instance.
     * @param ContainerInterface|null $context The values to render for the sections and fields.
     *
     * @return Stringable|string
     */
    protected function _renderSingleSection(SectionInterface $section, ContainerInterface $context = null)
    {
        $key = $section->getKey();

        try {
            $_val = ($context !== null)
                ? $context->get($key)
                : null;
        } catch (NotFoundExceptionInterface $exception) {
            $_val = null;
        }

        return $section->render($_val);
    }

    /**
     * Renders the nonce field.
     *
     * @since [*next-version*]
     *
     * @return string
     */
    abstract protected function _renderNonceField();
}

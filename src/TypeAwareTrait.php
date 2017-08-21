<?php

namespace RebelCode\WordPress\Admin\Settings;

use Dhii\Type\TypeInterface;

/**
 * Something that is aware of a type.
 *
 * @since [*next-version*]
 */
trait TypeAwareTrait
{
    /**
     * The type instance.
     *
     * @since [*next-version*]
     *
     * @var TypeInterface
     */
    protected $type;

    /**
     * Retrieves the type instance.
     *
     * @since [*next-version*]
     *
     * @return TypeInterface The type instance.
     */
    protected function _getType()
    {
        return $this->type;
    }

    /**
     * Sets the type instance.
     *
     * @since [*next-version*]
     *
     * @param TypeInterface $type The type instance to set.
     *
     * @return $this
     */
    protected function _setType(TypeInterface $type)
    {
        $this->type = $type;

        return $this;
    }
}

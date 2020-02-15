<?php

namespace Vuravel\Elements\Contracts;

interface BasicAttributes
{
    /**
     * Sets the id attribute of the element.
     *
     * @param  string  $id
     * @return mixed
     */
    public function id($id);

    /**
     * Adds an array of classes to the element.
     *
     * @param  array|string  $classes
     * @return mixed
     */
    public function addClass($classes);

    /**
     * Assign additional data to the element.
     *
     * @param  array  $data
     * @return mixed
     */
    public function data(array $data);
}

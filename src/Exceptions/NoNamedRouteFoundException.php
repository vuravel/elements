<?php

namespace Vuravel\Elements\Exceptions;

use RuntimeException;

class NoNamedRouteFoundException extends RuntimeException
{
	public function setMessage($route)
    {
        $this->message = "No matching route was found with {$route}.";
        return $this;
    }
}

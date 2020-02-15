<?php

namespace Vuravel\Elements\Exceptions;

use RuntimeException;

class EmptyRouteException extends RuntimeException
{
	public function setMessage($id)
    {
        $this->message = "Route or Url is empty for Element ID: {$id}";
        return $this;
    }
}

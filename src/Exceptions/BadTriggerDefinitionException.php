<?php

namespace Vuravel\Elements\Exceptions;

use RuntimeException;

class BadTriggerDefinitionException extends RuntimeException
{
	public function setMessage($type)
    {
        $this->message = "The parameter for trigger(s) should be either a String or Array. Instead a {$type} was given.";
        return $this;
    }
}

<?php

namespace Vuravel\Elements\Exceptions;

use RuntimeException;

class TriggerNotAllowedException extends RuntimeException
{
	public function setMessage($trigger)
    {
        $this->message = "{$trigger} is not an allowed trigger name.";
        return $this;
    }
}

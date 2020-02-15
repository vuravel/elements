<?php 
namespace Vuravel\Elements\Traits;

use Illuminate\Support\Collection;
use Vuravel\Menu\MenuItem;

trait IsLayout {

    protected function getFilteredComponents($args)
    {
        if($this->argsDefinedAsString($args))
            return collect([]);

        $args = is_array($args) && count($args) == 1 && 
                (is_array($args[0]) || $args[0] instanceof Collection) ? $args[0] : $args;

        if(!$args instanceof Collection)
            $args = collect(is_array($args) ? $args : (
                    is_callable($args) ? call_user_func($args) : //For Menus
                    ( $args instanceof MenuItem ? [$args] : null ))); //For Menus

        return $args->filter();
    }

    protected function getNormalizedLabel($args)
    {
        return $this->argsDefinedAsString($args) ? $args[0] : class_basename($this);
    }

    //PlainSpan for ex.
    protected function argsDefinedAsString($args)
    {
        return is_array($args) && count($args) == 1 && is_string($args[0]);
    }

}
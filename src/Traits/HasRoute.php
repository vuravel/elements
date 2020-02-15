<?php 
namespace Vuravel\Elements\Traits;

use Vuravel\Elements\Exceptions\{EmptyRouteException, NoNamedRouteFoundException};

trait HasRoute {

    protected $routeObject;

    protected function setRoute($route, $parameters = null)
    {
        $this->data(['route' => $this->guessRoute($route, $parameters) ]);

        return $this;
    }

    protected function setRouteWithMethod($route, $parameters = null)
    {
        $this->setRoute($route, $parameters);

        if(is_null($this->routeObject))
            $this->routeObject = $this->getMatchedRoute($route, $parameters);

        $this->setRouteMethod($this->getRouteMethod());

        return $this;
    }

    /**
     * Guesses the desired route and method from the parameters.
     *
     * @param  string  $route
     * @param  mixed  $parameters
     * @return mixed
     */
    protected function guessRoute($route, $parameters = null)
    {
        if(!$route)
            throw (new EmptyRouteException)->setMessage($this->id);
        
        $this->routeObject = $this->getRouteByName($route);

        return is_null( $this->routeObject ) ? url($route, $parameters) : route($route, $parameters);
    }

    protected function setRouteMethod($method = null)
    {
        $this->data(['routeMethod' => $method ]);
    }

    /**
     * Gets the route of the element.
     *
     * @return mixed
     */
    public function getRoute()
    {
        return $this->data('route');
    }

    public function getVuravelObjectFromRoute()
    {
        if(!$this->routeObject)
            throw (new NoNamedRouteFoundException)->setMessage($this->getRoute());

        $func = new \ReflectionFunction($this->routeObject->action['uses']);
        $func = new \ReflectionFunction($func->getStaticVariables()['object']);
        return $func->getStaticVariables()['objectClass'];
    }


    protected function getRouteByName($route)
    {
        return \Route::getRoutes()->getByName($route);
    }

    protected function getMatchedRoute($route, $parameters = null)
    {
        return collect(\Route::getRoutes())->first(function($r) use($route, $parameters){
            return $r->matches(request()->create(url($route, $parameters)));
        });
    }

    protected function getRouteMethod()
    {
        return $this->routeObject->methods[0] ?? 'POST';
    }

}
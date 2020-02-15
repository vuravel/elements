<?php

namespace Vuravel\Elements;

class Action
{
    use \Vuravel\Elements\Traits\HasRoute;
    use \Vuravel\Elements\Traits\HasTriggers;
    use \Vuravel\Elements\Traits\EmitsEvents;
    use \Vuravel\Elements\Traits\PerformsAjax;
    use \Vuravel\Form\Traits\RelatesToFormSubmission;
    use \Vuravel\Catalog\Components\Traits\DoesSorting;
    use \Vuravel\Catalog\Components\Traits\FiltersCatalog;

    //protected
    protected $element;
    protected $isAjax = false;

    //common
    public $action;
    public $vuravelid;

    //HasRoute
    public $route;
    public $routeMethod;
    public $include;

    //EmitsEvents
    public $event;
    public $emitPayload;

    //Performs Ajax
    public $ajaxPayload;
    public $sessionTimeoutMessage;
    public $redirectUrl;
    public $modalName;
    public $warnBeforeClose;
    public $panelId;
    public $response; 
    public $message;
    public $alertClass;
    public $debounce;

    public function __construct($element)
    {
        $this->element = $element;
    }


    public static function form($element, $function)
    {
        $trigger = new static($element);
        call_user_func($function, $trigger);
        return $trigger;
    }

    public static function emptyAjax($element)
    {
        $trigger = new static($element);
        $trigger->isAjax = true;
        return $trigger;
    }

    public function thenBrowseCatalog()
    {
        return $this->onSuccess(function($e){
            $e->submitsForm();
        });
    }

    public function isAjax()
    {
        return $this->isAjax;
    }

    public function __toString()
    {
        return json_encode($this);
    }

    /********************************************
    ************** Action only methods **********
    *********************************************/
    /**
     * Sets the debounce interval for an action. Otherwise, it is defaulted to 500ms
     *
     * @param integer|null $debounce The number of milliseconds between requests.
     *
     * @return     self 
     */
    public function debounce($debounce = 500)
    {
        $this->debounce = $debounce;
        return $this;
    }


    /********************************************
    ************** EmitsEvents overrides ********
    *********************************************/
    public function emitsOnSuccess($event, $data = null)
    {
        return $this->onSuccess(function($e) use($event, $data){
            $e->emitFromAction($event, $data);
        });
    }

    //Internal use only for now. To emit from vl-button, vl-link
    public function emitsDirectOnSuccess($event)
    {
        return $this->onSuccess(function($e) use($event){
            $e->action = 'emitDirect';
            $e->event = $event;
        });
    }

    /********************************************
    ************** PerformsAjax overrides ********
    *********************************************/
    public function loadsView($route, $parameters = null, $ajaxPayload = null)
    {
        $this->setRouteWithMethod($route, $parameters);
        
        return $this->commonAjax($ajaxPayload);
    }
    
    public function post($route, $parameters = null, $ajaxPayload = null)
    {
        $this->setRoute($route, $parameters);
        $this->setRouteMethod('POST');

        return $this->commonAjax($ajaxPayload);
    }
    
    public function include($route, $parameters = null, $ajaxPayload = null)
    {
        $this->include = true;

        $this->setRoute($route, $parameters);
        $this->setRouteMethod('POST');

        return $this->commonAjax($ajaxPayload);
    }

    //now it's the same as post. UPDATE DOCS
    public function loads($route, $parameters = null, $ajaxPayload = null)
    {
        return $this->post($route, $parameters, $ajaxPayload);
    }

    public function commonAjax($ajaxPayload = null)
    {
        $this->action = 'axiosRequest';
        $this->isAjax = true;

        $this->sessionTimeoutMessage = __('sessionTimeoutMessage');

        if($ajaxPayload)
            $this->ajaxPayload = $ajaxPayload;

        return $this;
    }

    public function onSuccessAlert($message, $iconClass = true)
    {
        $message = !$iconClass ? $message : 
            ('<i class="'.($iconClass == true ? 'icon-check' : $iconClass).'"> '.$message);

        return $this->onSuccess(function($e) use ($message, $iconClass) {
            $e->action = 'addAlert';
            $e->message = $message;
            $e->alertClass = 'vlAlertSuccess';
        });
    }

    public function onErrorAlert($message, $iconClass = true)
    {
        $message = !$iconClass ? $message : 
            ('<i class="'.($iconClass == true ? 'icon-times' : $iconClass).'"> '.$message);
            
        return $this->onError(function($e) use ($message, $iconClass) {
            $e->action = 'addAlert';
            $e->message = $message;
            $e->alertClass = 'vlAlertError';
        });
    }

    public function redirect($route = null, $parameters = null)
    {
        return $this->onSuccess(function($e) use($route, $parameters) {
            $e->action = 'redirect';
            $e->redirectUrl = $route ? $this->guessRoute($route, $parameters) : true;
        });
    }

    public function inModal($modalName = null, $warnBeforeClose = false)
    {
        return $this->onSuccess(function($e) use ($modalName, $warnBeforeClose) {
            $e->action = 'fillModal';
            $e->modalName = $modalName;
            $e->panelId = $modalName;
            $e->warnBeforeClose = $warnBeforeClose == false ? false : 
                ($warnBeforeClose == true ? __('warnBeforeClose') : __($warnBeforeClose));
        });
    }

    public function inPanel($panelId)
    {
        return $this->onSuccess(function($e) use ($panelId) {
            $e->action = 'fillPanel';
            $e->panelId = $panelId;
        });
    }


    /********************************************
    ************** Form Actions overrides *******
    *********************************************/
    public function submitsForm()
    {
        $this->action = 'submitForm';
        $this->isAjax = true;
        return $this;
    }

    /********************************************
    ************** Catalog Actions overrides *****
    *********************************************/
    public function sortsCatalog($sortOrders = '')
    {
        $this->action = 'sortCatalog';
        $this->isAjax = true;

        if($this->element->isField())
            $this->element->notRelatedToModel()->doesNotFill();

        $this->element->data([
            'sortsCatalog' => $sortOrders ?: true
        ]);

        return $this;
    }

    public function refreshCatalog($catalogId = null)
    {
        $this->action = 'refreshCatalog';
        $this->isAjax = true;
        $this->vuravelid = $catalogId;
        return $this;
    }

    public function refreshOnSuccess($catalogId = null)
    {
        return $this->onSuccess(function($e) use($catalogId){
            $e->refreshCatalog($catalogId);
        });
    }



    /********************************************
    ************** Routes Methods overrides *****
    *********************************************/
    public function getRoute()
    {
        return $this->route;
    }

    protected function setRoute($route, $parameters = null)
    {
        $this->route = $this->guessRoute($route, $parameters);

        return $this;
    }

    protected function setRouteMethod($method = null)
    {
        $this->routeMethod = $method;
        return $this;
    }
}
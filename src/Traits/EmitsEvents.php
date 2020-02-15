<?php 
namespace Vuravel\Elements\Traits;

trait EmitsEvents {

    public function emitFromAction($event, $data = null)
    {
        $this->action = 'emitFrom';
        $this->event = $event;
        $this->emitPayload = $data;
    }

    /**
     * Emits a Vue event when clicked with an optional payload as the event's first parameter.
     *
     * @param      string  $event  The event name
     * @param      array|null  $data   The optional additional data
     *
     * @return     self  
     */
    public function emitsOnClick($event, $data = null)
    {
        return $this->onClick(function($e) use ($event, $data){
            $e->emitFromAction($event, $data);
        });
    }

    /**
     * Emits a Vue event when blurred with the field's value and an optional payload as the event's first parameter.
     * 
     * @param string $event The event name
     * @param array|null $data The optional additional data
     *
     * @return self
     */
    public function emitsOnBlur($event, $data = null)
    {
        return $this->onBlur(function($e) use ($event, $data){
            $e->emitFromAction($event, $data);
        });
    }

    /**
     * Emits a Vue event when the field's value changes and an optional payload as the event's first parameter.
     * 
     * @param string $event The event name
     * @param array|null $data The optional additional data
     *
     * @return self
     */
    public function emitsOnChange($event, $data = null)
    {
        return $this->onChange(function($e) use ($event, $data){
            $e->emitFromAction($event, $data);
        });
    }

    /**
     * Emits a Vue event on Success with the response as the event's first paramter.
     *
     * @param string  $event  The event name
     * @param array|null $data The optional additional data
     *
     * @return self   
     */
    public function emitsOnSuccess($event, $data = null)
    {
        $this->defaultTrigger()->emitsOnSuccess($event, $data);
        return $this;
    }

    //Internal use only for now. To emit from vl-button, vl-link
    public function emitsDirectOnSuccess($event)
    {
        $this->defaultTrigger()->emitsDirectOnSuccess($event);
        return $this;
    }


    /**
     * Reloads a new empty Form. Useful when wanting to add multiple items with a "Add another item" Button.
     *
     * @return     self   
     */
    public function reloadsFreshForm()
    {
        return $this->emitsOnSuccess('addanother')->keepModalOpen();
    }

    

}
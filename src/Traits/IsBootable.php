<?php 
namespace Vuravel\Elements\Traits;

trait IsBootable {

    /**
     * Construct an Element object and boot it optionally.
     *
     * @return void
     */
    public function __construct($dontBoot = false)
    {
        if(!$dontBoot)
            $this->vlBoot();
    }

    /**
     * Boot an Element object.
     *
     * @return mixed
     */
    protected function vlBoot()
    {
        return $this->createdHook();
    }

    /**
     * A method that gets executed at the beginning of the lifecycle.
     * 
     * @return Vuravel\Form\Form
     */
    protected function createdHook()
    {
        if(method_exists($this, 'created'))
            $this->created();
        return $this;
    }

    /**
     * A method that gets executed after a Form/Catalog finished Booting.
     * 
     * @return Vuravel\Form\Form
     */
    protected function bootedHook()
    {
        if(method_exists($this, 'booted'))
            $this->booted();
        return $this;
    }

    /**
     * A method that gets executed after the component has been prepared.
     *
     * @param mixed $parameter
     * @return Vuravel\Form\Form
     */
    protected function mountedHook($parameter = null)
    {
        if(method_exists($this, 'mounted'))
            $this->mounted($parameter);
        return $this;
    }

    /**
     * A method that gets executed before the model has been saved.
     * 
     * @return Vuravel\Form\Form
     */
    protected function beforeSaveHook($model)
    {
        if(method_exists($this, 'beforeSave'))
            $this->beforeSave($model);
        return $this;
    }

    /**
     * A method that gets executed after the model has been saved (before relationships).
     * 
     * @return Vuravel\Form\Form
     */
    protected function afterSaveHook($model)
    {
        if(method_exists($this, 'afterSave'))
            $this->afterSave($model);
        return $this;
    }

    /**
     * A method that gets executed at the end of the lifecycle (after relationships have been saved).
     * 
     * @return Vuravel\Form\Form
     */
    protected function completedHook($model)
    {
        if(method_exists($this, 'completed'))
            $this->completed($model);
        return $this;
    }


}
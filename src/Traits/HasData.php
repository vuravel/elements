<?php 
namespace Vuravel\Elements\Traits;

trait HasData {

    /**
     * The element's data array.
     *
     * @var array
     */
    public $data = [];

    /**
     * Pass additional data to the element that can be accessed from the Front-end in the `data` property of the object - especially useful if you wish to customize or add new features to the component.
     *
     * @param  array  $data Key/value associative array.
     * @return mixed
     */
    public function data($data)
    {
        if(is_array($data)){
            $this->data = array_merge ($this->data, $data);
            return $this;
        }else{
            return $this->data[$data] ?? null;
        }
    }

}
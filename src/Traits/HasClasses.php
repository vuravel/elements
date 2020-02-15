<?php 
namespace Vuravel\Elements\Traits;


trait HasClasses {

    /**
     * The element's classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * The element's classes string.
     *
     * @var array
     */
    public $class = '';

    /**
     * Adds an array of classes to the element.
     *
     * @param  array|string  $classes
     * @return mixed
     */
    public function addClass($classes)
    {
        if(is_array($classes)){
            $this->classes += $classes;
        }else{
            array_push($this->classes, $classes);
        }
        $this->createClassString();
        return $this;
    }

    /**
     * Sets the class attribute of the element.
     * For multiple classes, use a space-separated string.
     *
     * @param  string  $class The class attribute.
     * @return mixed
     */
    public function class($class = null)
    {
        if($class){            
            $this->classes = [$class];
            $this->createClassString();
            return $this;
        }else{
            return $this->class;
        }
    }

    /**
     * Sets the class attribute for the input element of the field.
     * For multiple classes, use a space-separated string.
     *
     * @param  string  $class The class attribute.
     * @return mixed
     */
    public function inputClass($class)
    {        
        return $this->data(['inputClass' => $class]);
    }

    protected function createClassString()
    {
        $this->class = implode(' ',$this->classes);
    }

}
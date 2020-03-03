<?php

namespace Vuravel\Elements;

use Vuravel\Elements\Contracts\BasicAttributes;
use Illuminate\Support\Str;
use BadMethodCallException;

abstract class Element implements BasicAttributes
{
    use \Vuravel\Elements\Traits\HasHtmlAttributes;
    use \Vuravel\Elements\Traits\HasId;
    use \Vuravel\Elements\Traits\HasClasses;
    use \Vuravel\Elements\Traits\HasData;
    use \Vuravel\Elements\Traits\HasStyles;
    use \Vuravel\Elements\Traits\HasDuskSelector;
    use \Vuravel\Elements\Traits\HasAnimation;
    use \Vuravel\Elements\Traits\PerformsAjax;
    use \Vuravel\Elements\Traits\EmitsEvents;
    
    use \Vuravel\Elements\Traits\HasRoute;

    use \Vuravel\Elements\Traits\HasStore;
    use \Vuravel\Elements\Traits\IsBootable;


    use \Vuravel\Elements\Traits\HasTriggers;

    /**
     * The related Vue component name.
     *
     * @var string
     */
    public $component;

    /**
     * The component's label.
     *
     * @var string
     */
    public $label;


    /**
     * Constructs a Vuravel\Form\Component object
     *
     * @param  string $label
     * @return void
     */
    public function __construct($label = '')
    {
        $this->vlInitialize($label);
    }

    /**
     * Another way to construct a Vuravel\Form\Component object
     *
     * @param  mixed $arguments
     * @return void
     */
    public static function form(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Initializes a vuravel component.
     *
     * @param  string $label
     * @return void
     */
    protected function vlInitialize($label)
    {
        $this->id(($label ? Str::slug(strip_tags($label)) : class_basename($this)).uniqid(), false);
        $this->label = $label ? __($label) : '';
    }

    /**
     * Override the default Vue component template. 
     * Note that the Vue component file name will have 'Vl' apprended. For example, a $component of 'EditLink' will point to the file VlEditLink.vue.
     *
     * @param      string  $component  The vue component name. 
     *
     * @return     self  
     */
    public function component($component)
    {
        $this->component = $component;
        return $this;
    }

    /**
     * Overwrite the initially set label.
     *
     * @param  string $label
     * @return Element
     */
    public function labelNonStatic($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Overwrite the initially set label.
     *
     * @param  string $label
     * @return Element
     */
    public static function labelStatic(...$arguments)
    {
        return static::form(...$arguments);
    }

    /**
     * Adds an icon before component's label.
     *
     * @param  string $iconClass This is the class in &lt;i class="...">&lt;/i>
     * @return Element
     */
    public function iconNonStatic($iconClass)
    {
        $this->data(['icon' => $iconClass]);
        return $this;
    }

    /**
     * Adds an icon before component's label.
     *
     * @param  string $iconClass This is the class in &lt;i class="...">&lt;/i>
     * @return Element
     */
    public static function iconStatic($iconClass)
    {
        return static::form('')->icon($iconClass);
    }

    /**
     * Toggles another item identified by the $id on click if it's a Trigger, or on blur if it's a Field.
     *
     * @param      string  $id     The id of the element to be toggled.
     *
     * @return     self 
     */
    public function togglesId($id)
    {
        $this->data(['togglesId' => $id]);
        return $this;
    }

    /**
     * Sets a specific col value when the element is used inside `Columns`. By default, the columns get equal widths. A 12 column grid system is used here (same as Bootstrap CSS). For example:
     * <php>Columns::form(
     *    Html::form('Column 1')->col('col-8'),
     *    Html::form('Column 2')->col('col-4')
     * )</php>
     *
     * @param      string  $col    The col attribute. Ex: `col-8`.
     *
     * @return     self 
     */
    public function col($col)
    {
        $this->data(['col' => $col]);
        return $this;
    }

    /**
     * Checks if the component uses a certain trait (recursive: parent and traits of traits included).
     *
     * @param  string $traitClass
     * @return Boolean
     */
    public function usesTrait($traitClass)
    {
        return in_array($traitClass, class_uses_recursive($this));
    }

    /**
     * Convert the model to its string representation in JSON
     * Mostly, useful when echoing in blade for example.
     *
     * @return string
     */
    public static function renderStatic()
    {
        return static::vueRender(new static());
    }

    /**
     * Convert the model to its string representation in JSON
     * Mostly, useful when echoing in blade for example.
     *
     * @return string
     */
    public function renderNonStatic()
    {
        return static::vueRender($this);
    }

    /**
     * Handle dynamic method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if(in_array($method, static::duplicateStaticMethods())){
            $method .= 'Static';
            return (new static(true))->$method(...$parameters);
        }
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if(in_array($method, static::duplicateStaticMethods())){
            $method .= 'NonStatic';
            return $this->$method(...$parameters);
        }
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    public static function duplicateStaticMethods()
    {
        return ['label', 'icon'];
    }

    /**
     * Displays the rendered Vue component.
     * Mostly, useful when echoing in blade for example.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
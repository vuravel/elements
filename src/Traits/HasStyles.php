<?php 
namespace Vuravel\Elements\Traits;


trait HasStyles {

    /**
     * The element's styles.
     *
     * @var array
     */
    protected $styles = [];

    /**
     * The element's styles string.
     *
     * @var array
     */
    public $style = '';

    /**
     * Adds one or more ";"-separated styles to the element. 
     *
     * @param  string  $styles
     * @return mixed
     */
    public function addStyle($styles)
    {
        $this->styles[] = $styles;
        $this->createStyleString();
        return $this;
    }

    /**
     * Sets the style attribute of the element.
     * For multiple styles, use a ";" separated string.
     *
     * @param  string  $style The CSS style attribute.
     * @return mixed
     */
    public function style($style = null)
    {
        if($style){            
            $this->styles = [$style];
            $this->createStyleString();
            return $this;
        }else{
            return $this->style;
        }
    }

    /**
     * Sets the style attribute for the input element of the field.
     * For multiple styles, use a ";" separated string.
     *
     * @param  string  $style The CSS style attribute.
     * @return mixed
     */
    public function inputStyle($style)
    {        
        return $this->data(['inputStyle' => $style]);
    }

    protected function createStyleString()
    {
        $this->style = implode(';', $this->styles);
    }

}
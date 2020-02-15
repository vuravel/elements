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
     * Adds an array of styles to the element.
     *
     * @param  array|string  $styles
     * @return mixed
     */
    public function addStyle($styles)
    {
        if(is_array($styles)){
            $this->styles += $styles;
        }else{
            array_push($this->styles, $styles);
        }
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
<?php 
namespace Vuravel\Elements\Traits;

trait HasId {

    /**
     * The element's HTML id.
     *
     * @var string
     */
    public $id;

    /**
     * Sets the id attribute of the element.
     *
     * @param      string  $id     The id attribute.
     *
     * @return     self
     */
    public function id($id, $displayIdAttribute = true)
    {
        //For when calling a Bootable object and chaining ->id() 
        if($displayIdAttribute && method_exists($this, 'removeFromSession'))
            $this->removeFromSession();

        $this->id = $id;

        if($displayIdAttribute && method_exists($this, 'pushToSession'))
            $this->pushToSession();

        return $this->data([
            'displayIdAttribute' => $displayIdAttribute
        ]);
    }

}
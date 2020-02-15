<?php 
namespace Vuravel\Elements\Traits;

trait HasStore {

    /**
     * The element's session store (this data is not sent to Front-end)
     *
     * @var array
     */
    protected $store = [];

    /**
     * Assign additional session data to the element. Or retrieve it if parameter is a string key.
     *
     * @param  mixed  $sessionData
     * @return mixed
     */
    public function storeNonStatic($sessionData = null)
    {
        if(is_array($sessionData)){
            $this->store = array_merge ($this->store, $sessionData);
            return $this;
        }else{
            return $sessionData ? ($this->store[$sessionData] ?? null) : $this->store;
        }
    }

    /**
     * Put session data in the store and return a new booted Instance.
     *
     * @param  array  $sessionData
     * @return mixed
     */
    public static function storeStatic($sessionData)
    {
        return with(new static(true))->store($sessionData)->bootToSession();
    }

}
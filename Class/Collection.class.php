<?php

class Collection implements ArrayAccess,IteratorAggregate{

    private $items;


    function __construct(array $items)
    {
        $this->items = $items;
    }



    public function set($key,$value)
    {
        $this->items[$key] = $value;
        return $this->items;
    }


    public function get($key)
    {
        return ($this->exists($key))?$this->items[$key]:false;
    }


    public function isAlphaNumeric($key)
    {
        return preg_match("#^\p{L}(\p{L}+[- \']?)*\p{L}$#ui", $this->items[$key]) ?true:false;
    }



    public  function exists($key)
    {
        return array_key_exists($key,$this->items);
    }



    public function  lists($key,$value)
    {
        $result=array();

        foreach($this->items as $item)

            $result[$item[$key]]=$item[$value];


        return new Collection($result);
    }



    public function extract($key)
    {
        $result=array();
        foreach($this->items as $item)
            $result[]=$item[$key];

        return $result;
    }



    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }



    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }


    public function offsetGet($offset)
    {
        return $this->get($offset);
    }


    public function offsetSet($offset, $value)
    {
         $this->set($offset,$value);
    }


    public function offsetUnset($offset)
    {
        if($this->exists($offset))
            unset($this->items);

    }


}
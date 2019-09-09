<?php

namespace CodexSoft\Code\Wrappers;

use CodexSoft\Code\Helpers\Vars;

class ArrayBag implements \ArrayAccess, \IteratorAggregate, \Countable
{

    private $array = [];

    function __construct($array = [])
    {
        $this->array = $array;
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /**
     * @param $offset
     * @param $value
     *
     * @throws \Exception
     */
    public function set($offset, $value)
    {
        if (!Vars::isInteger($offset))
            throw new \Exception('key must be integer in ArrayBag!');
        $this->array[$offset] = $value;
    }

    /**
     * @param $array
     *
     * @return $this
     * @throws \Exception
     */
    public function replaceWith($array)
    {
        $this->array = [];
        foreach ($array as $key => $value)
            $this->set($key, $value);
        return $this;
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function has($offset)
    {
        return array_key_exists($offset, $this->array);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function remove($offset)
    {
        if (array_key_exists($offset, $this->array))
            unset($this->array[$offset]);
    }

    public function offsetGet($offset)
    { // todo: throw exception?
        return $this->get($offset);
    }

    public function get($offset, $default = null)
    {
        if (array_key_exists($offset, $this->array))
            return $this->array[$offset];
        return $default;
    }

    public function push($value)
    {
        array_push($this->array, $value);
        return $this;
    }

    public function add($value)
    {
        return $this->push($value);
    }

    public function pop()
    {
        return array_pop($this->array);
    }

    public function shift()
    {
        return array_shift($this->array);
    }

    /**
     * "Подложить" элемент в начало массива
     *
     * @param $value
     *
     * @return $this
     */
    public function unshift($value)
    {
        array_unshift($this->array, $value);
        return $this;
    }

    public function count()
    {
        return count($this->array);
    }

    public function all()
    {
        return $this->array;
    }

}
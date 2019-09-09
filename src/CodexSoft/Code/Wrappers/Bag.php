<?php

namespace CodexSoft\Code\Wrappers;

use Symfony\Component\HttpFoundation\ParameterBag;

class Bag extends ParameterBag implements \ArrayAccess
{

    public function set($key, $value) {
        parent::set($key, $value);
        return $this;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->parameters[] = $value;
        } else {
            $this->set($offset,$value);
        }
    }

    public function offsetExists($offset) {
        return $this->has($offset);
    }

    public function offsetUnset($offset) {
        $this->remove($offset);
    }

    public function offsetGet($offset) { // todo: throw exception?
        return $this->get($offset);
    }

    /**
     * @param array ...$keys
     *
     * @return bool
     */
    public function hasNotEmpty( ...$keys ) {

        if ( count($keys) == 1 && is_array( $keys[0] ) )
            $keys = $keys[0];

        foreach ( $keys as $key ) {
            if ( !$this->has( $key ) ) return false;
            if ( !$this->get( $key ) ) return false;
        }

        return true;

    }

    public static function from( array $array ) {
        return new static( $array );
    }

    public function mergeWith( array $array ) {
        $this->parameters = array_merge( $this->parameters, $array );
    }

    //public function replaceWith( array $array ) {
    //    $this->parameters = array_replace( $this->parameters, $array );
    //}

    public function unique() {
        $this->parameters = array_unique($this->parameters);
    }

    public function filterCustom( $filterFunction ) {
        $this->parameters = array_filter( $this->parameters, $filterFunction );
    }

}
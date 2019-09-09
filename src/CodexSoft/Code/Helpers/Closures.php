<?php

namespace CodexSoft\Code\Helpers;

class Closures
{

    /**
     * @param \Closure $callback
     * @param array $vars
     * @param null $owner
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public static function executeUsingArgumentsArray( \Closure $callback, $vars = [], $owner = null )
    {

        $reflection = new \ReflectionFunction( $callback );
        $reflectionParameters = $reflection->getParameters();

        $callParameters = [];

        foreach ( (array) $reflectionParameters as $reflectionParameter ) {
            $argumentName = $reflectionParameter->getName();
            $defaultValue = $reflectionParameter->isOptional() ? $reflectionParameter->getDefaultValue() : null;
            if ( $argumentName === 'vars' )
                array_push( $callParameters, $vars );
            else
                array_push( $callParameters, Arrays::tool()->valueOfKey( $vars, $reflectionParameter->getName(), $defaultValue ) );
        };

        return $owner
            ? $callback->call($owner, ...$callParameters )
            : $reflection->invokeArgs( $callParameters );

    }

}
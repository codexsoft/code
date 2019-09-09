<?php

namespace CodexSoft\Code\Wrappers\Parameter;

interface ParametersInterface
{

    /**
     * Returns the parameters.
     *
     * @return array An array of parameters
     */
    public function all(): array;

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys(): array;

    /**
     * Replaces the current parameters by a new set.
     *
     * @param array $parameters An array of parameters
     */
    public function replace(array $parameters = array());

    /**
     * Adds parameters.
     *
     * @param array $parameters An array of parameters
     */
    public function add(array $parameters = array());

    /**
     * Returns a parameter by name.
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Sets a parameter by name.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     */
    public function set($key, $value);

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key): bool;

    /**
     * Removes a parameter.
     *
     * @param string $key The key
     */
    public function remove($key);

    /**
     * Returns an iterator for parameters.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator(): \ArrayIterator;

    /**
     * @param array ...$keys
     *
     * @return bool
     */
    public function hasNotEmpty( ...$keys );

    /**
     * Returns the number of parameters.
     *
     * @return int The number of parameters
     */
    public function count(): int;

    public static function from( array $array ): ParametersInterface;

    public function mergeWith( array $array );

    public function unique();

    public function filterCustom( $filterFunction );

}
<?php

namespace CodexSoft\Code\Helpers;

class Vars
{

    /**
     *
     * This function internally swaps the contents between
     * two simple variables using 'passing by reference'.
     *
     * Some programming languages have such a swap function
     * built in, but PHP seems to lack such a function.  So,
     * one was created to fill the need.  It only handles
     * simple, single variables, not arrays, but it is
     * still a very handy tool to have.
     *
     * No value is actually returned by this function, but
     * the contents of the indicated variables will be
     * exchanged (swapped) after the call.
     *
     * $a = 123.456;
     * $b = 'abcDEF';
     * swap($a,$b);
     *
     * @param $arg1
     * @param $arg2
     */
    public static function swap(&$arg1, &$arg2): void
    {
        // Swap contents of indicated variables.
        $w = $arg1;
        $arg1 = $arg2;
        $arg2 = $w;
    }

    public static function isInteger($input): bool
    {
        return ctype_digit((string) $input);
    }

}
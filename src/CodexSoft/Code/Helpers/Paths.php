<?php

namespace CodexSoft\Code\Helpers;

class Paths
{

    /**
     * TODO: не следует ли воспользоваться DIRECTORY_SEPARATOR?
     * Похоже, это работает только при работе с абсолютными физическими путями
     * @param $compareTo
     * @param $path
     *
     * @return string
     */
    static public function getRelativePath( $compareTo, $path ) {

        $path = str_replace('\\','/',$path);
        $compareTo = str_replace('\\','/',$compareTo);

        // clean arguments by removing trailing and prefixing slashes
        if ( substr( $path, -1 ) == '/' ) {
            $path = substr( $path, 0, -1 );
        }

        if ( substr( $path, 0, 1 ) == '/' ) {
            $path = substr( $path, 1 );
        }

        if ( substr( $compareTo, -1 ) == '/' ) {
            $compareTo = substr( $compareTo, 0, -1 );
        }

        if ( substr( $compareTo, 0, 1 ) == '/' ) {
            $compareTo = substr( $compareTo, 1 );
        }

        // simple case: $compareTo is in $path
        if ( strpos( $path, $compareTo ) === 0 ) {
            $offset = strlen( $compareTo ) + 1;
            return substr( $path, $offset );
        }

        $relative  = array(  );
        $pathParts = explode( '/', $path );
        $compareToParts = explode( '/', $compareTo );

        foreach( $compareToParts as $index => $part ) {

            if ( isset( $pathParts[$index] ) && $pathParts[$index] == $part )
                continue;

            $relative[] = '..';

        };

        foreach( $pathParts as $index => $part ) {

            if ( isset( $compareToParts[$index] ) && $compareToParts[$index] == $part )
                continue;

            $relative[] = $part;

        };

        return implode( '/', $relative );

    }

}
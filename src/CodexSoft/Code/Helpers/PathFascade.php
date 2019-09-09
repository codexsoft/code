<?php

namespace CodexSoft\Code\Helpers;

/**
 * path generator fascade
 */
class PathFascade
{

    /**
     * @param string $path
     *
     * @return bool
     */
    public static function isUrl( $path ) {
        return Path::tool()->isUrl( $path );
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public static function isAbsolute( $path ) {
        return Path::tool()->isAbsolute( $path );
    }

}
<?php

namespace CodexSoft\Code\Helpers;

use CodexSoft\Code\Traits\StaticAccess;

/**
 * path generator fascade
 */
class Path
{

    use StaticAccess;

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isUrl( $path ) {
        return Strings::startsWith( '//', $path )
        || Strings::startsWith( 'http://', $path )
        || Strings::startsWith( 'https://', $path );
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function isAbsolute( $path ) {
        return Strings::startsWith( '/', $path );
    }

}
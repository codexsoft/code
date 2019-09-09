<?php

namespace CodexSoft\Code\Helpers;

use CodexSoft\Code\Traits\StaticAccess;

class Debug
{

    use StaticAccess;

    public function readCodeLines( $absoluteFilePath, $lineNumber, $wrappingLinesAmount = null ) {

        if ($wrappingLinesAmount === null)
            $wrappingLinesAmount = 5;

        try {
            $file = new \SplFileObject($absoluteFilePath);
        } catch ( \Exception $e ) {
            return [];
        }

        $resultLines = [];

        $firstLine = max($lineNumber - $wrappingLinesAmount,0);
        $lastLine = $lineNumber + $wrappingLinesAmount;

        for ( $currentLine = $firstLine; $currentLine <= $lastLine; $currentLine++ ) {
            if ($currentLine<0) continue;
            $file->seek( $currentLine ); // zero-based!
            $resultLines[] = $file->current();
        }

        return $resultLines;

    }

    public function errorTypeToString($type) {

        // todo вот one-liner еще преложили, надо протестировать
        //return array_flip(array_slice(get_defined_constants(true)['Core'], 1, 15, true))[$type];

        $errorTitle = [
            E_ERROR => 'E_ERROR', // 1
            E_WARNING => 'E_WARNING', // 2
            E_PARSE => 'E_PARSE', // 4
            E_NOTICE => 'E_NOTICE', // 8
            E_CORE_ERROR => 'E_CORE_ERROR', // 16
            E_CORE_WARNING => 'E_CORE_WARNING', // 32
            E_COMPILE_ERROR => 'E_COMPILE_ERROR', // 64
            E_COMPILE_WARNING => 'E_COMPILE_WARNING', // 128
            E_USER_ERROR => 'E_USER_ERROR', // 256
            E_USER_WARNING => 'E_USER_WARNING', // 512
            E_USER_NOTICE => 'E_USER_NOTICE', // 1024
            E_STRICT => 'E_STRICT', // 2048
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR', // 4096
            E_DEPRECATED => 'E_DEPRECATED', // 8192
            E_USER_DEPRECATED => 'E_USER_DEPRECATED', // 16384
        ];

        return ( array_key_exists( $type, $errorTitle ) )
            ? $errorTitle[ $type ] : $type;

    }

    /**
     * преобразует исключение в кастомное текстовое представление
     * @param \Throwable $exception
     *
     * @return string
     */
    function throwableToString(\Throwable $exception) {

        // these are our templates
        $traceline = "#%s %s(%s): %s(%s)";
        $msg = "\nUncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

        // alter your trace as you please, here
        $trace = $exception->getTrace();
        foreach ($trace as $key => $stackPoint) {
            // I'm converting arguments to their type
            // (prevents passwords from ever getting logged as anything other than 'string')
            if ( array_key_exists( 'args', $trace[$key] ) )
                $trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
            else $trace[$key]['args'] = [];
        }

        // build your tracelines
        $result = array();
        $key = 0;
        foreach ($trace as $key => $stackPoint) {
            $result[] = sprintf(
                $traceline,
                $key,
                @isset( $stackPoint['file'] ) ? $stackPoint['file'] : '',
                @isset( $stackPoint['line'] ) ? $stackPoint['line'] : '',
                $stackPoint['function'],
                implode(', ', $stackPoint['args'])
            );
        }
        // trace always ends with {main}
        $result[] = '#'.++$key.' {main}';

        // write tracelines into main template
        $msg = sprintf(
            $msg,
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            implode("\n", $result),
            $exception->getFile(),
            $exception->getLine()
        );

        return $msg;

    }

    public function switchOffXdebug() {
        if ( function_exists('xdebug_start_error_collection') ) {
            xdebug_start_error_collection();
            xdebug_disable();
        }
    }

}
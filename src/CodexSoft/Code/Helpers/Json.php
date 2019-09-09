<?php

namespace CodexSoft\Code\Helpers;

class Json
{

    const ENCODE_PARAMETERS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE;

    /**
     * @param $content
     *
     * @return string|false
     */
    public static function encode($content)
    {
        return json_encode($content, self::ENCODE_PARAMETERS);
    }

    /**
     * Decodes the given JSON string into a PHP data structure.
     * @param string $json the JSON string to be decoded
     * @param boolean $asArray whether to return objects in terms of associative arrays.
     * @return mixed the PHP data
     * @throws \Exception if there is any decoding error
     */
    public static function decode($json, $asArray = true) {

        if (\is_array($json)) {
            throw new \Exception('Invalid JSON data.');
        }
        $decode = json_decode((string) $json, $asArray);
        static::handleJsonError(json_last_error());

        return $decode;

    }

    /**
     * Handles [[encode()]] and [[decode()]] errors by throwing exceptions with the respective error message.
     *
     * @param integer $lastError error code from [json_last_error()](http://php.net/manual/en/function.json-last-error.php).
     * @throws \Exception if there is any encoding/decoding error.
     * @since 2.0.6
     */
    protected static function handleJsonError($lastError) {

        switch ($lastError) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                throw new \Exception('The maximum stack depth has been exceeded.');
            case JSON_ERROR_CTRL_CHAR:
                throw new \Exception('Control character error, possibly incorrectly encoded.');
            case JSON_ERROR_SYNTAX:
                throw new \Exception('Syntax error.');
            case JSON_ERROR_STATE_MISMATCH:
                throw new \Exception('Invalid or malformed JSON.');
            case JSON_ERROR_UTF8:
                throw new \Exception('Malformed UTF-8 characters, possibly incorrectly encoded.');
            default:
                throw new \Exception('Unknown JSON decoding error.');
        }
    }

}
<?php

namespace CodexSoft\Code\Traits;

use Carbon\Carbon;
use CodexSoft\Code\Constants;

trait Arrayable
{

    protected $includeVarsOfParentClassToArray = true;

    /**
     * @param null|array $whiteList list of fields to export
     * @param null|array $blackList list of fields to not to export
     * @param bool $includeInheritedVars should be inherited properties exported?
     *
     * @return array
     */
    public function asArray($whiteList = null, $blackList = null, $includeInheritedVars = true): array
    {

        $resultArray = [];
        $vars = get_object_vars($this);
        $parent_vars = get_class_vars(static::class);

        foreach( $vars as $var => $value ) {

            if ( $this->includeVarsOfParentClassToArray || !array_key_exists( $var, $parent_vars ) ) {
                $resultArray[$var] = self::valueToString($value);
            }

        }

        return $resultArray;
        //return Json::encode( $exportArray );

    }

    public static function valueToString($value): string
    {

        if (is_scalar($value)) {
            return (string) $value;
        }

        if ($value instanceof \DateTime) {
            return Carbon::instance($value)->format(Constants::FORMAT_YMD_HIS);
        }

        try {
            return (string) $value;
        } catch (\Throwable $e) {
            return '<Object of class '.\get_class($e).'>';
        }

        //if (\is_object($value)) {
        //    $stringifiedValue = \get_class($value);
        //    if (\method_exists($value,'getId')) {
        //        $stringifiedValue .= ' (id = '.\var_export($value->getId(),true).')';
        //    } elseif ($value instanceof \DateTime) {
        //        $stringifiedValue .= ' ('.$value->format(Constants::FORMAT_YMD_HIS).' '.$value->getTimezone()->getName().')';
        //    }
        //} elseif (\is_array($value)) {
        //    $stringifiedValue = \print_r($value,true);
        //} else {
        //    $stringifiedValue = '('.\gettype($value).') '.\var_export($value,true);
        //}

    }

}
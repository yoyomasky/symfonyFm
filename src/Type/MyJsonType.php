<?php
/**
 * @author: sma01
 * @since: 2024/6/20
 * @version: 1.0
 */
namespace App\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class MyJsonType extends JsonType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $jsonVal = json_decode($value, true);

        if (is_array($jsonVal) === true) {
            return parent::convertToPHPValue($value, $platform);
        }

        $value = str_replace("\t", '', $value);
        $value = str_replace("'", '', $value);

        $jsonVal = json_decode($value, true);

        if (is_array($jsonVal) === true) {
            return parent::convertToPHPValue($value, $platform);
        }

        return array();
    }
}
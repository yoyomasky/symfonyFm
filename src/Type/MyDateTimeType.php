<?php
/**
 * @author: sma01
 * @since: 2024/6/20
 * @version: 1.0
 */

namespace App\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;

class MyDateTimeType extends DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): \DateTime|\DateTimeInterface|null
    {
        if ($value === '0000-00-00 00:00:00' || $value === null) {
            return null;
        }

        return parent::convertToPHPValue($value, $platform);
    }
}
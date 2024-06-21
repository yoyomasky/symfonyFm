<?php
/**
 * @author: sma01
 * @since: 2024/6/19
 * @version: 1.0
 */


namespace App\Util;

class Util
{
    public static function isDTOError(string $message): bool
    {
        return 1 === preg_match('/App\\\Model\\\Query/', $message);
    }

    public static function parseSmartstoreSyncCode($prdSyncCode): ?array
    {
        if (true !== ValidUtil::isString($prdSyncCode)) {
            return null;
        }
        $prdSyncCode = str_replace(array("\r\n", "\r", "\n"), '', $prdSyncCode);
        if (str_contains($prdSyncCode, 'result:')) {
            $syncCode         = json_decode(str_replace('result:', '', $prdSyncCode), true);
            $originProductNo  = $syncCode['originProductNo'];
            $channelProductNo = $syncCode['smartstoreChannelProductNo'];
        } else {
            $syncCode         = explode('&', $prdSyncCode);
            $originProductNo  = $syncCode[0];
            $channelProductNo = $syncCode[1];
        }
        return ['origin_product_no' => $originProductNo, 'channel_product_no' => $channelProductNo];
    }

    public static function getIds(array $data, string $key = 'id'): array
    {
        if (false === ArrayUtil::isValid($data)) {
            return [];
        }
        $row = $data[0];
        if (true === ArrayUtil::isValid($row)) {
            return array_column($data, $key);
        }
        $ids = [];
        if (true === is_object($row)) {
            $method = 'get' . ucfirst($key);
            foreach ($data as $record) {
                if (true === method_exists($record, $method)) {
                    $ids[] = $record->$method();
                }
            }
        }
        return $ids;
    }

    public static function dateInterval(string $end = 'now', string $internal = 'P0D', $onlyDate = false): array
    {
        try {
            $endDate   = new \DateTime($end);
            $beginDate = clone $endDate;
            $interval  = new \DateInterval($internal);
            $beginDate->sub($interval);
            if ($onlyDate === true) {
                $beginDate = $beginDate->format('Y-m-d');
                $endDate   = $endDate->format('Y-m-d');
            } else {
                $beginDate = $beginDate->format('Y-m-d') . ' 00:00:00';
                $endDate   = $endDate->format('Y-m-d') . ' 23:59:59';
            }

            return [$beginDate, $endDate];
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function num2alpha(int $num): string
    {
        $alpha = "";
        while ($num > 0) {
            $num--;
            $remainder = $num % 26;
            $alpha     = chr(97 + $remainder) . $alpha;
            $num       = floor($num / 26);
        }
        return $alpha;
    }

    public static function objectToArray($object, array $ignore = []): mixed
    {
        if (is_object($object)) {
            $reflectionClass = new \ReflectionClass(get_class($object));
            $array           = [];

            foreach ($reflectionClass->getProperties() as $property) {
                if (true === ArrayUtil::isValid($ignore) && in_array($property->getName(), $ignore, true)) {
                    continue;
                }
                $value = $property->getValue($object);

                if (is_object($value) || is_array($value)) {
                    $value = self::objectToArray($value, $ignore); // 递归调用
                }

                $array[$property->getName()] = $value;
            }

            return $array;
        } elseif (is_array($object)) {
            $result = [];
            foreach ($object as $key => $value) {
                $result[$key] = self::objectToArray($value, $ignore); // 递归调用
            }
            return $result;
        } else {
            return $object; // 非对象和数组直接返回
        }
    }

    /**
     * 转换
     * @param $oldStr
     * @return string
     */
    public static function switchStr($oldStr)
    {
        if ($oldStr === true) {
            return 'true';
        } else {
            return 'false';
        }
    }
}
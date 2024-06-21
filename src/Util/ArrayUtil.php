<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */

namespace App\Util;

class ArrayUtil
{
    public static function isValid($data): bool
    {
        return is_array($data) && count($data) > 0;
    }

    public static function arrayColumnAsKey($array, $columnKey): array
    {
        $result = [];
        foreach ($array as $item) {
            if (isset($item[$columnKey])) {
                $result[$item[$columnKey]] = $item;
            }
        }
        return $result;
    }

    public static function getNestedValue(array $array, $keys, $delimiter = '.'): mixed
    {
        if (is_array($keys)) {
            $currentKey = array_shift($keys);

            if (isset($array[$currentKey])) {
                return self::getNestedValue($array[$currentKey], $keys, $delimiter);
            } else {
                return null;
            }
        } else {
            return $array[$keys] ?? null;
        }
    }

    public static function setNestedValue(array &$array, array $keys, $value): void
    {
        $currentArray = &$array;

        foreach ($keys as $key) {
            if (!isset($currentArray[$key]) || !is_array($currentArray[$key])) {
                $currentArray[$key] = [];
            }
            $currentArray = &$currentArray[$key];
        }

        $currentArray = $value;
    }

    public static function convertNullToEmptyStringAndCamelToSnake(array &$array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $snakeKey = self::camelToSnake($key);
            if (is_array($value)) {
                $result[$snakeKey] = self::convertNullToEmptyStringAndCamelToSnake($value);
            } else {
                $result[$snakeKey] = $value === null ? '' : $value;
            }
        }
        return $result;
    }

    public static function camelToSnake(string $input): string
    {
        if (preg_match('/[A-Z]/', $input) === 0) {
            return $input;
        }
        $pattern = '/([a-z])([A-Z])/';
        return strtolower(preg_replace_callback($pattern, function ($a) {
            return $a[1] . "_" . strtolower($a[2]);
        }, $input));
    }
}
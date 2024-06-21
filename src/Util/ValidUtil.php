<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */


namespace App\Util;

class ValidUtil
{
    public static function isUrl(?string $url): bool
    {
        if (false === is_string($url)) {
            return false;
        }
        return false !== filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function isPrice($price, bool $zero = false): bool
    {
        if (false === is_numeric($price)) {
            return false;
        }
        if (true === $zero) {
            return $price >= 0;
        }

        return $price > 0;
    }

    public static function isStock($stock): bool
    {
        return false !== filter_var($stock, FILTER_VALIDATE_INT) && $stock >= 0;
    }

    public static function hasChinese(string $str): bool
    {
        return preg_match("/\p{Han}+/u", $str);
    }

    public static function hasKorean(string $str): bool
    {
        return preg_match('/\p{Hangul}+/u', $str);
    }

    public static function isString($str): bool
    {
        return is_string($str) && ('' !== trim($str));
    }

    /**
     * 是否有值，true 有值，false 无值
     * @param $str
     * @return bool
     */
    public static function isStrNull($str): bool
    {
        return empty($str) === false && strlen($str) > 0;
    }
}
<?php

namespace stlswm\LaravelSupplement\Helper;

/**
 * Class RandomString
 * @package  stlswm\LaravelSupplement\Helper
 * @author   George
 * @datetime 2019/7/16 10:25
 * @annotation
 */
class RandomString
{
    /**
     * @var string
     * @annotation 随机字符串种子
     */
    public static $seek = '01234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';


    /**
     * @param int $length
     * @return string
     * @annotation 生成传入长度的随机字符串
     */
    public static function getStr(int $length = 32): string
    {
        return substr(str_shuffle(self::$seek), 0, $length);
    }

    /**
     * @param int $length
     * @return string
     * @annotation
     */
    public static function buildStrForTime(int $length = 18): string
    {
        return date('YmdHis') . substr(str_shuffle(self::$seek), 0, $length);
    }
}
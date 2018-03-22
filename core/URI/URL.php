<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 20.03.18
 * Time: 17:43
 */

namespace core\URI;

/**
 * Class URL
 * @package core\URI
 */
class URL
{
    /**
     * @var array
     */
    private static $URL = [];

    /**
     * @var int
     */
    private static $pointer = 0;


    /**
     * @return array
     */
    public static function getURL(): array
    {
        return self::$URL;
    }

    /**
     * @param int $pointer
     * @return bool|mixed
     */
    public static function getURLPointer(int $pointer = 0)
    {
        return self::$URL[$pointer] ?? false;
    }

    /**
     * @return bool|mixed
     */
    public static function getURLPointerNow()
    {
        return self::$URL[self::$pointer] ?? false;
    }

    /**
     * @return string
     */
    public static function getFullURLPointerNow() : string
    {
        $URL = Array();
        for ($i = 0; $i <= self::$pointer; $i++) {
            if (!isset(self::$URL[$i])) {
                break;
            }
            $URL[] = self::$URL[$i];
        }
        return implode('/', $URL);
    }

    /**
     * @param array $URL
     */
    public static function setURI(array $URL): void
    {
        self::$URL = $URL;
    }

    /**
     * @param int $pointer
     */
    public static function setPointer(int $pointer): void
    {
        self::$pointer = $pointer;
    }

    /**
     * увеличивает поинтер
     */
    public static function plusPointer(): void
    {
        self::$pointer++;
    }

    /**
     * @return int
     */
    public static function getPointer(): int
    {
        return self::$pointer;
    }

}
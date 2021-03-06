<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:04
 */

namespace core\component\application;

/**
 * Class AControllers
 * @package core\component\application
 */
abstract class AControllers extends AApplication
{
    /** @var string URL путь */
    protected static $pageURL   = Array();

    /** @var mixed|int|false Колличество подуровней */
    public static $countSubURL  =   0;

    /** @var array подуровни */
    protected static $subURL  =   Array();

    /** @var string шаблон */
    public  $template = 'basic';


    /**
     * Задает подстраницы
     * @param array $subURL подстраницы
     */
    public static function setSubURL(array $subURL): void
    {
        self::$subURL = $subURL;
    }

    /**
     * Отдает подстраницы
     *
     * @param mixed|int|boolean $level уровень URL
     *
     * @return array|bool подстраницы
     */
    public static function getSubURL($level = false)
    {
        if ($level === false) {
            return self::$subURL;
        }
        return self::$subURL[$level] ?? false;
    }

    /**
     * Задает URL страницы
     * @param string $URL URL
     */
    public static function setPageURL($URL): void
    {
        self::$pageURL = $URL;
    }

    /**
     * Отдает URL путь страницы
     * @return string
     */
    public static function getPageURL(): string
    {
        return self::$pageURL;
    }

    /**
     * Отдает URL
     * @param mixed|int|boolean $level уровень URL
     * @return mixed|string|boolean URL
     */
    public static function getURL($level = false)
    {
        if ($level === false) {
            return self::$URL;
        }
        return self::$URL[$level] ?? false;
    }
}

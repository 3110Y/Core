<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:23
 */

namespace core\components\generator\connectors;


/**
 * Class IGenerator
 * Коннектор генератора
 * @package core\components\generator\connectors
 */
interface IGenerator
{
    /**
     * Конструирует
     * @param array $scheme схема
     * @return mixed|string|array результат
     */
    public static function construct($scheme);
}
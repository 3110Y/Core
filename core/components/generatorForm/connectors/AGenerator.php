<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:22
 */

namespace core\components\generatorForm\connectors;
use core\components\component\connectors as componentConnectors;


/**
 * Class AGenerator
 * Коннектор генератора
 * @package core\components\generator\connectors
 */
abstract class AGenerator extends componentConnectors\AComponent implements componentConnectors\IComponent
{
    /**
     * фабрика
     * @param string $name имя
     * @param string $item схема
     * @return string результат
     */
    protected static function factory($name, $item)
    {
        //TODO: проверка
        return "\\core\\components\\{$name}\\component";
    }

}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace app\client\controllers;

use core\components\applicationWeb\connectors;


/**
 * Class error
 * Контроллер ошибок
 * @package app\controllers
 */
class error extends connectors\AControllers implements connectors\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubURL  =   false;

    /**
     * Инициализация
     */
    public function init()
    {
        $this->content  = Array(
            'NAME'        =>  'Это 404 контроллер',
            'TITLE'       =>  self::$page['meta_title'],
            'KEYWORDS'    =>  self::$page['meta_keywords'],
            'DESCRIPTION' =>  self::$page['meta_description'],
        );
        header('HTTP/1.0 404 Not Found');
    }


}
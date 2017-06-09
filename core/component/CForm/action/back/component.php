<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 28.05.17
 * Time: 1:53
 */

namespace core\component\CForm\action\back;

use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView as simpleView
};

/**
 * Class component
 * @package core\component\CForm\action\back
 */
class component extends CForm\AAction implements CForm\IAction
{
    public function init()
    {
        // TODO: Implement init() method.
    }

    public function run()
    {
        // TODO: Implement run() method.
    }

    /**
     * генирирует для карточки
     */
    public function one()
    {
        $data   =   Array(
            'URL'   => self::$config['controller']::getPageURL(),
        );
        foreach (self::$data as $key => $value) {
            $k = 'DATA_' . mb_strtoupper($key);
            $data[$k]   =   $value;
        }
        $answer =   simpleView\component::replace(self::getTemplate('tpl/item.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);
    }
}
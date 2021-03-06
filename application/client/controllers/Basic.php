<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:06
 */

namespace application\client\controllers;

use \application\client\model;

use \core\component\{
    registry\registry,
    application\AControllers,
    application\IControllerBasic,
    simpleView\simpleView,
    resources\resources
};


/**
 * Class basic
 * @package application\controllers
 */
class Basic extends AControllers implements IControllerBasic
{
    /**
     * @var string шаблон
     */
    #public $template = 'basic';

    /**
     * Преинициализация
     */
    public function pre(): void
    {
        /** @var model\settings $settings */
        $settings = model\settings::getInstance();
        $path                           =   self::$application['path'];
        $theme                          =   self::$application['theme'];
        self::$content['THEME']         =   "/application/{$path}/theme/{$theme}/";
        self::$content['URL']           =   self::$pageURL;

        /** Meta данные по умолчанию уехали в model\MetaData(title, description, keywords) */
        /** Используйте методы этого класса для установки и получения их */
        /** Шаблоны <meta> есть в basic.tpl */
        #TODO автогенерация тегов метаданных

        $data                           =   Array(
            'MENU'  => self::generationMenu(self::$application['url'])
        );
        $template                       =   self::getTemplate('menu.tpl');
        self::$content['MENU']          =   simpleView::replace($template,  $data);
    }

    /**
     * @param string           $parentURL   родительский URL
     * @param int              $parentID    родительский уровень
     *
     * @return array    меню
     */
    private static function generationMenu($parentURL = '/', $parentID = 0): array
    {
        /** @var \core\component\PDO\PDO $db */
        $db = registry::get('db');
        self::getURL(1);
        $where  =   Array(
            'parent_id' => $parentID,
            '`order_in_menu` != 0',
            '`status` = 1',
            '`error` = 0',
        );

        $query  =   $db->select('client_page', '*', $where, 'order_in_menu');
        $rows   =   Array();
        $parentURL  =   $parentURL !== '/'   ?   $parentURL . '/'  :   $parentURL;
        if ($query->rowCount() > 0) {
            while ($row =  $query->fetch()) {
                $class  =   '';
                $URL    =   $row['url'] === '/'    ?   $parentURL :   $parentURL . $row['url'];
                if ($row['url'] === self::$page['url'] && $row['parent_id'] === self::$page['parent_id']) {
                    $class          .=  'active ';
                }
                $rows[] =   Array(
                    'URL'           =>  $URL,
                    'NAME'          =>  $row['name'],
                    'CLASS'         =>  $class,
                );
            }
        }
        return  $rows;
    }

    /**
     * Постинициализация
     */
    public function post(): void
    {
        self::$content['JS_TOP']        =   resources::getJS();
        self::$content['CSS_TOP']       =   resources::getCSS();
        self::$content['JS_BOTTOM']     =   resources::getJS(false);
        self::$content['CSS_BOTTOM']    =   resources::getCSS(false);

        /** записываем в self::$content метаданные */
        model\MetaData::headMetadata(self::$content);
    }

    /**
     * Инициализация
     */
    public function __construct()
    {
        foreach (self::$page as $key => $value) {
            self::$content['DATA_' . mb_strtoupper($key)]  =  $value;
        }

        /** Изображение для OG разметки */
        #model\Metadata::setImage(self::getTemplate('images/logo.png'));
        /** Затираем тайтл по умолчанию названием раздела либо его собственным тайтлом */
        model\Metadata::setTitle(self::$page['meta_title'] ?: self::$page['name']);
    }

    /**
     * Преинициализация
     */
    public function preAjax(): void
    {
    }

    /**
     * Постинициализация
     */
    public function postAjax(): void
    {
    }
}
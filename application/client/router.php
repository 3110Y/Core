<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:05
 */

namespace application\client;

use \core\component\{
    authentication as authentication,
    application\handler\Web as applicationWeb,
    database\driver\PDO as PDO,
    templateEngine\engine\simpleView as simpleView
};
use core\core;


final class router extends applicationWeb\ARouter implements applicationWeb\IRouter
{

    /**
     * router constructor.
     *
     * @param string $URL           URL приложения
     * @param array  $application   данные приложения
     * @param bool   $isAjaxRequest AJAX запрос
     */
    public function __construct($URL, $application, $isAjaxRequest = false)
    {

        self::$isAjaxRequest        =  $isAjaxRequest;
        self::$URL                  =  $URL;
        self::$application          =  $application;
	    $config                     =   core::getConfig('db.common');
        /** @var PDO\component $db */
        $db =   PDO\component::getInstance($config);
        self::set('db', $db);
        $auth = new authentication\component($db);
        self::set('auth', $auth);
        self::set('view', new simpleView\component());
        self::get('view')->setExtension('tpl');
        self::$structure = $db->selectRows('client_page','*', Array( 'status' => '1'), '`order_in_menu` ASC');
        if (empty(self::$structure)) {
            die('Нет страниц');
        }
    }



    /**
     * Запускает роутинг
     * @return router
     */
    public function run(): router
    {
        /** @var \core\component\authentication\component $auth */
        $auth = self::get('auth');
        $auth->get('authorization')->check();
        $auth->get('object')->register('application_' . self::$application['id'], 'Вход в приложение: ' . self::$application['name']);
        if (!$auth->get('rules')->check('application_' . self::$application['id'])  && self::$URL[1] !== '404') {
            self::redirect(self::$application['url'] . '/404');
        }
        self::selectPage();
        $controllerBasic    =   'application\\' . self::$application['path'] . '\controllers\\' . self::$application['basicController'];
        $controllerBasic    =   new $controllerBasic();
        if ($controllerBasic instanceof applicationWeb\IControllerBasic) {
            $controllerBasic->preInit();
        }
        $path               =   self::$application['path'];
        $controller         =   self::$page['controller'];
        $controller         = "application\\{$path}\\controllers\\{$controller}";
        $controller         = new $controller();
        if ($controller instanceof applicationWeb\IControllers) {
            $controller->init();
        }
        if (!self::$isAjaxRequest && $controllerBasic instanceof applicationWeb\IControllerBasic) {
            $controllerBasic->postInit();
        }
        return $this;
    }

    /**
     *  Запускает роутинг
     * @return string
     */
    public function render()
    {
        if (self::$isAjaxRequest ) {
            return json_encode(self::$content);
        }
        self::get('view')->setTemplate(self::$template);
        self::get('view')->setData(self::$content);
        self::get('view')->run();
        return self::get('view')->get();
    }
}
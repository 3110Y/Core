<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 5:25
 */

namespace application\admin\controllers\system\common;

use \core\component\{
    application\AControllers,
    registry\registry,
    resources\resources
};

/**
 * Class enter
 * @package application\admin\controllers\system\common
 */
class enter extends AControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   0;

    /**
     * @var string шаблон
     */
    public  $template = 'enter';

    /**
     * Инициализация
     */
    public function __construct()
    {

        if (self::$isAjaxRequest ) {
            if (!isset($_POST['login'], $_POST['password'])) {
                self::$content = false;
            } else {
                $login          =   $_POST['login'];
                $password       =   $_POST['password'];
                /** @var \core\component\authentication\component $auth */
                $auth    =   registry::get('auth');
                $result = $auth->get('authorization')->login($login, $password);

                if ($result) {
                    $result = $auth->get('rules')->check('application_' . self::$application['id']);
                    if (!$result) {
                        $auth->get('authorization')->logout();
                        $result = null;
                    }
                }
                self::$content = $result;
            }
        } else {
            if (isset($_COOKIE['uid'])) {
                /** @var \core\component\authentication\component $auth */
                $auth    =   registry::get('auth');
                $result = $auth->get('rules')->check('application_' . self::$application['id']);
                if (!$result) {
                    $auth->get('authorization')->logout();
                }
                else {
                    self::redirect(self::$application['url']);
                }
            }
            resources::setCss(self::getTemplate('css/enter.css'));
            resources::setJs(self::getTemplate('js/enter.js'));
        }
    }
}
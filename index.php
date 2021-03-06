<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 11:12
 */

error_reporting (E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');

/** @var int Время Старта */
$timeStart  = microtime(true);

include_once 'core' . DIRECTORY_SEPARATOR . 'autoload.php';

use core\{
    core,
    router,
    component\dir\dir,
    component\config\config,
    component\PDO\PDO
};


dir::setDR(__DIR__);
dir::setDirConfig('configuration');
dir::setDirFileCache('filecache');

$config     = config::getConfig('db.common');
/** @var \core\component\PDO\PDO $db */
$db         =   PDO::getInstance($config);
$structure  =   $db->selectRows('core_application','*', Array( 'status' => '1'), '`priority` ASC');

if (isset($_SERVER['SHELL'], $argv)) {
    $URL    =   $argv;
    $URL[0] = '/';
} else {
    $parsedURL = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($parsedURL === false){
        die('URL parsing error');
    }
    $URL    =   explode('/', $parsedURL);
}
$result = (new router($structure, $URL))->run();

/** @var int Время Конца */
$timeEnd = microtime(true);
/** @var int Время Разница*/
$timeDiff = $timeEnd - $timeStart;
echo strtr($result, Array(
    '{time_DIFF}' => $timeDiff,
    '{core_VERSION}' => core::VERSION,
));

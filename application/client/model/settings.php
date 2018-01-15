<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 8.9.2017
 * Time: 17:17
 */

namespace application\client\model;

use \core\component\{
    application\handler\Web             as applicationWeb
};

class settings extends applicationWeb\AClass
{
    /**
     * @var string
     */
    private static $table = 'client_settings';

    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @var null
     */
    private static $configuration = null;


    /**
     * @param string $table
     * @return configuration|null
     */
    public static function getInstance(string $table = '') {
        if ($table !== '') {
            self::$table = $table;
        }
        if (!isset(self::$instance) || self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * cart constructor.
     */
    public function __construct()
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db =   self::get('db');
        $where = Array(
            'id' => 2
        );
        self::$configuration =   $db->selectRow(self::$table, '*', $where);
    }

    public function getConfiguration($key)
    {
        return isset(self::$configuration[$key])    ?   self::$configuration[$key]  :   '';
    }
}
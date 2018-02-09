<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 8.9.2017
 * Time: 17:17
 */

namespace application\model;

use \core\component\{
    application,
    registry\registry
};

class settings extends application\AClass
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
     * @return settings|null
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
        /** @var \core\component\PDO\PDO $db */
        $db =   registry::get('db');
        $where = Array(
            'id' => 1
        );
        self::$configuration =   $db->selectRow(self::$table, '*', $where);
    }

    public function getConfiguration($key)
    {
        return isset(self::$configuration[$key])    ?   self::$configuration[$key]  :   '';
    }
}
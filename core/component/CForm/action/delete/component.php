<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:14
 */

namespace core\component\CForm\action\delete;


use \core\component\{
    CForm as CForm
};


/**
 * Class component
 * @package core\component\CForm\action\delete
 */
class component extends  CForm\AAction implements CForm\IAction
{

    /**
     * @param $id
     */
    private function dell($id)
    {
        $this->data['id'] = $id;
        $this->preMethod('preDelete');
        if (!$this->isError) {
            $where = Array(
                'id' => $id
            );
            parent::$db->dell(parent::$table, $where);
            $this->postMethod('postDelete');
        }
        unset($this->data['id']);

    }

    public function run($id = 0)
    {
        $this->dell($id);
        if (isset($_GET['redirect'])) {
            self::redirect($_GET['redirect']);
        }

    }


    public function many($id = 0)
    {
        foreach ($_POST['row'] as $idRow => $value) {
            $this->dell($idRow);
        }
        if (isset($_GET['redirect'])) {
            self::redirect($_GET['redirect']);
        }
    }


}
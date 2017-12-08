<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.06.17
 * Time: 14:05
 */

namespace core\component\CForm2\viewer\add;


use \core\component\CForm2 as CForm;


class component extends CForm2\AViewer implements CForm2\IViewer
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   1.3;


    public function init()
    {
        $config = self::$config;
        unset($config['viewer']);
        $this->viewerConfig = array_merge($this->viewerConfig, $config);
        $this->schemaField              =  $this->viewerConfig['field'];
        $this->viewerConfig['parent']   =   $this->getParent();
        $this->data                     =   $this->fillData();
    }

    public function run()
    {
        $data = Array();
        foreach ($this->field as $field) {
            $data[$field] = false;
        }
        /** поля для пре сохранения */
        foreach ($this->schemaField as $key => $field) {
            /** @var \core\component\CForm2\field\input\component $fieldComponent */
            $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
            $fieldComponent  =   new $fieldComponent();
            $fieldComponent->setComponentSchema($field);
            $fieldComponent->setField($this->field);
            $fieldComponent->init();
            if (method_exists($fieldComponent, 'preInsert') && isset($field['field'])) {
                $data[$field['field']]    =   $fieldComponent->preInsert();
            }
            $this->field    =   $fieldComponent->getField();
        }
        $value = Array();
        foreach ($data as $key => $val) {
            if ($val !== false) {
                $value[$key] = $val;
            }
        }
        if (isset($this->viewerConfig['status'])) {
            $value['status'] = $this->viewerConfig['status'];
        }
        /** @var \core\component\database\driver\PDO\component $db */
        $db     =   $this->viewerConfig['db'];
        $db->inset($this->viewerConfig['table'], $value);
        $this->viewerConfig['id'] =   $db->getLastID();
        $this->data         =   $db->selectRow(self::$config['table'],
            $this->field,
            Array(
                'id' => $this->viewerConfig['id']
            ));

        /** поля для пост сохранения */
        foreach ($this->schemaField as $key => $field) {
            /** @var \core\component\CForm2\field\input\component $fieldComponent */
            $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
            $fieldComponent::setData($this->data);
            $fieldComponent  =   new $fieldComponent();
            $fieldComponent->setComponentSchema($field);
            if (isset($field['field'], $this->data[$field['field']])) {
                $fieldComponent->setFieldValue($this->data[$field['field']]);
            }
            $fieldComponent->init();
            if (method_exists($fieldComponent, 'postInsert')) {
                $fieldComponent->postInsert();
            }
            $this->data     =   $fieldComponent::getData();
        }
        $array = Array();
        foreach ($this->data as $key => $value) {
            $k = '{DATA_' . mb_strtoupper($key) . '}';
            $array[$k]   =   $value;
        }
        $path = $this->viewerConfig['controller']::getPageURL();
        if (isset($this->viewerConfig['redirect'])) {
            $path .= strtr($this->viewerConfig['redirect'], $array);
        }
        self::redirect($path);
    }

    /**
     * Заполняет дату
     * @return array дата
     */
    private function fillData(): array
    {
        $this->fillField();
	    foreach ($this->schemaField as $key => $value) {
		    if(!isset( $value[self::$mode])) {
			    $value[self::$mode] = Array(
				    self::$mode => Array(),
			    );
		    }
		    if(!isset( $value[self::$mode]['order'])) {
			    $this->schemaField[$key][self::$mode]['order'] = $key;
		    }
	    }
        usort($this->schemaField, Array($this, 'callbackSchemaSort'));
        $where  =   $this->preparationWhere();
        /** @var \core\component\database\driver\PDO\component $db */
        $db =   $this->viewerConfig['db'];
        $row    =   $db->selectRow($this->viewerConfig['table'], $this->field, $where);
        return ($row === false) ?   []  :   $db->selectRow($this->viewerConfig['table'], $this->field, $where);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:38
 */

namespace core\component\CForm\viewer\UKListing;


use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView as simpleView
};
use core\core;


/**
 * Class component
 *
 * @package core\component\CForm\viewer\UKListing
 */
class component extends CForm\AViewer implements CForm\IViewer
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   2;


	public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        foreach ($this->pagination as $page) {
            $this->answer['ON_PAGE_LIST'][] =  Array(
                'CLASS' =>  $page == $this->onPage  ?   'uk-active' :   '',
                'URL'   =>  '?onPage=' . $page,
                'TEXT'  =>  $page
            );
        }
        $this->answer['ON_PAGE']            = $this->onPage;
        $this->answer['PARENT']             = $this->parent;
        $this->answer['PAGE']               = $this->page;
        $this->answer['TOTAL_ROWS']         = $this->totalRows;
        $this->answer['TOTAL_PAGE']         = $this->totalPage;
        $this->answer['PAGINATION']         = $this->getPagination();
    }

    /**
     * Запуск
     */
    public function run()
    {
        if ($this->totalRows == 0) {
            $template = core::getDR(true) . self::getTemplate('template/listNo.tpl', __DIR__);
        } else {
            $template = core::getDR(true) . self::getTemplate('template/list.tpl', __DIR__);
            $this->answer['TR'] = Array(
                'TH' => Array(),
                'TD' => Array(),
            );
            foreach ($this->data as $row) {
                /**
                 * Поля
                 */
                foreach ($this->field as $key   =>   $field) {
                    if (isset($row[$field['field']])) {
                        $field['value'] = $row[$field['field']];
                    }
                    if (!isset($field['mode'])) {
                        $field['value'] = 'listing';
                    }
                    $fieldName      =   $field['type'];
                    $fieldObject    =   "core\component\CForm\\field\\{$fieldName}\component";
                    if (class_exists($fieldObject)) {
                        /** @var \core\component\CForm\field\UKInput\component $fieldComponent */
                        $fieldComponent = new $fieldObject($field, $this->data);
                        $fieldComponent->init();
                        $fieldComponent->run();
                        if (isset($this->answer['TR']['TH'][$key])) {
                            $this->answer['TR']['TH'][$key] = $fieldComponent->getCaption();
                        }
                        $this->answer['TR']['TD'][]     = $fieldComponent->getAnswer();
                    }
                }
                /**
                 * Кнопки
                 */
                foreach ($this->button as $key   =>   $button) {
                    $buttonName      =   $button['type'];
                    $buttonObject    =   "core\component\CForm\\button\\{$buttonName}\component";
                    if (class_exists($buttonObject)) {
                        /** @var \core\component\CForm\button\UKSimple\component $fieldComponent */
                        $buttonComponent = new $buttonObject($button, $this->data);
                        $buttonComponent->init();
                        $buttonComponent->run();
                        if (isset($this->answer['TR']['TH'][$key])) {
                            $this->answer['TR']['TH'][$key] = $buttonComponent->getCaption();
                        }
                        $this->answer['TR']['TD'][]     = $buttonComponent->getAnswer();
                    }
                }
            }
        }
        self::$controller::setCss(self::getTemplate('css/list.css', __DIR__));
        $this->answer   =   simpleView\component::replace($template, $this->answer);

    }


    /**
     * Отдает Постраничку
     * @return array данные Постранички
     */
    private function getPagination() :array
    {
        $url = self::$controller::getPageURL() . '/' . self::$id . '/' . parent::$mode . '/';
        $pagination  =   Array();

        if ($this->totalPage === 1) {
            $pagination[] = Array(
                'HREF'  =>  $url . 1,
                'TEXT'  =>  'Вся информация размещена на одной странице',
                'CLASS' =>  'uk-active'
            );
        } elseif ($this->totalPage <= 6) {
            if ($this->page != 1) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . ($this->page - 1),
                    'TEXT'  =>  '<span uk-pagination-previous></span>',
                );
            }
            for ($i = 1; $i <= $this->totalPage; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            if ($this->page != $this->totalPage) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . ($this->page + 1),
                    'TEXT'  =>  '<span uk-pagination-next></span>',
                );
            }

        } elseif ($this->totalPage  <= 5) {
            if ($this->page != 1) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . $this->page - 1,
                    'TEXT'  =>  '<span uk-pagination-previous></span>',
                );
            }
            for ($i = 1, $iMax = 7; $i < $iMax; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . $this->totalPage,
                'TEXT'  =>  $this->totalPage,
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page + 1),
                'TEXT'  =>  '<span uk-pagination-next></span>',
            );

        } elseif ($this->page >=  ($this->totalPage - 4)) {
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page - 1),
                'TEXT'  =>  '<span uk-pagination-previous></span>',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . 1,
                'TEXT'  =>  1,
            );
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            for ($i = ($this->totalPage - 5), $iMax = $this->totalPage; $i <= $iMax; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            if ($this->page != $this->totalPage) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . ($this->page + 1),
                    'TEXT'  =>  '<span uk-pagination-next></span>',
                );
            }
        } else {
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page - 1),
                'TEXT'  =>  '<span uk-pagination-previous></span>',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . 1,
                'TEXT'  =>  1,
            );
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            for ($i = ($this->page - 2), $iMax = ($this->page + 2); $i < $iMax; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . $this->totalPage,
                'TEXT'  =>  $this->totalPage,
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page + 1),
                'TEXT'  =>  '<span uk-pagination-next></span>',
            );
        }
        return $pagination;
    }

}
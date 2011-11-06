<?php

class BooksController extends Zend_Controller_Scaffolding
{

    protected $fields;

    public function init()
    {
        $this->fields = array(
            'id' => array(
                'skip' => true
            ),
            'title' => array(
                'searchable' => true,
                'sortable'  => true,
                'sortBy'  => 'desc'
            ),
            'description' => array(
                'type'  => 'richtextarea',
                'rows'  => 5,
                'cols'  => 40
            ),
            'available' => array(
                'type' => 'checkbox',
                'searchable' => true
            ),
            'category' => array(
                'title' => 'Category',
                'asText' => true,
                'asTextColumn'  => 'name',
                'searchable' => true,
                'sortable'  => true,
            ),
            'catalog' => array(
                'title' => 'Catalog',
                'asText' => true,
                'asTextColumn'  => 'name',
                'searchable' => true,
                'sortable'  => true,
            ),
            'created' => array(
                'skip' => 'edit'
            ),
            'updated' => array(
                'skip' => 'edit'
            )
        );
        $this->initScaffolding(new Application_Model_Books(), $this->fields, array('csrfProtected' => false, 'entityTitle' => 'book'));
    }

    public function  indexAction() {
        $this->initScaffolding(new Application_Model_Books(), $this->fields, array('csrfProtected' => false, 'entityTitle' => 'book'));
        parent::indexAction();
    }

    public function pagerAction() {
        $fields = array();

        $this->initScaffolding(new Application_Model_Books(), $fields,
                array(
                    'csrfProtected' => false,
                    'useIndexAction' => true,
                    'pagination' => array('itemsPerPage' => 2)
                ));
        parent::indexAction();
    }

    public function readonlyAction() {
        $fields = array();

        $this->initScaffolding(new Application_Model_Books(), $fields,
                array(
                    'csrfProtected' => false,
                    'useIndexAction' => true,
                    'readonly' => true
                ));
        parent::indexAction();
    }

    public function  _loadRichTextEditor(array $fields) {
        $this->view->headScript()->appendScript('// RTE Fields: ' . join(',', $fields));
    }

    public function _beforeCreate(Zend_Form $form, array &$values) {
        $values['created'] = date('Y-m-d H:i:s');
        return true;
    }

    public function _beforeUpdate(Zend_Form $form, array &$values) {
        $values['updated'] = date('Y-m-d H:i:s');
        return true;
    }

    public function  _beforeDelete(Zend_Db_Table_Row_Abstract $entity) {
        $this->getResponse()->setHeader('Before-Delete', 'OK');
        return true;
    }

    public function _afterCreate(Zend_Form $form, $id) {
        $this->getResponse()->setHeader('After-Create', 'OK');
        return true;
    }

    public function _afterUpdate(Zend_Form $form) {
        $this->getResponse()->setHeader('After-Update', 'OK');
        return true;
    }

    public function _afterDelete(Zend_Db_Table_Row_Abstract $entity) {
        $this->getResponse()->setHeader('After-Delete', 'OK');
        return true;
    }
}


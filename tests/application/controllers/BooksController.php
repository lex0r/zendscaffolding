<?php

class BooksController extends Zend_Controller_Scaffolding
{

    protected $fields;

    public function init()
    {
        $this->fields = array(
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
            // We may need the ID value, so we fetch it.
            'category' => array(
                'hide' => 'list', // do not show as separate column
//                'searchable' => true,
                'displayField' => 'Category.name', // use alternative column as displayed value for list/edit display
            ),
            // A foreign key field definition.
            // Category is the name of the reference rule from model class.
            // name is the column name that must be fetched
            'Category.name' => array(
                'title' => 'Category',
                'searchable' => true,
                'sortable'  => true,
                'order' => 3,
            ),
            'catalog' => array(
                'hide' => 'list',
                'displayField' => 'Catalog.name',
                'searchable' => true,
            ),
            'Catalog.name' => array(
                'title' => 'Catalog',
                //'searchable' => true,
                'sortable'  => true,
                'order' => 1,
            ),
            'created' => array(
                'hide' => 'edit'
            ),
            'updated' => array(
                'hide' => 'edit'
            )
        );
        $this->scaffold(new Application_Model_Books(), $this->fields, array('csrfProtected' => false, 'entityTitle' => 'book'));
    }

    public function  indexAction() {
        $this->scaffold(new Application_Model_Books(), $this->fields, array('csrfProtected' => false, 'entityTitle' => 'book'));
        parent::indexAction();
    }

    public function pagerAction() {
        $fields = array();

        $this->scaffold(new Application_Model_Books(), $fields,
                array(
                    'csrfProtected' => false,
                    'useIndexAction' => true,
                    'pagination' => array('itemsPerPage' => 2)
                ));
        parent::indexAction();
    }

    public function readonlyAction() {
        $fields = array();

        $this->scaffold(new Application_Model_Books(), $fields,
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


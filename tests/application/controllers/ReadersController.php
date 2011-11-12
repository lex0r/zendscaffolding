<?php

class ReadersController extends Zend_Controller_Scaffolding
{

    public function init()
    {
        $fields = array(
            'name' => array(
                'title'     => 'First & last name',
                'searchable'=> true,
            ),
            'category' => array(
                'hide'          => 'list',
                'displayField'  => 'Category.name'
            ),
            'Category.name' => array(
                'title' => 'Category',
            ),
            'updated' => array(
                'hide'  => 'edit'
            ),
            'created' => array(
                'searchable'=> true,
                'type'      => 'datepicker',
                'hide'      => 'edit'
            ),
            'Books.title' => array(
                'title'         => 'Assigned books',
            )
        );

        $this->scaffold(new Application_Model_Readers(), $fields, array('csrfProtected' => false, 'entityTitle' => 'reader', 'disabledActions' => array(self::ACTION_DELETE)));
    }

    /*
    public function indexAction() {
        $fields = array(
            'id' => array(
                'skip'      => true,
                'pk'        => true
            ),
            'name' => array(
                'title'     => 'First & last name',
                'type'      => 'text',
                'searchable'=> true,
            ),
            'age' => array(
                'title' => 'Age',
            ),
            'books' => array(
                'title' => 'Books assigned',
            ),
            'created' => array(
                'searchable'=> true,
                'type'      => 'datepicker',
                'skip'  => 'edit',
            ),
            'updated' => array(
                'skip' => 'edit'
            ),
            'assignBooks' => array(
                'title'         => 'Assigned books',
                'dependentTable'=> new Application_Model_ReadersBooks(),
                'asTextColumn'  => 'title',
                'skip'  => 'list'
            )
        );

        $opts = array(
            'csrfProtected'     => false,
            'entityTitle'       => 'reader',
            'disabledActions'   => array(self::ACTION_DELETE));

        $select = Zend_Db_Table::getDefaultAdapter()->select();
        $select->from(array('r' => 'readers'),
                        array(
                            'id' => 'r.id',
                            'name' => 'r.name',
                            'age' => 'r.age',
                            'books' => new Zend_Db_Expr('COUNT(rb.reader_id)'),
                            'created' => 'r.created',
                            'updated' => 'r.updated'))
                ->joinLeft(array('rb' => 'readers_books'), 'r.id = rb.reader_id', null)
                ->group('r.id');
        $this->initScaffolding($select, $fields, $opts);

        parent::indexAction();
    }
     *
     */

    public function loadDatePicker(array $fields) {
        $this->view->headScript()->appendScript('// Date Picker Fields: ' . join(',', $fields));
    }

    public function beforeCreate(Zend_Form $form, array &$values) {
        $values['created'] = date('Y-m-d H:i:s');
        return true;
    }

    public function beforeUpdate(Zend_Form $form, array &$values) {
        $values['updated'] = date('Y-m-d H:i:s');
        return true;
    }
}


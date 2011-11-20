<?php

class ReadersController extends Zend_Controller_Scaffolding
{

    public function init() {
        parent::init();

        $fields = array(
            'name' => array(
                // The title of the column
                'title'     => 'Name',
                // We want to be able to search by the reader's name
                'searchable'=> true,
                // We want to be able to sort by name
                'sortable'=> true,
                // The field is first in the list.
                'listOrder' => 1
            ),
            'category' => array(
                // We don't want to show the ID in the list.
                'hide'          => 'list',
                // Instead we want to show it as relevant
                // text field from a related table, defined as key of the next field.
                // Note: 'Category' is the name of reference rule defined in
                // current model class (Application_Model_Readers) inside _referenceMap variable.
                'displayField'  => 'Category.name'
            ),
            // The field from related table, that will replace meaningless ID.
            'Category.name' => array(
                'title' => 'Category',
                'listOrder' => 2,
                'sortable' => true
            ),
            // A field from dependent table that we also want to show.
            // Note: 'Reader' is the name of the reference rule in the dependent
            // table's model, that points to the current model class,
            // 'balance' is the field we want to show, that belongs to dependent
            // table, NOT current table (i.e. not 'readers').
            // Important: this field is not intended for changes from the application
            // and is just for demo purposes.
            'Reader.balance' => array(
                'title' => 'Balance',
                'listOrder' => 3
            ),
            'created' => array(
                'searchable'=> true,
                // This field will be transmitted with other
                // fields of type 'jsPicker' to loadDatePicker method.
                // This allows to create a JS widget (calendar for date,
                // but any other if needed).
                'fieldType' => 'jsPicker',
                // We don't want to allow edit of this field
                'hide'      => 'edit',
                'listOrder' => 5
            ),
            'updated' => array(
                'hide'  => 'edit',
                'listOrder' => 4
            ),
            // This field definition makes sense only for edit form
            // and allows to show list of assigned books through a
            // many-to-many table readers_books.
            // Note: 'Books' stands for the reference rule defined in the dependent table
            // Application_Model_ReadersBooks that points to books table, while
            // 'title' is the textual name of the related entity (book) that
            // substitutes a meaningless ID.
            'Books.title' => array(
                'title' => 'Assigned books',
            ),
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


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
                'search'=> true,
                // We want to be able to sort by name
                'sort'=> array('default' => 'asc'),
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
                'sort' => true
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
                'search'=> true,
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

        $opts = array(
            'csrfProtected' => false,
            'entityTitle' => 'reader',
            'disabledActions' => array(self::ACTION_DELETE)
        );

        // We want to use custom view script for index action.
        if ($this->getRequest()->getActionName() == self::ACTION_INDEX) {
            $opts['viewFolder'] = 'readers';
        }
        $this->scaffold(new Application_Model_Readers(), $fields, $opts);
    }

    /**
     * Example of absolutely custom listing query that has support for
     * search, sorting and pagination.
     */
    public function smartqueryAction() {
        $fields = array(
            // Common table field definitions must respect the format <tableAlias>.<fieldName>
            'r.name' => array(
                'title'     => 'Name',
                // Needed for proper field handling.
                'dataType'  => 'varchar',
                'search'=> true,
                'sort'=> true,
            ),
            'r.age' => array(
                'title' => 'Age',
                'dataType' => 'int'
            ),
            // Computed fields are presented as aliases
            'books' => array(
                'title'    => 'Assigned books',
                'dataType' => 'int',
                'sort'=> true,
                'search' => array('empty' => true, 'emptyLabel' => 'No books', 'emptyOnly' => true),
                'aggregate' => true
            )
        );

        $opts = array(
            'disabledActions'   => array(self::ACTION_DELETE),
            'viewFolder' => 'readers'
        );

        $select = Zend_Db_Table::getDefaultAdapter()->select();
        $select->from(array('r' => 'readers'),
                        array('id', 'name', 'age',
                            // And here goes computed field
                            'books' => new Zend_Db_Expr('COUNT(rb.reader_id)')))
                ->joinLeft(array('rb' => 'readers_books'), 'r.id = rb.reader_id', null)
                ->group('r.id');
        $this->smartQuery($select, $fields, $opts);
    }

    /**
     * Handle date fields (in this case - just for unit test purposes)
     * @param array $fields
     */
    public function loadDatePicker(array $fields) {
        $this->view->headScript()->appendScript('// Date Picker Fields: ' . join(',', $fields));
    }

    /**
     * Handle entity before saving it for the first time to database.
     * @param Zend_Form $form
     * @param array $values
     * @return type
     */
    public function beforeCreate(Zend_Form $form, array &$values) {
        $values['created'] = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * Handle entity before saving it to database.
     * @param Zend_Form $form
     * @param array $values
     * @return type
     */
    public function beforeUpdate(Zend_Form $form, array &$values) {
        $values['updated'] = date('Y-m-d H:i:s');
        return true;
    }
}


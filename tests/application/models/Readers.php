<?php
class Application_Model_Readers extends Zend_Db_Table {
    protected $_name = 'readers';

    protected $_referenceMap    = array(
        'Category' => array(
            'columns'           => 'category', // foreign key column
            'refTableClass'     => 'Application_Model_ReaderCategories',
            'refColumns'        => 'id'
        ),
    );
}
?>

<?php
class Application_Model_Books extends Zend_Db_Table {
    protected $_name = 'books';

    protected $_referenceMap    = array(
        'Category' => array(
            'columns'           => 'category', // foreign key column
            'refTableClass'     => 'Application_Model_BookCategories',
            'refColumns'        => 'id'
        ),
        'Catalog' => array(
            'columns'           => 'catalog', // foreign key column
            'refTableClass'     => 'Application_Model_BookCatalogs',
            'refColumns'        => 'id'
        ),
    );
}
?>

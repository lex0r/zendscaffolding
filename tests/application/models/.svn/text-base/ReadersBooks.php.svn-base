<?php
class Application_Model_ReadersBooks extends Zend_Db_Table {
    protected $_name = 'readers_books';

    protected $_referenceMap    = array(
        'Reader' => array(
            'columns'           => 'reader_id', // foreign key column
            'refTableClass'     => 'Application_Model_Readers',
            'refColumns'        => 'id'
        ),
        'Books' => array(
            'columns'           => 'book_id',   // foreign key column
            'refTableClass'     => 'Application_Model_Books',
            'refColumns'        => 'id'
        ),
    );
}
?>

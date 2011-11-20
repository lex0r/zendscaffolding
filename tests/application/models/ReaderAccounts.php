<?php
class Application_Model_ReaderAccounts extends Zend_Db_Table {
    protected $_name = 'reader_accounts';

    // References the main table 'readers'
    protected $_referenceMap    = array(
        'Reader' => array(
            'columns'           => 'reader_id', // foreign key column
            'refTableClass'     => 'Application_Model_Readers',
            'refColumns'        => 'id'
        ),
    );
}
?>

<?php
class Application_Model_Readers extends Zend_Db_Table {
    protected $_name = 'readers';

    // These MUST be defined if you want to use data from
    // related tables (especially for one-to-many relationships)
    protected $_referenceMap    = array(
        'Category' => array(
            'columns'           => 'category', // foreign key column
            'refTableClass'     => 'Application_Model_ReaderCategories',
            'refColumns'        => 'id'
        ),
    );

    protected $_dependentTables = array(
        // This is a many-to-many table.
        'Application_Model_ReadersBooks',
        // And this is 1-1 table that fully depends on reader.
        'Application_Model_ReaderAccounts'
    );
}
?>

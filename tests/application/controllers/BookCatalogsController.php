<?php

class BookCatalogsController extends Zend_Controller_Scaffolding
{

    public function init()
    {
        $this->scaffold(new Application_Model_BookCatalogs(), array(), array('csrfProtected' => false));
    }
}


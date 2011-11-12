<?php

class BookCategoriesController extends Zend_Controller_Scaffolding
{

    public function init()
    {
        $this->scaffold(new Application_Model_BookCategories(), array(), array('csrfProtected' => false));
    }
}


<?php

class ReaderCategoriesController extends Zend_Controller_Scaffolding
{

    public function init()
    {
        $this->initScaffolding(new Application_Model_ReaderCategories(), array(), array('csrfProtected' => false));
    }
}


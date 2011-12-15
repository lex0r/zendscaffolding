<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class ReadersControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function  __construct() {
        $this->bootstrap = realpath(dirname(__FILE__) . '/../bootstrap.php');
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
    }

    public function testCreate() {
        $books = new Application_Model_Books();
        // 2 books are expected
        $bookList = $books->select()->query()->fetchAll(Zend_Db::FETCH_OBJ);

        $readerId = 0;
        $params = array(
                    'name'      => 'Reader ' . (++$readerId),
                    'created'   => date('Y-m-d H:i:s'),
                    'Bookstitle'=> array($bookList[0]->id, $bookList[1]->id),
                    'category'  => 1,
                    'save'      => 'save'
                );
        $request = $this->getRequest();
        $request->setMethod('POST')->setPost($params);
        $this->dispatch('/readers/create');
        $this->assertHeaderContains('Location', '/default/readers/index');

        $this->resetRequest();
        $this->resetResponse();

        $readers = new Application_Model_Readers();
        $reader = $readers->select()->from($readers)->where('1 = 1')->limit(1)->query()->fetchObject();
        $this->dispatch('/readers/update/id/' . $reader->id);

        $this->assertQueryContentContains('dd#name-element', 'value="Reader 1"');
        $this->assertQueryContentContains('select', 'value="' . $bookList[0]->id . '"');
        $this->assertQueryContentContains('select', 'value="' . $bookList[1]->id . '"');

        $this->assertQueryContentContains('select#category', 'selected="selected"');
    }

    public function testUpdate() {
        $books      = new Application_Model_Books();
        // 2 books are expected
        $bookList   = $books->select()->query()->fetchAll(Zend_Db::FETCH_OBJ);

        $readers    = new Application_Model_Readers();
        $reader     = $readers->select()->from($readers)->where('1 = 1')->limit(1)->query()->fetchObject();

        $params = array(
                    'id'        => $reader->id,
                    'name'      => 'Reader 1 updated',
                    'created'   => $reader->created,
                    'Bookstitle'=> array($bookList[0]->id),
                    'save'      => 'Save'
                );
        $request = $this->getRequest();
        $request->setMethod('POST')->setPost($params);
        $this->dispatch('/readers/update/id/' . $reader->id);
        $this->assertHeaderContains('Location', '/default/readers/index');

        $this->resetRequest();
        $this->resetResponse();

        $this->dispatch('/readers/update/id/' . $reader->id);

        $this->assertQueryContentContains('dd#name-element', 'value="Reader 1 updated"');
        $this->assertQueryCountMax('Book Title', 1);
        $this->assertQueryCountMax('selected="selected"', 1);
    }

    public function testSearch() {
        $this->dispatch('/readers/index');
        $this->assertQueryContentContains('dd#name-element', 'class="zs-search-text"');
        $this->assertQueryCountMax('class="zs-search-datetime"', 2);

        $this->assertQueryContentContains('td', 'Reader 1');

        $request = $this->getRequest();
        $request->setMethod('POST')
                ->setPost(array(
                    'created_zs_from' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'created_zs_to' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                    'submit'    => 'Search'
                ));

        $this->dispatch('/readers/index');

        $this->resetRequest();
        $this->resetResponse();

        $request = $this->getRequest();
        $request->setMethod('POST')
                ->setPost(array(
                    'created_zs_from' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                    'created_zs_to' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                    'submit'    => 'Search'
                ));

        $this->dispatch('/readers/index');

        $this->assertQueryContentContains('td', 'Reader 1 updated');
    }

    public function testSkippedFieldsList() {
        $this->dispatch('/readers/index');

        $this->assertNotQueryContentContains('th', 'Id');
        $this->assertQueryContentContains('th', 'Name');
        $this->assertQueryContentContains('th', 'Created');
    }

    public function testDisabledActionDelete() {
        $readers = new Application_Model_Readers();
        $areader = $readers->select()->from($readers)->where('1 = 1')->limit(1)->query()->fetchObject();

        $this->dispatch('/readers/delete/id/' . $areader->id);
        $this->assertQueryContentContains('body', "'delete' action is disabled");
    }

    public function testDatePicker() {
        $this->dispatch('/readers/index');
        $this->assertQueryContentContains('html', 'Date Picker Fields: created');
    }

    public function testCustomDbSelect() {
        $this->dispatch('/readers/smartquery');
        $this->assertQueryContentContains('th', 'Assigned books');
        $this->assertQueryContentContains('tr', '<td>1</td>');
    }
}
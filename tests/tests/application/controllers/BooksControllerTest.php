<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class BooksControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
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

    public function testCreateRequiredFieldEmpty() {
        $request = $this->getRequest();
        $request->setMethod('POST')
                ->setPost(array(
                    'title' => 'Robin Hood',
                    'author'=> ''
                ));

        $this->dispatch('/books/create');

        $this->assertQueryContentContains("dd#author-element ul li", "Value is required and can't be empty");
    }

    public function testCreateButtons() {
        // Clean up database before tests
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->delete('readers');
        $db->delete('reader_accounts');
        $db->delete('books');
        $db->delete('readers_books');
        $db->delete('reader_categories');
        $db->delete('book_categories');
        $db->delete('book_catalogs');

        // Create vocabularies
        $db->insert('book_categories', array('id' => 1, 'name' => 'SciFi'));
        $db->insert('book_categories', array('id' => 2, 'name' => 'Adventure'));
        $db->insert('reader_categories', array('id' => 1, 'name' => 'Student'));
        $db->insert('reader_categories', array('id' => 2, 'name' => 'Worker'));
        $db->insert('book_catalogs', array('id' => 1, 'name' => 'Upper floor'));
        $db->insert('book_catalogs', array('id' => 2, 'name' => 'Lower floor'));

        $bookId = 0;
        $params = array(
                    'title'     => 'Book Title ' . (++$bookId),
                    'author'    => 'Book Author ' . ($bookId),
                    'category'  => 1,
                    'catalog'   => 1,
                    'available' => 1,
                    'save'      => 'save'
                );
        $request = $this->getRequest();
        $request->setMethod('POST')->setPost($params);
        $this->dispatch('/books/create');
        $this->assertHeaderContains('Location', '/default/books/index');

        // Test afterCreate handler
        $this->assertHeaderContains('After-Create', 'OK');

        $this->resetRequest();
        $this->resetResponse();

        $request = $this->getRequest();
        unset($params['save']);
        $params['title']    = 'Book Title ' . (++$bookId);
        $params['author']   = 'Book Author ' . ($bookId);
        $params['saveedit'] = 'save';
        $request->setMethod('POST')->setPost($params);
        $this->dispatch('/books/create');
        $this->assertHeaderContains('Location', '/default/books/update/');

        $this->resetRequest();
        $this->resetResponse();

        $request = $this->getRequest();
        unset($params['saveedit']);
        $params['title']    = 'Book Title ' . (++$bookId);
        $params['author']   = 'Book Author ' . ($bookId);
        $params['savecreate'] = 'save';
        $request->setMethod('POST')->setPost($params);
        $this->dispatch('/books/create');

        $this->assertHeaderContains('Location', '/default/books/create');

        // Test beforeCreate handler
        $books = new Application_Model_Books();
        $abook = $books->select()->from($books)->where('1 = 1')->limit(1)->query()->fetchObject();
        $this->assertTrue((bool)strtotime($abook->created));
        $this->assertFalse((bool)strtotime($abook->updated));
    }

    public function testUpdate() {
        $books = new Application_Model_Books();
        $abook = $books->select()->from($books)->where('1 = 1')->limit(1)->query()->fetchObject();

        $request = $this->getRequest();
        $request->setMethod('POST')
                ->setPost(array(
                    'id'    => $abook->id,
                    'title' => 'Book Title ' . $abook->id . ' updated',
                    'author'=> 'Book Author ' . $abook->id . ' updated',
                    'category'  => 2,
                    'catalog'   => 2,
                    'available' => 1,
                    'save' => 'save'
                ));

        $this->dispatch('/books/update/id/' . $abook->id);

        $this->assertHeaderContains('Location', '/default/books/index');

        // Test afterUpdate handler
        $this->assertHeaderContains('After-Update', 'OK');

        $this->resetRequest();
        $this->resetResponse();
        $this->dispatch('/books/index');

        $this->assertQueryContentContains('td', 'Book Title ' . $abook->id . ' updated');

        // Test beforeUpdate handler
        $books = new Application_Model_Books();
        $abook = $books->select()->from($books)->where('1 = 1')->limit(1)->query()->fetchObject();
        $this->assertTrue((bool)strtotime($abook->created));
        $this->assertTrue((bool)strtotime($abook->updated));
    }

    public function testPager() {
        $this->dispatch('/books/pager');

        $this->assertQueryContentContains('span.select', '1');
        $this->assertQueryContentContains('a', '2');
        $this->assertQueryContentContains('a.next', '&gt;');
        $this->assertQueryContentContains('a.last', '&gt;&gt;');
        $this->assertQueryCount('tr', 3);

        $this->resetResponse();
        $this->resetRequest();
        $this->dispatch('/books/pager/page/2');

        $this->assertQueryContentContains('span.select', '2');
        $this->assertQueryContentContains('a', '1');
        $this->assertQueryContentContains('a.first', '&lt;&lt;');
        $this->assertQueryContentContains('a.previous', '&lt;');
        $this->assertQueryCount('tr', 2);

    }

    public function testDelete() {
        $books = new Application_Model_Books();
        $abook = $books->select()->from($books)->where('1 = 1')->limit(1)->query()->fetchObject();

        $request = $this->getRequest();

        $this->dispatch('/books/delete/id/' . $abook->id);

        $this->assertHeaderContains('Location', '/default/books/index');
        // Test before/afterDelete handler
        $this->assertHeaderContains('Before-Delete', 'OK');
        $this->assertHeaderContains('After-Delete', 'OK');

        $this->resetRequest();
        $this->resetResponse();
        $this->dispatch('/books/index');

        $this->assertNotQueryContentContains('td', 'Book Title ' . $abook->id . ' updated');
    }

    public function testSearch() {
        $this->dispatch('/books/index');
        $this->assertQueryContentContains('select#catalog', 'value=""');
        $this->assertQueryContentContains('select#catalog', 'Upper floor');
        $this->assertQueryContentContains('select#catalog', 'Lower floor');

        $this->assertQueryContentContains('dd#title-element', 'class="zs-search-text"');
        $this->assertQueryContentContains('dd#available-element', 'class="zs-search-radio"');
        $this->assertQueryContentContains('dl', 'class="zs-btn-search"');
        $this->assertQueryContentContains('dl', 'class="zs-btn-reset"');
        $this->assertQueryContentContains('td', 'Book Title 2');
        $this->assertQueryContentContains('td', 'Book Title 3');

        $request = $this->getRequest();
        $request->setMethod('POST')
                ->setPost(array(
                    'title' => 'Book Title%',
                    'available' => 1,
                    'submit' => 'Search'
                ));

        $this->dispatch('/books/index');

        $this->assertQueryContentContains('dd#title-element', 'value="Book Title%"');
        $this->assertQueryContentContains('dd#available-element', 'checked="checked"');
        $this->assertQueryContentContains('td', 'Book Title 2');
        $this->assertQueryContentContains('td', 'Book Title 3');

        $this->resetRequest();
        $this->resetResponse();

        $request = $this->getRequest();
        $request->setMethod('POST')
                ->setPost(array(
                    'title' => 'Book Title 3',
                    'available' => 0,
                    'submit' => 'Search'
                ));

        $this->dispatch('/books/index');
        $this->assertQueryContentContains('strong', 'No records have been found');

        $this->resetRequest();
        $this->resetResponse();

        $request = $this->getRequest();
        $request->setMethod('POST')
                ->setPost(array(
                    'title' => '',
                    'available' => 0,
                    'reset' => 'Reset'
                ));

        $this->dispatch('/books/index');

        $this->assertQueryContentContains('td', 'Book Title 2');
        $this->assertQueryContentContains('td', 'Book Title 3');
    }

    public function testReadonly() {
        $this->dispatch('/books/readonly');

        $this->assertNotQueryContentContains('a.zs-btn-edit', 'edit');
        $this->assertNotQueryContentContains('a.zs-btn-delete', 'delete');
    }

    public function testSorting() {
        $this->dispatch('/books/index');
        $this->assertQueryContentContains('a.zs-sort-asc', 'Title');
        $this->assertQueryContentRegex('tbody', "/Book Title 3.*Book Title 2/s");

        $this->resetRequest();
        $this->resetResponse();

        $this->dispatch('/books/index/orderby/title/mode/asc');
        $this->assertQueryContentContains('a.zs-sort-desc', 'Title');
        $this->assertQueryContentRegex('tbody', "/Book Title 2.*Book Title 3/s");

    }

    public function testRichTextEditor() {
        $this->dispatch('/books/create');
        $this->assertQueryContentContains('html', 'RTE Fields: description');
    }
}
<?php
namespace Pages\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class PagesTable extends AbstractTableGateway {

	protected $table = 'pages';

	public function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAllPageNames() {
		$names = array();
		
		$resultSet = $this->select('page_owner=0');//CHANGE: get from session
		
		foreach ($resultSet as $row) {
		    $names[$row->page_id] = $row->page_title;
		}
		return $names;
	}
	
	public function getHome() {
		$row = $this->select(array('page_is_home' => 1))->current();
		if (!$row)
			return false;
		
		$page = new Entity\Page(array(
				'page_id' => $row->page_id,
				'page_owner' => $row->page_owner,
				'page_title' => $row->page_title,
				'page_is_home' => $row->page_is_home,
				'page_content' => $row->page_content,
		));
		return $page;
	}
	
	public function getPage($page_id) {
		$row = $this->select(array('page_id' => (int) $page_id))->current();
		if (!$row)
			return false;
		
		$page = new Entity\Page(array(
				'page_id' => $row->page_id,
				'page_owner' => $row->page_owner,
				'page_title' => $row->page_title,
				'page_is_home' => $row->page_is_home,
				'page_content' => $row->page_content,
		));
		
		return $page;
	}
	public function getPageByTitle($page_title) {
		$row = $this->select(array('page_title' => $page_title))->current();
		if (!$row)
			return false;
	
		$page = new Entity\Page(array(
				'page_id' => $row->page_id,
				'page_owner' => $row->page_owner,
				'page_title' => $row->page_title,
				'page_is_home' => $row->page_is_home,
				'page_content' => $row->page_content,
		));
	
		return $page;
	}
	
	
	public function savePage(Entity\Page $page) {
		$data = array(
				'page_owner' => $page->getPage_owner(),
				'page_title' => $page->getPage_title(),
				'page_is_home' => $page->getPage_is_home(),
				'page_content' => $page->getPage_content(),
		);
	
		$page_id = (int) $page->getPage_id();
		if ($page_id == 0) {
			if (!$this->insert($data))
				return false;
			return $this->getLastInsertValue();
		}
		elseif ($this->getPage($page_id)) {
			if (!$this->update($data, array('page_id' => $page_id)))
				return false;
			return $page_id;
		}
		else
			return false;
	}
	
}
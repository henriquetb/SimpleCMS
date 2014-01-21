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
		    $names[$row->page_id] = $row->page_name;
		}
		return $names;
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
}
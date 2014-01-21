<?php
namespace Pages\Model\Entity;

class Page {

	protected $_page_id;
	protected $_page_owner;
	protected $_page_title;
	protected $_page_is_home;
	protected $_page_content;

	public function __construct(array $options = null) {
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	public function __set($name, $value) {
		$method = 'set' . $name;
		if (!method_exists($this, $method)) {
			throw new Exception('Invalid Method');
		}
		$this->$method($value);
	}

	public function __get($name) {
		$method = 'get' . $name;
		if (!method_exists($this, $method)) {
			throw new Exception('Invalid Method');
		}
		return $this->$method();
	}

	public function setOptions(array $options) {
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}
	
	/**
	 * @return the $_page_id
	 */
	public function getPage_id() {
		return $this->_page_id;
	}

	/**
	 * @return the $_page_owner
	 */
	public function getPage_owner() {
		return $this->_page_owner;
	}
	/**
	 * @return the $_page_title
	 */
	public function getPage_title() {
		return $this->_page_title;
	}

	/**
	 * @return the $_page_is_home
	 */
	public function getPage_is_home() {
		return $this->_page_is_home;
	}

	/**
	 * @return the $_page_content
	 */
	public function getPage_content() {
		return $this->_page_content;
	}

	/**
	 * @param field_type $_page_id
	 */
	public function setPage_id($_page_id) {
		$this->_page_id = $_page_id;
        return $this;
	}

	/**
	 * @param field_type $_page_owner
	 */
	public function setPage_owner($_page_owner) {
		$this->_page_owner = $_page_owner;
        return $this;
	}

	/**
	 * @param field_type $_page_title
	 */
	public function setPage_title($_page_title) {
		$this->_page_title = $_page_title;
        return $this;
	}

	/**
	 * @param field_type $_page_is_home
	 */
	public function setPage_is_home($_page_is_home) {
		$this->_page_is_home = $_page_is_home;
        return $this;
	}

	/**
	 * @param field_type $_page_content
	 */
	public function setPage_content($_page_content) {
		$this->_page_content = $_page_content;
        return $this;
	}


	

}
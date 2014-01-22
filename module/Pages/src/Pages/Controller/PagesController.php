<?php
namespace Pages\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Pages\Model\Entity\Page;

class PagesController extends AbstractActionController {
    
    protected $_pagesTable;
    
    public function getPagesTable() {
    	if (!$this->_pagesTable) {
    		$sm = $this->getServiceLocator();
    		$this->_pagesTable = $sm->get('Pages\Model\PagesTable');
    	}
    	return $this->_pagesTable;
    }
    
	public function indexAction() {
	    return new ViewModel(array(
	    		'pagesList' => $this->getPagesTable()->fetchAllPageNames(),
	    ));
	}

	public function validatePage($page){
		return true;
	}
	
	public function addAction(){
	    $request = $this->getRequest();
	    $response = $this->getResponse();
	    if ($request->isPost()) {
	        $new_page = new \Pages\Model\Entity\Page();
	        $post_data = $request->getPost();
	        $new_page->setPage_id(0); //has no id yet.
	        $new_page->setPage_owner(0); //CHANGE: put session value
	        $new_page->setPage_title($post_data['page_title']);
	        $new_page->setPage_is_home($post_data['page_is_home']);
	        $new_page->setPage_content($post_data['page_content']);
	        
	        //validate the server side Page data
	        //empty inputs
	        //size of inputs
	        //unique title
	        //sanitize content
	        if (!$this->validatePage($new_page))
	           return $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
	        
	        
	        
	    	if (!$page_id = $this->getPagesTable()->savePage($new_page))
	    		$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
	    	else {
	    		$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'page_id' => $page_id)));
	    	}
	    }
	    return $response;
	}

	public function removeAction() {
	}

	public function updateAction(){
	    $request = $this->getRequest();
	    $response = $this->getResponse();
	    if ($request->isPost()) {
	    	$post_data = $request->getPost();
	    	$page_id = $post_data['page_id'];
	    	
            $page = $this->getPagesTable()->getPage($page_id);
            
            if (!$page)
                return $response->setContent(\Zend\Json\Json::encode(array('response' => false, 'error' => 'No Page found.')));
            
            $page->setPage_title($post_data['page_title']);
            $page->setPage_is_home($post_data['page_is_home']);
            $page->setPage_content($post_data['page_content']);
            
	    	if (!$this->validatePage($page))
	    		return $response->setContent(\Zend\Json\Json::encode(array('response' => false, 'error' => 'Wrong data.')));
	    	
	    	if (!$this->getPagesTable()->savePage($page))
	    		$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
	    	else {
	    		$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'page_id' => $page->getPage_id())));
	    	}
	    }
	    return $response;
	}

	public function showAction(){
	    $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $page_id = $post_data['page_id'];
            if (!$page = $this->getPagesTable()->getPage($page_id))
                $response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 
                    'page' => $page->toArray()
                )));
            }
        }
        return $response;
	}
	
	
}
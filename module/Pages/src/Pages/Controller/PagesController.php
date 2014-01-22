<?php
namespace Pages\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Pages;

class PagesController extends AbstractActionController {
    
    protected $_pagesTable;
    protected $userSession;
    
    public function __construct(){
        $this->userSession = new Container('user');
    }
    
    public function getPagesTable() {
    	if (!$this->_pagesTable) {
    		$sm = $this->getServiceLocator();
    		$this->_pagesTable = $sm->get('Pages\Model\PagesTable');
    	}
    	return $this->_pagesTable;
    }
    
	public function indexAction() {
	    //$this->userSession = new Container('user');
	    return new ViewModel(array(
	           'pagesList' => $this->getPagesTable()->fetchAllPageNames(),
	           'userName' => $this->userSession->username,
	           'home' => $this->getPagesTable()->getHome(),
	    ));
	    
	}

	public function validatePage(Pages\Model\Entity\Page $page){
	    if ($page->getPage_title() == "" || $page->getPage_title() == null)
	        return "Title can't be empty. Page not saved.";
	    
	    $sameTitle = $this->getPagesTable()->getPageByTitle($page->getPage_title());
	    if ($sameTitle)
	        //if finds a page with same title, and it is not itself 
	       if ($page->getPage_title() ==$sameTitle->getPage_title() && $page->getPage_id()!=$sameTitle->getPage_id())
	           return "Title already exists. Page not saved.";

	    return null;
	}
	
	public function addAction(){
	    $request = $this->getRequest();
	    $response = $this->getResponse();
	    if ($request->isPost()) {
	        $new_page = new \Pages\Model\Entity\Page();
	        $post_data = $request->getPost();
	        $new_page->setPage_id(0); //has no id yet.
	        $new_page->setPage_owner($this->userSession->userid);
	        $new_page->setPage_title($post_data['page_title']);
	        $new_page->setPage_is_home($post_data['page_is_home']);
	        $new_page->setPage_content($post_data['page_content']);
	        
	        //validate the server side Page data
	        //empty inputs
	        //unique title
	        //sanitize content
	        $valid = $this->validatePage($new_page);
	        if ( $valid != null)
	           return $response->setContent(\Zend\Json\Json::encode(array('response' => false, 'error' => $valid)));
	        
	        
	        
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
            
	    	$valid = $this->validatePage($page);
	    	//$valid = "eeerro";
	        if ( $valid != null)
	           return $response->setContent(\Zend\Json\Json::encode(array('response' => false, 'error' => $valid)));
	        
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
                    'page' => $page->toArray(),
                    'userName' => $this->userSession->username,
                )));
            }
        }
        return $response;
	}
	
	public function loginAction() {
        
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $request->getPost();
            $username = $post_data['username'];
            //creates hardcoded user login
            if ($username == ""){
                $this->userSession->username = 'HenriqueTB';
                $this->userSession->userid = 0;//CHANGE get the actual user_id
            }else{ 
              $this->userSession->username = $username;
              $this->userSession->userid = 0;//CHANGE get the actual user_id
            }
            if ($this->userSession->username=="")
            	$response->setContent(\Zend\Json\Json::encode(array('response' => false)));
            else {
            	$response->setContent(\Zend\Json\Json::encode(array('response' => true, 'userName' => $this->userSession->username)));
            }
        }
        return $response;
	}
	
}
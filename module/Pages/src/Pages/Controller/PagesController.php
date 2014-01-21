<?php
namespace Pages\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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

	public function addAction(){
	}

	public function removeAction() {
	}

	public function updateAction(){
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
                $response->setContent(\Zend\Json\Json::encode(array('response' => true, 'view_page_content' => $page->getPage_content())));
            }
        }
        return $response;
	}
	
}
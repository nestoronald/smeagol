<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class NoticiasController extends AbstractActionController
{
	/**
	 * Noticias
	 * @var Admin\Model\Noticias
	 */
	protected $_noticias;
	
	/**
	 * Get Noticias object
	 * @return Admin\Model\Noticias
	 */
	public function getNoticias()
	{
		if (!$this->_noticias) {
			$sm = $this->getServiceLocator();
			$this->_noticias = $sm->get('Admin\Model\Noticias');
		}
	
		return $this->_noticias;
	}
	
	/**
	 * We are overwriting the dispatch function so that all requests to this controller are catched here.
	 * We use the action as the identifier, so that our calls will be http://www.domain.com/noticias/identifier
	 * By the identifier we get the noticias.
	 *
	 * @param \Zend\Stdlib\RequestInterface $request
	 * @param \Zend\Stdlib\ResponseInterface $response
	 * @return type
	 * @throws \Noticias\Controller\Exception
	 */
	public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response = null)
	{
		$identifier = (string)$this->getEvent()->getRouteMatch()->getParam('action');
		$identifier = "noticias/". $identifier;
		$noticias = $this->getNoticias();
	
		try {
			$noticias = $noticias->getNoticiasByIdentifier($identifier);
	
			// get the renderer to manipulate the title
			$renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
	
			// set the noticias title in the html head
			$renderer->headTitle($noticias->title);
	
			// write the models content to the websites content
			$this->layout()->content = '<h1>' . $noticias->title . '</h1>' . $noticias->content;
		} catch(\Exception $ex) {
			// if we are on development, show the exception,
			// if not (we are in production) show the 404 noticias
			if(isset($_SERVER['APPLICATION_ENV']) && $_SERVER['APPLICATION_ENV'] == 'development') {
				throw $ex;
			} else {
				// it is necessery to call the parent dispatch, otherwise the notFoundFunction doesn't work.
				parent::dispatch($request, $response);
				$this->notFoundAction();
				return;
			}
		}
	
	}
	
	public function indexAction()
	{
		return new ViewModel(array(
           'noticias'=> $this->getnoticias()->fetchAllNoticias(),
        ));
    }
    
    // Agregamos este método
    public function getNodeTable()
    {
    	if (!$this->nodeTable) {
    		$sm = $this->getServiceLocator();
    		$this->nodeTable = $sm->get('Smeagol\Model\NodeTable');
    	}

    	return $this->nodeTable;
    }
    
    public function editAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('admin', array(
    				'controller' => 'noticias','action' => 'index'
    		));
    	}
    	$noticiasTable = $this->getNoticias();
    	$noticias = $noticiasTable->getNoticias($id);  	
    	
    	if($noticias){
    	
	    	// Obtenemos el ViewHelper HeadScript para agregar un javacript en la sección head
	    	// del html; este script controlará la petición en Ajax
	    	$HeadScript = $this->getServiceLocator()->get('viewhelpermanager')->get('HeadScript');
	    	$HeadLink = $this->getServiceLocator()->get('viewhelpermanager')->get('headLink');
	    	$HeadScript->appendFile("/ckeditor/ckeditor.js");
	    	$HeadLink->appendStylesheet("/ckeditor/content.css");
	    	
	    	// verificamos el post
	    	$request = $this->getRequest();
	    	
	    	if($request->isPost()){
	    		// Obtenemos el título, contenido y url del POST
	    		$noticias->title = $request->getPost("titulo");
	    		$noticias->content = $request->getPost("contenido");
	    		$noticias->url = $request->getPost("url");
	    		
	    		// Guardamos los datos
	    		$noticiasTable->saveNoticias($noticias);
	    		
	    		// Redireccionamos la petición
	    		return $this->redirect()->toRoute('admin', array(
	    				'controller' => 'noticias','action' => 'index'
	    		));
	    	}	
	    	
	    	return new ViewModel(array(
	           'noticias'=> $noticias
	        ));
    	}else{
    		return $this->redirect()->toRoute('admin', array(
    				'controller' => 'noticias','action' => 'index'
    		));    		
    	}			
    }
    
    public function addAction() {
		$noticias = $this->getNoticias ();
		// Obtenemos el ViewHelper HeadScript para agregar un javacript en la
		// sección head
		// del html; este script controlará la petición en Ajax
		$HeadScript = $this->getServiceLocator ()->get ( 'viewhelpermanager' )->get ( 'HeadScript' );
		$HeadLink = $this->getServiceLocator ()->get ( 'viewhelpermanager' )->get ( 'headLink' );
		$HeadScript->appendFile ( "/ckeditor/ckeditor.js" );
		$HeadLink->appendStylesheet ( "/ckeditor/content.css" );

    	// verificamos el post
		$request = $this->getRequest ();
		
		$mensaje = "";
		if ($request->isPost ()) {
			// Obtenemos el título, contenido y url del POST
			$noticias->title = $request->getPost ( "titulo" );
			$noticias->content = $request->getPost ( "contenido" );
			$noticias->url = $request->getPost ( "url" );

			// seteamos el ID a  0
			$noticias->id=0;
				
			if (! empty ( $noticias->title ) && ! empty ( $noticias->content ) && ! empty ( $noticias->url )) {
				$noticias->user_id = 1;
				$noticias->created = date ( "Y-m-d H:i:s" );
				$noticias->modified = date ( "Y-m-d H:i:s" );
				$noticias->node_type_id = 2;
				// Guardamos los datos
				$noticias->saveNoticias ( $noticias );
				
				// Redireccionamos la petición
				return $this->redirect ()->toRoute ( 'admin', array (
						'controller' => 'noticias',
						'action' => 'index' 
				) );
			} else {
				$mensaje = "Debe llenar todos los datos";
			}
		}else{
			// Valores predeterminados de las propiedades de noticias
			$noticias->title = "";
			$noticias->content = "";
			$noticias->url = "";
		}
		
		return new ViewModel ( array (
				'noticias' => $noticias,
				'mensaje' => $mensaje 
		) );
	}
        public function deleteAction() {
		$id = ( int ) $this->params ()->fromRoute ( 'id', 0 );
		if (! $id) {
			// Redirect to list of noticiass
			return $this->redirect ()->toRoute ( 'admin', array (
					'controller' => 'noticias',
					'action' => 'index' 
			) );
		}
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$del = $request->getPost ('del');
			
			if ($del == 'SI') {
				$id = ( int ) $request->getPost ( 'id' );
				$this->getNoticias()->deleteNoticias ( $id );
			}
			
			// Redirect to list of noticiass
			return $this->redirect ()->toRoute ( 'admin', array (
					'controller' => 'noticias',
					'action' => 'index' 
			) );
		}
		
		return array (
				'id' => $id,
				'noticias' => $this->getNoticias()->getNoticias($id) 
		);
	}
    
}

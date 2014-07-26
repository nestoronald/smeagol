<?php

namespace Noticia\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Noticia\Model\Noticia;

class IndexController extends AbstractActionController
{
    /**
     * Noticias
     * @var Noticias\Model\Noticias
     */
    protected $_noticia;

    /**
     * Get Noticias object
     * @return Noticias\Model\Noticias
     */
    public function getNoticia()
    {
        if (!$this->_noticia) {
            $sm = $this->getServiceLocator();
            $this->_noticia = $sm->get('Noticia\Model\Noticia');
        }

        return $this->_noticia;
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
        $identifier = "noticia/". $identifier;
        $noticia = $this->getNoticia();
        
        try {
            $noticia = $noticia->getNoticiaByIdentifier($identifier);
            
            // get the renderer to manipulate the title
            $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
            
            // set the noticias title in the html head
            $renderer->headTitle($noticia->title);
            
            // write the models content to the websites content
            $this->layout()->content = '<h1>' . $noticia->title . '</h1>' . $noticia->content;
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
    	
    }
}

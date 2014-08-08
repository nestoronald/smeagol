<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Soap\Server;
use Zend\Soap\Client;
use Zend\Soap\AutoDiscover;
use Smeagol\Service\Hello;

class SoapController extends AbstractActionController {

    public function nowsdlAction() {
        $uri = $this->getRequest()->getUri();
        $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        $hello = new Hello();
        $options = array('location' => $base . '/soap/nowsdl',
            'uri' => $base . '/soap/nowsdl');

        // Instancia del Soap Server; null establece que no se usa un descriptor wsdl
        $server = new Server(null, $options);

        // Los métodos del servicio se depliegan a partir del objeto $hello
        $server->setObject($hello);
        $server->handle();

        // se dshabilita la vista
        return $this->getResponse();
    }

    public function test1Action() {
        $uri = $this->getRequest()->getUri();
        $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

        $options = array('location' => $base . '/soap/nowsdl',
            'uri' => $base.'/soap/nowsdl');

        // Instancia de Soap Client, null establece que no se usa un descriptor wsdl
        $client = new Client(null, $options);

        // Invocando al método remoto sayHello
        echo $client->sayHello("Mundo!");
        return $this->getResponse();
    }

    // método que genera el archivo wsdl
    public function autodiscoverWsdlAction() {
        $uri = $this->getRequest()->getUri();
        $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

        $autodiscover = new AutoDiscover();
        // definiendo la clase para generar su wsdl y el 
        // enlace del soap server
        $autodiscover->setClass("\Smeagol\Service\Hello")
                ->setUri($base.'/soap/withwsdl');
        // Se imprime el XML del descriptor WSDL
        $autodiscover->handle();
        // se deshabilita la vista
        return $this->getResponse();
    }

    public function withWsdlAction() {
        $uri = $this->getRequest()->getUri();
        $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

        // La instancia del soap server tiene que hacerse con el url del archivo wsdl
        $server = new Server($base."/soap/autodiscoverwsdl");
        // Se define la clase desplegada en el web service
        $server->setClass("\Smeagol\Service\Hello");
        // Se despliega el web service
        $server->handle();
        return $this->getResponse();
    }

    public function test2Action() {
        $uri = $this->getRequest()->getUri();
        $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

        $options = array('location' => $base.'/soap/withwsdl',
            'uri' => $base.'/soap/withwsdl');
        // Se instancia el cliente con el enlace del descriptor wsdl
        $client = new Client($base.'/soap/autodiscoverwsdl', $options);
        // se invoca al método remoto
        echo $client->sayHello("Mundo!");
        return $this->getResponse();
    }

}


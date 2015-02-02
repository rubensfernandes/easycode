<?php 

namespace Drk;
use Drk\Uri;
use Drk\Controller;
use Drk\Loader;
use Drk\DoctrineWrapper;

class Application {

	public  $controller;
	public  $method;
	private $em;
	private $config;

	public function __construct($loader, $config)
	{

		$this->loader = $loader;
		$this->setConfig($config);
		
		$apps = $this->getConfig('apps');

		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$uri = new Uri($url);

		$controller = new Controller($uri);
		$controller->setApps($apps);

		$controller->filterApp();

		$this->namespace  = $controller->getNamespace();
		$this->controller = $controller->getController();
		$this->method     = $controller->getMethod();


		if($this->controller === null)
			$this->controller = $this->namespace.'\\Controllers\\'.$this->getConfig('routerDefault');

		$this->loader->add($this->namespace, realpath('../'));
	}

	public function setEm($em)
	{
		$this->em = $em;
	}

	public function getEm()
	{
		return $this->em;
	}

	public function getConfig($i)
	{
		return $this->config[$i];
	}

	public function setConfig($config = array())
	{
		$this->config = $config;
	}

	public function play()
	{	
		$class  = new $this->controller;
		$class->loader = new Loader($this->namespace);
		$class->app = $this;
		$method = $this->method;

		if(method_exists($class, $method))
			$class->$method();
		else
			echo 'metodo: "'.$method.'" n&atilde;o encontrado!';
	}

}
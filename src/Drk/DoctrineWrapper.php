<?php
namespace Drk;

use Doctrine\ORM\Tools\Setup,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common\Cache\ArrayCache as Cache,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Doctrine\Common\ClassLoader;

class DoctrineWrapper {

	private $em;

	public function __construct($configDoc){


		$loader = new ClassLoader('Entity',__DIR__.'/../../../../../'.$configDoc['namespace']);
		$loader->register();
		$loader = new ClassLoader('EntityProxy',__DIR__.'/../../../../../'.$configDoc['namespace']);
		$loader->register();

		//configuration
		$config = new Configuration();
		$cache = new Cache();
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir(__DIR__.'/../../../../../'.$configDoc['namespace'].'/Models/EntityProxy');
		$config->setProxyNamespace($configDoc['namespace'].'\Models\EntityProxy');
		$config->setAutoGenerateProxyClasses(true);

		AnnotationRegistry::registerFile(__DIR__.'/../../../../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
		$driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(
		    new \Doctrine\Common\Annotations\AnnotationReader(),
		    array(__DIR__.'/../../../../../'.$configDoc['namespace']."/Models")
		);
		$config->setMetadataDriverImpl($driver);
		$config->setMetadataCacheImpl($cache);

		//getting the EntityManager
		$em = EntityManager::create(
		    $configDoc['db'],
		    $config
		);

		
		$this->em = $em;

	}

	public function getEm()
	{
		return $this->em;
	}

}
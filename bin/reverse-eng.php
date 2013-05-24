<?php
use Doctrine\ORM\Tools\EntityGenerator;

ini_set("display_errors", "On");

$lib = dirname(__FILE__).'/../library/vendor/Doctrine/lib/';
require $lib . 'vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', $lib . 'vendor/doctrine-common/lib');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', $lib . 'vendor/doctrine-dbal/lib');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', $lib);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

// config
$config = new \Doctrine\ORM\Configuration();
$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__ . '/Entities'));
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionParams = array(
    'dbname' => 'LD_ADMINISTRATIVE',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);

$em = \Doctrine\ORM\EntityManager::create($connectionParams, $config);

// custom datatypes (not mapped for reverse engineering)
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

// fetch metadata
$driver = new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
    $em->getConnection()->getSchemaManager()
);
$em->getConfiguration()->setMetadataDriverImpl($driver);
$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory($em);
$cmf->setEntityManager($em); 
$classes = $driver->getAllClassNames();
$metadata = $cmf->getAllMetadata(); 

$cme = new \Doctrine\ORM\Tools\Export\ClassMetadataExporter();
$generator = new EntityGenerator();
$generator->setGenerateStubMethods(true);
$generator->setUpdateEntityIfExists(true);
$generator->setGenerateAnnotations(true);
$generator->generate($metadata, __DIR__ . '/../library/Application/Entity');
die();
$exporter = $cme->getExporter('annotation', 'models/Entities');
$exporter->setEntityGenerator($generator);
$exporter->setMetadata($metadata);
$exporter->export();
//$generator->setUpdateEntityIfExists(true);
//$generator->setGenerateStubMethods(true);
//$generator->setGenerateAnnotations(true);
//$generator->generate($metadata, __DIR__ . '/../models/Entities');
print 'Done!';

<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

use Doctrine\ORM\EntityManager as EntityManager ,
    Doctrine\ORM\Configuration as Configuration ,
    Doctrine\ORM\Mapping\Driver\XmlDriver as XmlDriver ,
    Doctrine\ORM\ORMException as ORMException ,
    Doctrine\Common\EventManager as EventManager ,
    Doctrine\DBAL\DriverManager as DriverManager ,
    Doctrine\DBAL\Connection as Connection ;

class KhgEntityManager extends EntityManager
{

static private
  $_instance ,
  $_proxyDir ,
  $_proxyNamespace = 'KhgProxy' ,
  $_entitiesDirectoryArr ,
  $_isDevelopment = false ,
  $_connectionOptions = array(
    'dbname'   => 'u949265586_apps',
    'user'     => 'u949265586_apps',
    'password' => 'u949265586',
    'host'     => 'localhost',
    'driver'   => 'pdo_mysql',
  ) ;


protected function __construct ( $pConnectionOptions , $pConfig , $pEventManager = NULL )
{
  if ( ! $pConfig->getMetadataDriverImpl())
  {
    throw ORMException::missingMappingDriverImpl() ;
  }

  if ( is_array( $pConnectionOptions ))
  {
    if ( $pEventManager === NULL )
    {
      $pEventManager = new EventManager() ;
    }

    $pConnectionOptions = DriverManager::getConnection(
      $pConnectionOptions ,
      $pConfig ,
      $pEventManager
    ) ;
  }
  else if ( $pConnectionOptions instanceof Connection )
  {
    if ( $pEventManager !== null &&
         $pConnectionOptions->getEventManager() !== $pEventManager )
    {
      throw ORMException::mismatchedEventManager() ;
    }
  }
  else
  {
    throw new \InvalidArgumentException( "Invalid argument: " . $pConnectionOptions ) ;
  }

  parent::__construct( $pConnectionOptions , $pConfig , $pEventManager ) ;
}

static private function _createInstance ( )
{
  if ( self::$_isDevelopment )
  {
    $cache = new \Doctrine\Common\Cache\ArrayCache() ;
  }
  else
  {
    //My provider does not have apc
    //$cache = new \Doctrine\Common\Cache\ApcCache() ;
    $cache = new \Doctrine\Common\Cache\ArrayCache() ;
  }

  $config = new Configuration ;
  $config->setMetadataCacheImpl( $cache ) ;

  $driverImpl = new XmlDriver( self::$_entitiesDirectoryArr ) ;
  $config->setMetadataDriverImpl($driverImpl);

  $config->setQueryCacheImpl($cache);

  $config->setProxyDir( self::$_proxyDir ) ;
  $config->setProxyNamespace( self::$_proxyNamespace ) ;

  $config->setAutoGenerateProxyClasses( self::$_isDevelopment ) ;

  return new KhgEntityManager( self::$_connectionOptions , $config ) ;

}

static public function getInstance ( )
{
  global $APPS_PHP_DIR ;

  if ( self::$_instance == NULL )
  {
    self::$_proxyDir = $APPS_PHP_DIR . '/KilimanjaroHikingGear/DataModel/Proxies' ;
    self::$_proxyNamespace = 'KhgProxy' ;

    self::$_entitiesDirectoryArr =
      array( $APPS_PHP_DIR . '/KilimanjaroHikingGear/DataModel/Entities' ) ;

    self::$_instance = self::_createInstance() ;
  }

  return self::$_instance ;
}

}

?>
<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

use Doctrine\ORM\Tools\SchemaTool as SchemaTool ;

$manager  = KhgEntityManager::getInstance() ;
$tool = new SchemaTool( $manager ) ;

$classes = array(
  $manager->getClassMetaData( 'KhgItem' ) ,
  $manager->getClassMetaData( 'KhgManufacturer' ) ,
) ;

$tool->dropSchema( $classes ) ;
$tool->createSchema( $classes ) ;

?>
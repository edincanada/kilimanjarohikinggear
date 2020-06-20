
<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

$view = new KhgJsonpView() ;
$request = $view->getRequest() ;

$controller = new KhgController( KhgEntityManager::getInstance()) ;
$controller->setRequest( $request ) ;
$controller->processRequest() ;

$result = $controller->getResult() ;
$view->processResult( $result ) ;

header( 'Content-Type: ' . $view->getContentType()) ;
header( 'Content-Type: application/json' ) ;
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ) ;
header( 'Cache-Control: no-store, no-cache, must-revalidate' ) ;
header( 'Cache-Control: post-check=0, pre-check=0' , false ) ;
header( 'Pragma: no-cache' ) ; // HTTP/1.0
header( "Expires: " . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ) ;

$view->printData() ;

?>
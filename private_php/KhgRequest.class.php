<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

final class KhgRequestType
{
  const
    None = 0 ,
    ItemList = 1 ,
    ManufacturerList = 2 ,
    EditItem = 3 ,
    EditManufacturer = 4 ;
}

class KhgRequest
{

private $_selectedId , $_requestType ;

protected function _initialize ( $pType , $pId )
{
  $this->_requestType = $pType ;
  $this->_selectedId = $pId ;
}

public function __construct ( $pType , $pId )
{
  $this->_initialize( $pType , $pId ) ;
}

public function KhgRequestType ( $pType , $pId )
{
  $this->_initialize( $pType , $pId ) ;
}

public function getSelectedId ( )
{
  return $this->_selectedId ;
}

public function getRequestType ( )
{
  return $this->_requestType ;
}

}

?>
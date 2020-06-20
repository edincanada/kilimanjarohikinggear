<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

class KhgItemEditRequest extends KhgRequest
{

private $_item , $_manufacturerId ;

protected function _initialize ( $pItem , $pManufacturerId )
{
  $this->_manufacturerId = $pManufacturerId ;
  $this->_item = $pItem ;
}

public function __construct ( $pId , $pItem , $pManufacturerId )
{
  parent::_initialize( KhgRequestType::EditItem , $pId ) ;
  $this->_initialize( $pItem , $pManufacturerId ) ;
}

public function KhgItemEditRequest ( $pId , $pItem , $pManufacturerId )
{
  parent::__construct( KhgRequestType::EditItem , $pId ) ;
  $this->_initialize( $pItem , $pManufacturerId ) ;
}

public function getItem ( )
{
  return $this->_item ;
}

public function getManufacturerId ( )
{
  return $this->_manufacturerId ;
}

}

?>

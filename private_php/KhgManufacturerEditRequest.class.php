<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

class KhgManufacturerEditRequest extends KhgRequest
{

private $_manufacturer , $_listOfItemIds ;

protected function _initialize ( $pManufacturer , $pItemIdArray )
{
  $this->_manufacturer = $pManufacturer ;
  $this->_listOfItemIds = $pItemIdArray ;
}

public function __construct ( $pId , $pManufacturer , $pItemIdArray )
{
  parent::_initialize( KhgRequestType::EditManufacturer , $pId ) ;
  $this->_initialize( $pManufacturer , $pItemIdArray ) ;
}

public function KhgManufacturerEditRequest ( $pId , $pManufacturer , $pItemIdArray )
{
  parent::_initialize( KhgRequestType::EditManufacturer , $pId ) ;
  $this->_initialize( $pManufacturer , $pItemIdArray ) ;
}

public function getManufacturer ( )
{
  return $this->_manufacturer ;
}

public function getItemIdArray ( )
{
  return $this->_listOfItemIds ;
}

}

?>
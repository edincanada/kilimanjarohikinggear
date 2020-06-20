<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

final class KhgResultType
{
  const
    None = 0 ,
    ItemList = 1 ,
    ManufacturerList = 2 ;
}

class KhgResult
{

private $_type , $_selectedId , $_items , $_manufacturers , $_itemUpdated ,
        $_manufacturerUpdated ;

private function _initialize (
  $pType ,
  $pSelectedId ,
  $pItems ,
  $pManufacturers ,
  $pItemUpdated ,
  $pManufacturerUpdated
)
{
  $this->_type = $pType ;
  $this->_selectedId = $pSelectedId ;

  $this->_items = $pItems ;
  $this->_manufacturers = $pManufacturers ;

  $this->_itemUpdated = $pItemUpdated ;
  $this->_manufacturerUpdated = $pManufacturerUpdated ;
}

public function __construct (
  $pType ,
  $pSelectedId ,
  $pItems ,
  $pManufacturers ,
  $pItemUpdated ,
  $pManufacturerUpdated
)
{
  $this->_initialize(
    $pType ,
    $pSelectedId ,
    $pItems ,
    $pManufacturers ,
    $pItemUpdated ,
    $pManufacturerUpdated
  ) ;
}

public function KhgResult (
  $pType ,
  $pSelectedId ,
  $pItems ,
  $pManufacturers ,
  $pItemUpdated ,
  $pManufacturerUpdated
)
{
  $this->_initialize(
    $pType ,
    $pSelectedId ,
    $pItems ,
    $pManufacturers ,
    $pItemUpdated ,
    $pManufacturerUpdated
  ) ;
}

public function getResultType ( )
{
  return $this->_type ;
}

public function getSelectedId ( )
{
  return $this->_selectedId ;
}

public function getItems ( )
{
  return $this->_items ;
}

public function getManufacturers ( )
{
  return $this->_manufacturers ;
}

public function manufacturerUpdated ( )
{
  return $this->_manufacturerUpdated ;
}

public function itemUpdated ( )
{
  return $this->_itemUpdated ;
}

}

?>
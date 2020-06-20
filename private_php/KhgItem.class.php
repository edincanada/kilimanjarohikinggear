<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

class KhgItem
{

const CLASS_NAME = 'KhgItem' ,
      ID_FIELD_NAME = '_itemId' ;

private $_itemId , $_name , $_itemCode , $_description , $_stockCount , $_discontinued ,
        $_manufacturer , $_imageUrl ;

private function _initialize (
  $pName ,
  $pCode ,
  $pDescription ,
  $pStock ,
  $pDiscontinued ,
  $pImageUrl ,
  $pManufacturer = NULL ,
  $preserveManufacturer = false
)
{
  $this->_name = "{$pName}" ;
  $this->_itemCode = "{$pCode}" ;
  $this->_description = "{$pDescription}" ;
  $this->_stockCount = (int)( $pStock ) ;
  $this->_discontinued = (bool)( $pDiscontinued ) ;
  $this->_imageUrl = "{$pImageUrl}" ;

  if ( ! $preserveManufacturer )
  {
    $this->_manufacturer = $pManufacturer ;
  }
}

public function __construct (
  $pName ,
  $pCode ,
  $pDescription ,
  $pStock ,
  $pDiscontinued ,
  $pImageUrl ,
  $pManufacturer
)
{
  $this->_initialize(
    $pName ,
    $pCode ,
    $pDescription ,
    $pStock ,
    $pDiscontinued ,
    $pImageUrl ,
    $pManufacturer
  ) ;
}


public function KhgItem (
  $pName ,
  $pCode ,
  $pDescription ,
  $pStock ,
  $pDiscontinued ,
  $pImageUrl ,
  $pManufacturer
)
{
  $this->_initialize(
    $pName ,
    $pCode ,
    $pDescription ,
    $pStock ,
    $pDiscontinued ,
    $pImageUrl ,
    $pManufacturer
  ) ;
}

public function setName ( $pName )
{
  $this->_name = "{$pName}" ;
}

public function setCode ( $pCode )
{
  $this->_itemCode = "{$pCode}" ;
}

public function setDescription ( $pDescription )
{
  $this->_description = "{$pDescription}" ;
}

public function setStockCount ( $pStock )
{
  $this->_stockCount = (int)( $pStock ) ;
}

public function setDiscontinued ( $pDiscontinued )
{
  $this->_discontinued = (bool)( $pDiscontinued ) ;
}

public function setImageUrl ( $pImageUrl )
{
  $this->_imageUrl = "{$pImageUrl}" ;
}

public function setManufacturer ( $pManufacturer )
{
  $this->_manufacturer = $pManufacturer ;
}

public function getId ( )
{
  return $this->_itemId ;
}

public function getName ( )
{
  return $this->_name ;
}

public function getItemCode ( )
{
  return $this->_itemCode ;
}

public function getDescription ( )
{
  return $this->_description ;
}

public function getStockCount ( )
{
  return $this->_stockCount ;
}

public function isDiscontinued ( )
{
  return $this->_discontinued ;
}

public function getImageUrl ( )
{
  return $this->_imageUrl ;
}

public function getManufacturer ( )
{
  return $this->_manufacturer ;
}

public function copyFrom ( $pItem )
{
  $this->_initialize(
    $pItem->_name ,
    $pItem->_itemCode ,
    $pItem->_description ,
    $pItem->_stockCount ,
    $pItem->_discontinued ,
    $pItem->_imageUrl ,
    NULL ,
    true
  ) ;
}

}

?>
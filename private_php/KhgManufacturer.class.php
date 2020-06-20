<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

use Doctrine\Common\Collections\ArrayCollection as ArrayCollection ;

class KhgManufacturer
{

const CLASS_NAME = 'KhgManufacturer' ,
      ID_FIELD_NAME = '_manufacturerId' ;

private $_manufacturerId , $_name , $_description , $_address , $_phoneNumber , $_email ,
        $_items ;

private function _initialize (
  $pName ,
  $pDescription ,
  $pAddress ,
  $pNumber ,
  $pEmail ,
  $pPreserveArray = false
)
{
  $this->_name = "{$pName}" ;
  $this->_description = "{$pDescription}" ;
  $this->_address = "{$pAddress}" ;
  $this->_phoneNumber = "{$pNumber}" ;
  $this->_email = "{$pEmail}" ;

  if ( ! $pPreserveArray )
  {
    $this->_items = new ArrayCollection() ;
  }
}

public function __construct (
  $pName ,
  $pDescription ,
  $pAddress ,
  $pNumber ,
  $pEmail
)
{
  $this->_initialize( $pName , $pDescription , $pAddress , $pNumber , $pEmail ) ;
}

public function KhgManufacturer (
  $pName ,
  $pDescription ,
  $pAddress ,
  $pNumber ,
  $pEmail
)
{
  $this->_initialize( $pName , $pDescription , $pAddress , $pNumber , $pEmail ) ;
}

public function setName ( $pName )
{
  $this->_name = "{$pName}" ;
}

public function setDescription ( $pDescription )
{
  $this->_description = "{$pDescription}" ;
}

public function setAddress ( $pAddress )
{
  $this->_address = "{$pAddress}" ;
}

public function setPhoneNumber ( $pNumber )
{
  $this->_phoneNumber = "{$pNumber}" ;
}

public function setEmail ( $pEmail )
{
  $this->_email = "{$pEmail}" ;
}

public function getId ( )
{
  return $this->_manufacturerId ;
}

public function getName ( )
{
  return $this->_name ;
}

public function getDescription ( )
{
  return $this->_description ;
}

public function getAddress ( )
{
  return $this->_address ;
}

public function getPhoneNumber ( )
{
  return $this->_phoneNumber ;
}

public function getEmail ( )
{
  return $this->_email ;
}

public function getItems ( )
{
  return $this->_items ;
}

public function copyFrom ( $pManufacturer )
{
  $this->_initialize(
    $pManufacturer->_name ,
    $pManufacturer->_description ,
    $pManufacturer->_address ,
    $pManufacturer->_phoneNumber ,
    $pManufacturer->_email ,
    true
  ) ;

  $this->_items->clear() ;

  foreach ( $pManufacturer->_items as $currentItem )
  {
    $this->_items->add( $currentItem ) ;
  }
}

}

?>
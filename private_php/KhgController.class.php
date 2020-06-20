<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

class KhgController
{

private $_request , $_result , $_entityManager ;

static private $_itemIdAsKeyCallBack = NULL ;
static private $_manufacturerIdAsKeyCallBack = NULL ;

private function _initialize ( $pEntityManager )
{
  $this->_entityManager = $pEntityManager ;
  self::_initializeCallbacks() ;
}

static private function arrayToAssoc ( $pArray , $pKeyGenerator )
{
  $retArray = Array() ;
  foreach ( $pArray as $currentObject )
  {
    $retArray[ $pKeyGenerator( $currentObject )] = $currentObject ;
  }

  return $retArray ;
}

static private function _initializeCallbacks ( )
{
  if ( self::$_itemIdAsKeyCallBack == NULL )
  {
    self::$_itemIdAsKeyCallBack = function ( $pItem )
    {
      return $pItem->getId() ;
    } ;
  }

  if ( self::$_manufacturerIdAsKeyCallBack == NULL )
  {
    self::$_manufacturerIdAsKeyCallBack = function ( $pManufacturer )
    {
      return $pManufacturer->getId() ;
    } ;
  }

}

static private function firstKey ( $pArray )
{
  $keys = array_keys( $pArray ) ;
  return array_shift( $keys ) ;
}

static private function _assignItemToManufacturer ( $pItem , $pManufacturer )
{
    if ( $pItem->getManufacturer()->getId() != $pManufacturer->getId())
    {
      $pItem->getManufacturer()->getItems()->removeElement( $pItem ) ;
      $pItem->setManufacturer( $pManufacturer ) ;
      $pManufacturer->getItems()->add( $pItem ) ;
    }
}

static private function _assignItemArrayToManufacturer ( $pItemArray , $pManufacturer )
{
  $pManufacturer->getItems()->clear() ;

  foreach ( $pItemArray as $currentItem )
  {
    if ( $currentItem->getManufacturer()->getId() != $pManufacturer->getId())
    {
      $currentItem->setManufacturer( $pManufacturer ) ;
      $pManufacturer->getItems()->add( $currentItem ) ;
    }
  }
}

private function _processItemListRequest ( )
{
  $itemRepo = $this->_entityManager->getRepository( KhgItem::CLASS_NAME ) ;
  $items = self::arrayToAssoc( $itemRepo->findAll() , self::$_itemIdAsKeyCallBack ) ;

  $selectedId = $this->_request->getSelectedId() ;

  if ( ! array_key_exists( $selectedId , $items ))
  {
    $selectedId = self::firstKey( $items ) ;
  }

  $result = new KhgResult(
    KhgResultType::ItemList ,
    $selectedId ,
    $items ,
    NULL ,
    false ,
    false
  ) ;

  return $result ;
}

private function _processManufacturerListRequest ( )
{
  $manufacturerRepo = $this->_entityManager->getRepository( KhgManufacturer::CLASS_NAME ) ;

  $manufacturers = self::arrayToAssoc(
    $manufacturerRepo->findAll() ,
    self::$_manufacturerIdAsKeyCallBack
  ) ;

  $selectedId = $this->_request->getSelectedId() ;

  if ( array_key_exists( $selectedId , $manufacturers ))
  {
    $selectedId = self::firstKey( $manufacturers ) ;
  }

  $result = new KhgResult(
    KhgResultType::ManufacturerList ,
    $selectedId ,
    NULL ,
    $manufacturers ,
    false ,
    false
  ) ;

  return $result ;
}

private function _processItemEditRequest ( )
{

  $itemRepo = $this->_entityManager->getRepository( KhgItem::CLASS_NAME ) ;
  $items = self::arrayToAssoc( $itemRepo->findAll() ,  self::$_itemIdAsKeyCallBack ) ;

  $item = $itemRepo->findOneBy(
    array( KhgItem::ID_FIELD_NAME => $this->_request->getSelectedId())
  ) ;

  $manufacturerRepo =
    $this->_entityManager->getRepository( KhgManufacturer::CLASS_NAME ) ;

  $requestManufacturer = $manufacturerRepo->findOneBy(
    array( KhgManufacturer::ID_FIELD_NAME => $this->_request->getManufacturerId())
  ) ;

  $requestItem = $this->_request->getItem() ;
  $itemUpdated = false ;

  $selectedId = self::firstKey( $items ) ;

  if ( !( $item == NULL || $requestItem == NULL ))
  {
    //Do not overwrite imageurl
    $imageUrl = $item->getImageUrl() ;
    $item->copyFrom( $requestItem ) ;
    $item->setImageUrl( $imageUrl ) ;


    $itemUpdated = true ;
    $selectedId = $item->getId() ;
    self::_assignItemToManufacturer( $item , $requestManufacturer ) ;

    //Not necessary
    //$this->_entityManager->persist( $item ) ;
    //$this->_entityManager->persist( $requestManufacturer ) ;
  }

  $result = new KhgResult(
    KhgResultType::ItemList ,
    $selectedId ,
    $items ,
    NULL ,
    $itemUpdated ,
    false
  ) ;

  return $result ;
}

private function _processManufacturerEditRequest ( )
{
  $manufacturerRepo =
    $this->_entityManager->getRepository( KhgManufacturer::CLASS_NAME ) ;

  $manufacturers = self::arrayToAssoc(
    $manufacturerRepo->findAll() ,
    self::$_manufacturerIdAsKeyCallBack
  ) ;

  $itemRepo = $this->_entityManager->getRepository( KhgItem::CLASS_NAME ) ;
  $requestItems = array() ;
  $requestItemIds = $this->_request->getItemIdArray() ;

  foreach ( $requestItemIds as $currentItemId )
  {
    $currentItem =
      $itemRepo->findOneBy( array( KhgItem::ID_FIELD_NAME => $currentItemId)) ;

    if ( $currentItem != NULL )
    {
      $requestItems[] = $currentItem ;
    }
  }

  $manufacturer = $manufacturerRepo->findOneBy(
    array(
      KhgManufacturer::ID_FIELD_NAME =>
       $this->_request->getSelectedId()
    )
  ) ;

  $requestManufacturer = $this->_request->getManufacturer() ;
  $manufacturerUpdated = false ;

  $selectedId = self::firstKey( $manufacturers ) ;

  if ( ! ( $manufacturer == NULL || $requestManufacturer == NULL ))
  {
    $manufacturer->copyFrom( $requestManufacturer ) ;
    $manufacturerUpdated = true ;
    $selectedId = $manufacturer->getId() ;

    self::_assignItemArrayToManufacturer( $requestItems , $manufacturer ) ;

    //Not necessary
    //$this->_entityManager->persist( $manufacturer ) ;
    //foreach( $requestItems as $currentItem )
    //{
    //  $this->_entityManager->persist( $currentItem ) ;
    //}
  }

  $result = new KhgResult(
    KhgResultType::ManufacturerList ,
    $selectedId ,
    NULL ,
    $manufacturers ,
    false ,
    $manufacturerUpdated
  ) ;

  return $result ;
}

public function __construct ( $pEntityManager )
{
  $this->_initialize( $pEntityManager ) ;
}

public function KhgController ( $pEntityManager )
{
  $this->_initialize( $pEntityManager ) ;
}

public function setRequest ( $pRequest )
{
  $this->_request = $pRequest ;
}

public function processRequest ( )
{
  $requestType = $this->_request->getRequestType() ;

  switch ( $requestType )
  {
    case KhgRequestType::ItemList :
    {
      $this->_result = $this->_processItemListRequest() ;
    } break ;

    case KhgRequestType::ManufacturerList :
    {
      $this->_result = $this->_processManufacturerListRequest() ;
    } break ;

    case KhgRequestType::EditItem :
    {
      $this->_result = $this->_processItemEditRequest() ;
    } break ;

    case KhgRequestType::EditManufacturer :
    {
      $this->_result = $this->_processManufacturerEditRequest() ;
    } break ;

    default :
    {
    } break ;
  }

  if ( $this->_result->itemUpdated() || $this->_result->manufacturerUpdated())
  {
    $this->_entityManager->flush() ;
  }
}

public function getResult ( )
{
  return $this->_result ;
}

}

?>
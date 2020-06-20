<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

class KhgJsonpView
{

const _GET = 'get' , _MANUFACTURERS = 'manufacturers' , _METHOD = 'method' ,
      _CALLBACK = 'callback' , _ASSIGN = 'assign' ,
      JSON_CONTENT_TYPE = 'application/json' ,
      JAVASCRIPT_CONTENT_TYPE = 'text/javascript' ;

static private $_falseStr = 'false' ,
               $_trueStr = 'true' ,
               $_assignFormat = '%s = %s ;' ,
               $_rawFormat = '%s%s' ;

static private $_dataTemplate = <<<JSON_HERE_DOC
{
  "ids" : %s ,
  "%s"  : %s
}
JSON_HERE_DOC;

static private $_itemJsonTemplate = <<<JSON_HERE_DOC
"%d" : {
  "id" : %d ,
  "name" : "%s" ,
  "itemCode" : "%s" ,
  "description" : "%s" ,
  "stockCount" : %d ,
  "isDiscontinued" : %s ,
  "imageUrl" : "%s" ,
  "manufacturerId" : "%d"
}

JSON_HERE_DOC;

static private $_manufacturerJsonTemplate = <<<JSON_HERE_DOC
"%d" : {
  "id" : %d ,
  "name" : "%s" ,
  "description" : "%s" ,
  "address" : "%s" ,
  "email" : "%s" ,
  "phoneNumber" : "%s" ,
  "itemIdList" : %s
}

JSON_HERE_DOC;

static private $_callbackFormat = <<<CALLBACK_HERE_DOC
%s(
 ( function ()
 {
   return %s ;
 })()
) ;
CALLBACK_HERE_DOC;

private $_result , $_jsonData , $_request , $_format , $_jsElement ;

private function _initialize ( $pArray )
{
  $requestType = KhgRequestType::ItemList ;

  if (
    array_key_exists( self::_GET , $pArray ) &&
    $pArray[ self::_GET ] == self::_MANUFACTURERS
  )
  {
    $requestType = KhgRequestType::ManufacturerList ;
  }

  if ( array_key_exists( self::_CALLBACK , $pArray ))
  {
    $this->_format = self::$_callbackFormat ;
    $this->_jsElement = $pArray[ self::_CALLBACK ] ;
  }
  else if ( array_key_exists( self::_ASSIGN , $pArray ))
  {
    $this->_format = self::$_assignFormat ;
    $this->_jsElement = $pArray[ self::_ASSIGN ] ;
  }
  else
  {
    $this->_format = self::$_rawFormat ;
    $this->_jsElement = '' ;
  }

  $this->_request = new KhgRequest( $requestType , NULL ) ;
}

public function __construct ( )
{
  global $_GET ;
  $this->_initialize( $_GET ) ;
}

public function KhgJsonpView ( )
{
  global $_GET ;
  $this->_initialize( $_GET ) ;
}

static function _escapeDoubleQuotes ( $pString )
{
  return str_replace( '"' , '\"', $pString ) ;
}

static private function _arrayToJsonArray ( $pArray , $pWrapWithQuotes = false )
{
  $retString = '' ;
  $retSeparator = '' ;
  $quote = '' ;

  if ( $pWrapWithQuotes )
  {
    $quote = '"' ;
  }

  foreach ( $pArray as $currentElement )
  {
    $retString = $retString . $retSeparator . $quote . self::_escapeDoubleQuotes( $currentElement ) . $quote ;
    $retSeparator = ',' ;
  }

  return '[' . $retString . ']' ;
}

private function _processItemListResult ( )
{
  $items = $this->_result->getItems() ;
  $itemsAsJsonArray = array() ;
  $idArray = array() ;

  foreach ( $items as $currentItem )
  {
    $idArray[] = '"' . "{$currentItem->getId()}" . '"' ;

    $itemsAsJsonArray[] = sprintf(
      self::$_itemJsonTemplate ,
      $currentItem->getId() ,
      $currentItem->getId() ,
      self::_escapeDoubleQuotes( $currentItem->getName()) ,
      self::_escapeDoubleQuotes( $currentItem->getItemCode()) ,
      self::_escapeDoubleQuotes( $currentItem->getDescription()) ,
      $currentItem->getStockCount() ,
      $currentItem->isDiscontinued() ? self::$_trueStr : self::$_falseStr ,
      self::_escapeDoubleQuotes( $currentItem->getImageUrl()) ,
      $currentItem->getManufacturer()->getId()
    ) ;
  }

  $idsJson  = '[' . implode( ',' , $idArray ) . ']' ;
  $jsonData = '{' . implode( ',' , $itemsAsJsonArray ) . '}' ;

  $this->_jsonData = sprintf( self::$_dataTemplate , $idsJson , 'items' , $jsonData ) ;
}

private function _processManufacturerListResult ( )
{
  $manufacturers = $this->_result->getManufacturers() ;
  $manufacturersAsJsonArray = array() ;
  $idArray = array() ;

  foreach ( $manufacturers as $currentManufacturer )
  {
    $items = $currentManufacturer->getItems() ;
    $itemIdArray = array() ;
    foreach ( $items as $currentItem )
    {
      $itemIdArray[] = $currentItem->getId() ;
    }

    $idArray[] = $currentManufacturer->getId() ;

    $manufacturersAsJsonArray[] = sprintf(
      self::$_manufacturerJsonTemplate ,
      $currentManufacturer->getId() ,
      $currentManufacturer->getId() ,
      self::_escapeDoubleQuotes( $currentManufacturer->getName()) ,
      self::_escapeDoubleQuotes( $currentManufacturer->getDescription()) ,
      self::_escapeDoubleQuotes( $currentManufacturer->getAddress()) ,
      self::_escapeDoubleQuotes( $currentManufacturer->getEmail()) ,
      self::_escapeDoubleQuotes( $currentManufacturer->getPhoneNumber()) ,
      self::_arrayToJsonArray( $itemIdArray , true )
    ) ;
  }

  $idsJson = self::_arrayToJsonArray( $idArray , true ) ;
  $jsonData = '{' . implode( ',' , $manufacturersAsJsonArray ) . '}' ;

  $this->_jsonData = sprintf( self::$_dataTemplate , $idsJson , 'manufacturers' , $jsonData ) ;
}

public function getRequest ( )
{
  return $this->_request ;
}

public function processResult ( $pResult )
{
  $this->_result = $pResult ;

  switch ( $this->_result->getResultType())
  {
    case KhgResultType::ItemList :
    {
      $this->_processItemListResult() ;
    }
    break ;

    case KhgResultType::ManufacturerList :
    {
      $this->_processManufacturerListResult() ;
    }
    break ;
  }
}

public function getContentType( )
{
  if ( $this->_format == self::$_rawFormat )
  {
    return self::JSON_CONTENT_TYPE ;
  }
  else
  {
    return self::JAVASCRIPT_CONTENT_TYPE ;
  }
}

public function printData ( )
{
  echo sprintf( $this->_format , $this->_jsElement , $this->_jsonData ) ;
}

}

?>
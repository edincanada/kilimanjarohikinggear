<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

class KhgItemEditView
{

const POST_METHOD = 'POST' , REQUEST_METHOD = 'REQUEST_METHOD' ;
static private $_itemIdKey = 'itemid' ;
static private $_lineBreak = "\n" ;

static private $_nameKey = 'txfName' ,
               $_itemCodeKey = 'txfItemCode' ,
               $_stockCountKey = 'nmfStockCount' ,
               $_isDiscontinuedKey = 'chbEditDiscontinued' ,
               $_descriptionKey = 'txaDescription' ,
               $_manufacturerKey = 'hdnManufacturer' ,
               $_checkedAttr = 'checked="checked" ' ;

static private $_formKeys = array (
  'txfName' ,
  'txfItemCode' ,
  'nmfStockCount' ,
  'chbEditDiscontinued' ,
  'txaDescription' ,
  'hdnManufacturer'
) ;

static private $_errorMessages = array (
  'txfName' => 'Invalid name' ,
  'txfItemCode' => 'Invalid item code',
  'nmfStockCount' => 'Invalid stock count' ,
  'chbEditDiscontinued' => 'Invalid value for discontinued' ,
  'txaDescription' => 'Invalid description' ,
  'hdnManufacturer' => 'Invalid manufacturer'
) ;

static private $_errorLiFormat ='<li>%s</li>' ;
static private $_errorUlTemplate = <<<HERE_DOC
<tr>
 <td colspan="2">
  The following errors were found:
  <ul id="ulErrorList" class="cssUlErrorList">
%s
  </ul>
 </td>
</tr>
HERE_DOC;

static private $_validate = NULL ;


private $_errors , $_request = NULL , $_formName = '' , $_formItemCode = '' , $_formStockCount = '' ,
        $_formDiscontinued = '' , $_formDescription = '' , $_formManufacturerId = '' ;

static private function _initializeValidators ( )
{
  if ( self::$_validate == NULL )
  {
    self::$_validate = array (
      'txfName' => function ( $pName )
      {
        return $pName != NULL && preg_match( '/[A-Za-z0-9]/' , $pName ) ;
      } ,
      'txfItemCode' => function ( $pCode )
      {
        return $pCode != NULL && preg_match( '/^[A-Za-z0-9][A-Za-z0-9]*$/' , trim( $pCode )) ;
      } ,
      'nmfStockCount' => function ( $pCount )
      {
        return $pCount != NULL && preg_match( '/^[0-9]*?[1-9][0-9]*$/' , trim( $pCount )) ;
      } ,
      'chbEditDiscontinued' => function ( $pBox )
      {
        return $pBox == NULL || strtoupper( $pBox ) == 'DISCONTINUED' ;
      } ,
      'txaDescription' => function ( $pDescription )
      {
        return $pDescription != NULL && preg_match( '/[A-Za-z0-9]/' , $pDescription ) ;
      }  ,
      'hdnManufacturer' => function ( $pCount )
      {
        return $pCount != NULL && preg_match( '/^[0-9][0-9]*$/' , trim( $pCount )) ;
      }
    ) ;
  }
}

private function _initialize ( $pServer , $pGet , $pPost )
{
  if ( array_key_exists( self::$_itemIdKey, $pGet ))
  {
    $itemId = $pGet[ self::$_itemIdKey ] ;
    if ( $pServer[ self::REQUEST_METHOD ] == self::POST_METHOD )
    {
      $this->_initializeFromPost( $pPost , (int)( $itemId )) ;
    }
  }
}

static private function _untaintString ( $pStr )
{
  $str = $pStr ;

  if ( $str == NULL )
  {
    $str = '' ;
  }

  $str = trim( $str ) ;
  $str = str_replace( '&' , '&amp;'  , $str ) ;
  $str = str_replace( '"' , '&quot;' , $str ) ;
  $str = str_replace( "'" , '&#39;' , $str ) ;
  $str = str_replace( '\\' , '&#92;' , $str ) ;

  return $str ;
}

private function _initializeFromPost ( $pPostArray , $pId )
{
  $validateFunction = NULL ;

  if ( ! array_key_exists( self::$_isDiscontinuedKey , $pPostArray ))
  {
    $pPostArray[ self::$_isDiscontinuedKey ] = NULL ;
  }

  foreach ( self::$_formKeys as $currentFormKey )
  {
    $validateFunction = self::$_validate[ $currentFormKey ] ;
    if ( ! $validateFunction( $pPostArray[ $currentFormKey ]))
    {
      $this->_errors[ $currentFormKey ] = self::$_errorMessages[ $currentFormKey ] ;
    }
  }

  if ( count( $this->_errors ) < 1 )
  {
    $updatedItem = new KhgItem(
      trim( $pPostArray[ self::$_nameKey ]) ,
      trim( $pPostArray[ self::$_itemCodeKey]) ,
      trim( $pPostArray[ self::$_descriptionKey ]) ,
      (int)( $pPostArray[ self::$_stockCountKey ]) ,
      strtoupper( $pPostArray[ self::$_isDiscontinuedKey ] ) == 'DISCONTINUED' ,
      NULL ,
      NULL
    ) ;

    $this->_request = new KhgItemEditRequest(
      $pId ,
      $updatedItem ,
      (int)( $pPostArray[ self::$_manufacturerKey ])
    ) ;
  }
  else
  {
    $this->_formName = self::_untaintString( $pPostArray[ self::$_nameKey ]) ;
    $this->_formItemCode = self::_untaintString( $pPostArray[ self::$_itemCodeKey]) ;
    $this->_formDescription = self::_untaintString( $pPostArray[ self::$_descriptionKey ]) ;
    $this->_formStockCount = self::_untaintString( $pPostArray[ self::$_stockCountKey ]) ;

    $this->_formDiscontinued =
      strtoupper( $pPostArray[ self::$_isDiscontinuedKey ]) == 'DISCONTINUED' ;

    $this->_formManufacturerId = (int)( $pPostArray[ self::$_manufacturerKey ]) ;
    $this->_request = NULL ;
  }
}

public function __construct ( )
{
  global $_SERVER , $_GET , $_POST ;
  self::_initializeValidators() ;
  $this->_initialize( $_SERVER , $_GET , $_POST ) ;
}

public function KhgItemEditView ( )
{
  global $_SERVER , $_GET , $_POST ;
  self::_initializeValidators() ;
  $this->_initialize( $_SERVER , $_GET , $_POST ) ;
}

public function getRequest ( )
{
  return $this->_request ;
}

public function processResult ( $pResult )
{
  global $HOST_NAME ;
  
  if ( $pResult != NULL )
  {
    header(
      'Location: http://' . $HOST_NAME . '/apps/kilimanjarohikinggear/index.html?id=' .
      $pResult->getSelectedId()
    ) ;

    exit() ;
  }
}

static private function _addIndentation (
  $pString ,
  $pTabCount ,
  $pTabSize = 1 ,
  $pIndentFirstLine = false
)
{
  $tabWidth = $pTabCount * $pTabSize ;
  $indentation = sprintf( "%{$tabWidth}s" , '' ) ;

  $lineArray = explode( self::$_lineBreak , $pString ) ;

  $currentIndendation = '' ;
  if ( $pIndentFirstLine )
  {
    $currentIndendation = $indentation ;
  }

  foreach ( $lineArray as $currentLineIndex => $currentLine )
  {
    if ( $lineArray[ $currentLineIndex ] != '')
    {
      $lineArray[ $currentLineIndex ] = $currentIndendation . $currentLine ;
    }

    $currentIndendation = $indentation ;
  }

  return implode( self::$_lineBreak , $lineArray ) ;
}


public function printItemName ( )
{
  echo $this->_formName ;
}

public function printItemCode ( )
{
  echo $this->_formItemCode ;
}

public function printStockCount ( )
{
  echo $this->_formStockCount ;
}

public function printDiscontinuedAttribute ( )
{
  if ( $this->_formDiscontinued )
  {
    echo self::$_checkedAttr ;
  }
}

public function printDescription ( )
{
  echo $this->_formDescription ;
}


public function printManufacturerId ( )
{
  echo $this->_formManufacturerId ;
}

public function printErrorsFound ( )
{
  if ( count( $this->_errors ))
  {
    foreach( $this->_errors as $currentError )
    {
      $errorLiElements[] = sprintf( self::$_errorLiFormat , $currentError ) ;
    }

    $errorsStr = self::_addIndentation( implode( "\n" , $errorLiElements ) , 3 ) ;
    echo self::_addIndentation( sprintf( self::$_errorUlTemplate , $errorsStr ) , 2 ) ;
  }
  else
  {
    echo self::$_lineBreak ;
  }
}

}
?>
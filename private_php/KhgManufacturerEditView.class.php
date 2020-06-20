<?php

class KhgManufacturerEditView
{

const REQUEST_METHOD = 'REQUEST_METHOD' ,
      POST_METHOD = 'POST' ;
  
static private $_validate = NULL ;

static private $_nameKey = 'txfName' ,
               $_descriptionKey = 'txaDescription' ,
               $_addressKey = 'txaAddress' ,
               $_phoneNumberKey = 'nmfPhoneNumber' ,
               $_emailKey = 'txfEmail' ,
               $_itemListKey = 'hdnItemList' ,
               $_manufacturerKey = 'manufacturerid' ,
               $_lineBreak = "\n" ;


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
//validate fields.

//If they are valid, create an edito request.
//if not, send it back with those field values.

static private $_formKeys = array (
  'txfName' ,
  'txfEmail' ,
  'txaAddress' ,
  'nmfPhoneNumber' ,
  'txaDescription' ,
  'hdnItemList'
) ;

static private $_errorMessages = array (
  'txfName' => 'Invalid name' ,
  'txfEmail' => 'Invalid email' ,
  'txaAddress' => 'Invalid address' ,
  'nmfPhoneNumber' => 'Invalid phone number' ,
  'txaDescription' => 'Invalid desription' ,
  'hdnItemList' => 'Invalid items selected'
) ;

private $_errors , $_request , $_name , $_description , $_address , $_email ,
        $_phoneNumber , $_itemIdList ;

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

static private function _initializeValidators ( )
{
  if ( self::$_validate == NULL )
  {
    self::$_validate = array (
      'txfName' => function ( $pName )
      {
        return $pName != NULL && preg_match( '/[a-zA-Z]+/', $pName ) ;
      } ,

      'txfEmail' => function ( $pEmail )
      {
        return
          $pEmail != NULL &&
          preg_match( '/[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*/', $pEmail ) ;
      } ,

      'txaAddress' => function ( $pAddress )
      {
        return $pAddress != NULL && preg_match( '/[a-zA-Z]+/', $pAddress ) ;
      } ,

      'nmfPhoneNumber' => function ( $pNumber )
      {
        return $pNumber != NULL && preg_match( '/^[0-9]+$/', $pNumber ) ;
      } ,

      'txaDescription' => function ( $pDescription )
      {
        return $pDescription != NULL && preg_match( '/[a-zA-Z0-9]+/', $pDescription ) ;
      } ,

      'hdnItemList' => function ( $pItemList )
      {
        $currentItemId = NULL ;
        $retIsValid = true ;
        foreach ( $pItemList as $currentItemId )
        {
          $retIsValid = $retIsValid && preg_match( '/^[0-9]+$/', $currentItemId ) ;
        }

        return $retIsValid ;
      }
    ) ;
  }
}

private function _initialize ( $pServer , $pGet , $pPost )
{
  if ( array_key_exists( self::$_manufacturerKey, $pGet ))
  {
    $itemId = $pGet[ self::$_manufacturerKey ] ;
    if ( $pServer[ self::REQUEST_METHOD ] == self::POST_METHOD )
    {
      $this->_initializeFromPost( $pPost , (int)( $itemId )) ;
    }
  }
}

private function _initializeFromPost ( $pArray , $pId )
{
  $this->_errors = array() ;
  foreach ( self::$_formKeys as $currentFormKey )
  {
    $validateFunction = self::$_validate[ $currentFormKey ] ;
    if ( ! $validateFunction( $pArray[ $currentFormKey ]))
    {
      $this->_errors[ $currentFormKey ] = self::$_errorMessages[ $currentFormKey ] ;
    }
  }

  $this->_itemIdList = array() ;
  
  if ( array_key_exists( self::$_itemListKey, $pArray ) &&
       is_array( $pArray[ self::$_itemListKey ] ))
  {
    foreach ( $pArray[ self::$_itemListKey ] as $currentItemId )
    {
      $this->_itemIdList[] = (int)( $currentItemId ) ;
    }
  }

  if ( count( $this->_errors ) < 1 )
  {
    $updatedManufacturer = new KhgManufacturer(
      trim( $pArray[ self::$_nameKey ]) ,
      trim( $pArray[ self::$_descriptionKey ]) ,
      trim( $pArray[ self::$_addressKey ]) ,
      trim( $pArray[ self::$_phoneNumberKey ]) ,
      trim( $pArray[ self::$_emailKey ])
    ) ;

    $this->_request = new KhgManufacturerEditRequest(
      $pId ,
      $updatedManufacturer ,
      $this->_itemIdList
    ) ;
  }
  else
  {
    $this->_name = self::_untaintString( $pArray[ self::$_nameKey ]) ;
    $this->_description = self::_untaintString( $pArray[ self::$_descriptionKey ]) ;
    $this->_address = self::_untaintString( $pArray[ self::$_addressKey ]) ;
    $this->_phoneNumber = self::_untaintString( $pArray[ self::$_phoneNumberKey ]) ;
    $this->_email = self::_untaintString( $pArray[ self::$_emailKey ]) ;
  }
}

public function __construct ( )
{
  global $_SERVER , $_GET , $_POST ;
  self::_initializeValidators() ;
  $this->_initialize( $_SERVER , $_GET , $_POST ) ;
}

public function getRequest ( )
{
  return $this->_request ;
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

public function printName ( )
{
  echo $this->_name ;
}

public function printPhoneNumber ( )
{
  echo $this->_phoneNumber ;
}

public function printEmail ( )
{
  echo $this->_email ;
}

public function printAddress ( )
{
  echo $this->_address ;
}

public function printDescription ( )
{
  echo $this->_description ;
}

static private function _toJsonString ( $pArray )
{
  $strArray = array() ;
  foreach ( $pArray as $currentElement )
  {
    $strArray[] = '"' . $currentElement . '"' ;
  }

  return '[' . implode( ', ' , $strArray ) . ']' ;
}

public function printItemListJSONString ( )
{
  $jsonStr = '' ;
  if ( $this->_itemIdList != NULL && count( $this->_itemIdList ) > 0 )
  {
    $jsonStr = self::_toJsonString( $this->_itemIdList ) ;
  }

  print self::_untaintString( $jsonStr ) ;
}

public function processResult ( $pResult )
{
  global $HOST_NAME ;
  
  if ( $pResult != NULL )
  {
    header(
      'Location: http://' . $HOST_NAME . '/apps/kilimanjarohikinggear/manufacturers.html?id=' .
      $pResult->getSelectedId()
    ) ;

    exit() ;
  }
}

}

?>
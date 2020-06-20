<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

class KhgView
{

static private $_lineBreak = "\n" ;
static private $_checkedAttr = ' checked="checked"' ;
static private $_accordionElementTemplate = <<<_HERE_DOC
<h3>%s</h3>
<div>
  %s
</div>

_HERE_DOC;

static private $_initializer ;

private $_result , $_items , $_selectedId ;

static private function getInitializer ( )
{
  if ( self::$_initializer == NULL )
  {
    self::$_initializer = new KhgRequest( KhgRequestType::ItemList , NULL ) ;
  }

  return self::$_initializer ;
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

public function getRequest ( )
{
  return self::getInitializer() ;
}

public function processResult( $pResult )
{
  $this->_result = $pResult ;
  $this->_items = $this->_result->getItems() ;
  $this->_selectedId = $this->_result->getSelectedId() ;
}

public function printItemList ( )
{
  $items = $this->_result->getItems() ;

  $itemsText = '' ;

  foreach ( $items as $currentItem )
  {
    $itemsText = $itemsText . sprintf(
      self::$_accordionElementTemplate ,
      $currentItem->getName() ,
      $currentItem->getDescription()
    ) ;
  }

  echo self::_addIndentation( $itemsText , 4 ) ;
}

public function printSelectedItemName ( )
{
  echo $this->_items[ $this->_selectedId ]->getName() ;
}

public function printSelectedItemCode ( )
{
  echo $this->_items[ $this->_selectedId ]->getItemCode() ;
}

public function printSelectedItemStockCount ( )
{
  echo $this->_items[ $this->_selectedId ]->getStockCount() ;
}

public function printSelectedItemManufacturerName ( )
{
  echo $this->_items[ $this->_selectedId ]->getManufacturer()->getName() ;
}

public function printItemDiscontinuedCheckedAttr ( )
{
  if ( $this->_items[ $this->_selectedId ]->isDiscontinued())
  {
    echo self::$_checkedAttr ;
  }
}

}

?>
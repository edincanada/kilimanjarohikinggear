var itemData , manufacturerData ;

( function ( )
{

var DIV_ITEMS_ID         = '#divItems' ,
    DIV_NAME_ID          = '#divName' ,
    DIV_ITEM_CODE_ID     = '#divItemCode' ,
    DIV_STOCK_COUNT_ID   = '#divStockCount' ,
    DIV_DISCONTINUED_ID  = '#divDiscontinued' ,
    DIV_MANUFACTURER_ID  = '#divManufacturer' ,
    ANC_NAME_ID          = '#ancName' ,
    SPAN_ITEM_CODE_ID    = '#spanItemCode' ,
    SPAN_STOCK_COUNT_ID  = '#spanStockCount' ,
    CHB_DISCONTINUED_ID  = '#chbDiscontinued' ,
    ANC_MANUFACTURER_ID = '#ancManufacturer' ,
    DIV_PICTURE_ID       = '#divPicture' ,
    SLT_SEE_THE_CODE_ID  = '#sltSeeTheCode' ;

var MANUFACTURER_URL = 'manufacturers.html' ,
    ITEM_EDIT_URL    = 'itemedit.php' ;

var divItems = null ,
    ancName = null ,
    spanItemCode = null ,
    spanStockCount = null ,
    chbDiscontinued = null ,
    ancManufacturer = null ,
    divName = null ,
    divItemCode = null ,
    divStockCount = null ,
    divDiscontinued = null ,
    divManufacturer = null ,
    divPicture = null ,
    sltSeeTheCode = null ;


var sourceCodeWindow ;

var setItemInfo = function ( pItem )
{
  changeValue( divName , ancName , pItem.name ) ;
  ancName.attr( 'href' , ITEM_EDIT_URL + '?itemid=' + pItem.id ) ;
  ancName.attr( 'title' , 'Edit item' ) ;

  changeValue( divItemCode , spanItemCode , pItem.itemCode ) ;
  changeValue( divStockCount , spanStockCount , pItem.stockCount ) ;

  changeValue( divManufacturer , ancManufacturer , pItem.manufacturer.name ) ;
  ancManufacturer.attr( 'href' , MANUFACTURER_URL + '?id=' + pItem.manufacturer.id ) ;
  ancManufacturer.attr( 'title' , 'Manufacturer info' ) ;


  divPicture.hide({
    effect : FOLD_EFFECT_STR ,
    duration : 500 ,
    complete : function ( )
    {
      divPicture.empty() ;
      divPicture.append( pItem.image ) ;
      divPicture.show( FOLD ) ;
    }
  }) ;

  divDiscontinued.hide({
    effect : FOLD_EFFECT_STR ,
    duration : 500 ,
    complete : function ( )
    {
      chbDiscontinued.attr( 'checked' , pItem.isDiscontinued ) ;
      divDiscontinued.show( FOLD ) ;
    }
  }) ;
}

var main = function ( )
{
  setDataReferences( itemData , manufacturerData ) ;

  divItems = $( DIV_ITEMS_ID ) ;
  divName = $( DIV_NAME_ID ) ;
  divItemCode = $( DIV_ITEM_CODE_ID ) ;
  divStockCount = $( DIV_STOCK_COUNT_ID ) ;
  divDiscontinued = $( DIV_DISCONTINUED_ID ) ;
  divManufacturer = $( DIV_MANUFACTURER_ID ) ;
  ancName = $( ANC_NAME_ID ) ;
  spanItemCode = $( SPAN_ITEM_CODE_ID ) ;
  spanStockCount = $( SPAN_STOCK_COUNT_ID ) ;
  chbDiscontinued = $( CHB_DISCONTINUED_ID ) ;
  ancManufacturer = $( ANC_MANUFACTURER_ID ) ;
  divPicture = $( DIV_PICTURE_ID ) ;
  sltSeeTheCode = $( SLT_SEE_THE_CODE_ID ) ;

  var ii , itemIds = itemData.ids , items = itemData.items , itemCount = itemIds.length ,
      accordionItem = null ;

  var activeId = $.url().param( 'id' ) || 1 ;
  var activePanelIndex = itemIds.indexOf( activeId + '' ) ;

  for ( ii = 0 ; ii < itemCount ; ii ++ )
  {
    accordionItem = createAccordionItem( items[ itemIds[ ii ]]) ;
    divItems.append( accordionItem.titleElement ) ;
    divItems.append( accordionItem.contentElement ) ;
  }

  divItems.accordion({ active: activePanelIndex , heightStyle : 'content' }).on(
    'accordionactivate' ,
    function ( paramEvent , paramUI )
    {
      var selectedItem = items[ itemIds[ divItems.accordion( 'option' , 'active' )]] ;
      setItemInfo( selectedItem ) ;
    }
  ) ;

  setItemInfo( items[ itemIds[ activePanelIndex ]]) ;

  sltSeeTheCode.on(
    'change' ,
    function ( )
    {
      var selectedChild = sltSeeTheCode.children('option:selected') ;
      var selectedIndex = selectedChild.index() ;

      if ( selectedIndex > 0 )
      {
        if ( sourceCodeWindow == null || sourceCodeWindow.location == null )
        {
          sourceCodeWindow =
            window.open(
            selectedChild.val() ,
            'SourceCode'
          ) ;
        }
        else
        {
          sourceCodeWindow.location.href = selectedChild.val() ;
          sourceCodeWindow.focus() ;
        }
      }
    }
  ) ;
} ;

$( document ).one( 'ready' , main ) ;

} )() ;
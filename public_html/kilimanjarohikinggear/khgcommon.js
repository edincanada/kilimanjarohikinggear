var changeValue =
    setDataReferences =
    createAccordionItem = function ( ) { } ;

var FOLD_EFFECT_STR = 'fold' ;
var FOLD = {
  effect : FOLD_EFFECT_STR ,
  duration : 500
} ;

( function ( )
{

if ( ! ( 'indexOf' in Array.prototype ))
{
  Array.prototype.indexOf = function( pFind , pIndex /*opt*/)
  {
    if ( typeof( pIndex ) == 'undefined' )
    {
      pIndex = 0 ;
    }

    if ( pIndex < 0 )
    {
      pIndex += this.length ;

      if ( pIndex < 0 )
      {
        pIndex = 0 ;
      }
    }

    var ii , arrayLength = this.length , found = false , retValue = -1 ;

    for ( ii = pIndex ; ii < arrayLength && !found ; ii++ )
    {
      if ( ii in this && this[ ii ] === pFind )
      {
        found = true ;
        retValue = ii ;
      }
    }

    return retValue ;
  } ;
}

changeValue = function ( pElementToHide , pElementText , pNewText )
{
  pElementToHide.hide({
    effect : FOLD_EFFECT_STR ,
    duration : 500 ,
    complete : function ( )
    {
      pElementText.text( pNewText ) ;
      pElementToHide.show( FOLD ) ;
    }
  }) ;
}

var H3_TAG  = '<h3 />' ,
    DIV_TAG = '<div />' ,
    IMG_TAG = '<img />' ;

setDataReferences = function ( pItemData , pManufacturerData )
{
  var ii , itemIds = pItemData.ids , itemCount = itemIds.length , items = pItemData.items ,
      manufacturers = pManufacturerData.manufacturers , itemId , item , manufacturerId ,
      manufacturer ;

  for ( ii = 0 ; ii < itemCount ; ii ++ )
  {
    itemId = itemIds[ ii ] ;
    item = items[ itemId ] ;

    manufacturerId = item.manufacturerId ;
    manufacturer = manufacturers[ manufacturerId ] ;

    item.manufacturer = manufacturer ;

    item.image = $( IMG_TAG , {
      'src' : item.imageUrl ,
      'class' : 'cssImgPicture'
    }) ;

    if ( ! manufacturer.hasOwnProperty( 'items' ))
    {
      manufacturer.items = [] ;
    }

    if ( manufacturer.items.indexOf( item ) < 0 )
    {
      manufacturer.items.push( item ) ;
    }
  }
} ;

createAccordionItem = function ( pObject )
{
  return {
    titleElement : $( H3_TAG , { text : pObject.name }) ,
    contentElement : $( DIV_TAG , { text : pObject.description })
  } ;
} ;

})() ;
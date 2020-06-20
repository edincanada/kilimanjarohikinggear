var itemData , manufacturerData ;

( function ( )
{

var ITEM_INFO_URL = 'index.html' ;
var A_TAG = '<a />' ,
    LI_TAG = '<li />' ;

var sourceCodeWindow ;
var MANUFACTURER_EDIT_URL = 'manufactureredit.php' ;

var populateItemList = function ( pUlElement , pManufacturer )
{
  var ii = 0, itemLink , listItem , itemCount = 0 ;

  if ( typeof( pManufacturer.items ) !== 'undefined' )
  {
    itemCount = pManufacturer.items.length ;
  }

  pUlElement.empty() ;
  for ( ii = 0 ; ii < itemCount ; ii ++ )
  {
    itemLink = $(
      A_TAG , {
      'href'  : ITEM_INFO_URL + '?id=' + pManufacturer.items[ ii ].id ,
      'alt'   : 'Item info' ,
      'title' : 'Item info' ,
      'text'  : pManufacturer.items[ ii ].name
    }) ;

    listItem = $( LI_TAG ).addClass( 'classname' ) ;
    listItem.append( itemLink ) ;

    pUlElement.append( listItem ) ;
  }

  pUlElement.children('li:first-child').addClass( 'cssLiFirst' ) ;
  pUlElement.children('li:last-child').addClass( 'cssLiLast' ) ;
} ;

var setManufacturerInfo = function ( pManufacturer )
{
  changeValue( divName , ancName , pManufacturer.name ) ;
  ancName.attr( 'href' , MANUFACTURER_EDIT_URL + '?manufacturerid=' + pManufacturer.id ) ;
  ancName.attr( 'title' , 'Edit item' ) ;
  changeValue( divAddress , divInnerAddress , pManufacturer.address ) ;
  changeValue( divPhoneNumber , spanPhoneNumber , pManufacturer.phoneNumber ) ;
  changeValue( divEmail , spanEmail , pManufacturer.email ) ;

  ulItemList.hide({
    effect : FOLD_EFFECT_STR ,
    duration : 500 ,
    complete : function ( )
    {
      populateItemList( ulItemList , pManufacturer ) ;
      ulItemList.show( FOLD ) ;
    }
  }) ;
} ;

var DIV_MANUFACTURERS_ID = '#divManufacturers' ,
    DIV_NAME_ID          = '#divName' ,
    DIV_ADDRESS_ID       = '#divAddress' ,
    DIV_INNER_ADDRESS_ID = '#divInnerAddress' ,
    DIV_PHONE_NUMBER_ID  = '#divPhoneNumber' ,
    DIV_EMAIL_ID         = '#divEmail' ,
    DIV_ITEM_LIST_ID     = '#divItemList' ,
    ANC_NAME_ID          = '#ancName' ,
    SPAN_PHONE_NUMBER_ID = '#spanPhoneNumber' ,
    SPAN_EMAIL_ID        = '#spanEmail' ,
    UL_ITEM_LIST_ID      = '#ulItemList' ,
    SLT_SEE_THE_CODE_ID  = '#sltSeeTheCode' ;

var divManufacturers = null ,
    divName = null ,
    divAddress = null ,
    divInnerAddress = null ,
    divPhoneNumber = null ,
    divEmail = null ,
    divItemList = null ,
    ancName = null ,
    spanPhoneNumber = null ,
    spanEmail = null ,
    ulItemList = null ,
    sltSeeTheCode = null ;

var main = function ( )
{
  setDataReferences( itemData , manufacturerData ) ;
  divManufacturers = $( DIV_MANUFACTURERS_ID ) ;
  divName = $( DIV_NAME_ID ) ,
  divAddress = $( DIV_ADDRESS_ID ) ,
  divInnerAddress = $( DIV_INNER_ADDRESS_ID ) ,
  divPhoneNumber = $( DIV_PHONE_NUMBER_ID ) ,
  divEmail = $( DIV_EMAIL_ID ) ,
  divItemList = $( DIV_ITEM_LIST_ID ) ,
  ancName = $( ANC_NAME_ID ) ,
  spanPhoneNumber = $( SPAN_PHONE_NUMBER_ID ) ,
  spanEmail = $( SPAN_EMAIL_ID ) ;
  ulItemList = $( UL_ITEM_LIST_ID ) ;
  sltSeeTheCode = $( SLT_SEE_THE_CODE_ID ) ;

  var ii , manufacturers = manufacturerData.manufacturers ,
      manufacturerIds = manufacturerData.ids , manufacturerCount = manufacturerIds.length ,
      accordionElement = null ;

  for ( ii = 0 ; ii < manufacturerCount ; ii ++ )
  {
    accordionElement = createAccordionItem( manufacturers[ manufacturerIds[ ii ]]) ;

    divManufacturers.append( accordionElement.titleElement ) ;
    divManufacturers.append( accordionElement.contentElement ) ;
  }

  var activeId = $.url().param( 'id' ) || 1 ;
  var activePanelIndex = manufacturerIds.indexOf( activeId + '' ) ;

  divManufacturers.accordion({ active: activePanelIndex , heightStyle : 'content' }).on(
    'accordionactivate' ,
    function ( paramEvent , paramUI )
    {
      var selectedManufacturer =
        manufacturers[ manufacturerIds[ divManufacturers.accordion( 'option' , 'active' )]] ;

      setManufacturerInfo( selectedManufacturer ) ;
    }
  ) ;

  setManufacturerInfo( manufacturers[ manufacturerIds[ activePanelIndex ]]) ;

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
          sourceCodeWindow.location.href =
            selectedChild.val() ;

          sourceCodeWindow.focus() ;
        }
      }
    }
  ) ;
} ;

$( document ).one( 'ready' , main ) ;

})() ;

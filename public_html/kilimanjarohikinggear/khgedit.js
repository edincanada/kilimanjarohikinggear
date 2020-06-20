var itemData , manufacturerData ;

( function ( )
{

var TXF_NAME_ID = '#txfName' ,
    TXF_ITEM_CODE_ID = '#txfItemCode' ,
    NMF_STOCK_COUNT_ID = '#nmfStockCount' ,
    CHB_EDIT_DISCONTINUED_ID = '#chbEditDiscontinued' ,
    TXA_DESCRIPTION_ID = '#txaDescription' ,
    UL_MANUFACTURERES_ID = '#ulManufacturers' ,
    HDN_MANUFACTURER_ID = '#hdnManufacturer' ;
    FORM_KHG_ID = '#formKilimanjaroHikingGear' ,
    UL_ERROR_LIST_ID = '#ulErrorList' ;
    DIV_SUBMIT_ID = '#divSubmit' ,
    ANC_CANCEL_ID = '#ancCancel' ,
    SLT_SEE_THE_CODE_ID  = '#sltSeeTheCode' ;

var txfName = null ,
    txfItemCode = null ,
    nmfStockCount = null ,
    chbEditDiscontinued = null ,
    txaDescription = null ,
    ulManufacturers = null ,
    hdnManufacturer = null ,
    formKilimanjaroHikingGear = null ,
    ulErrorList = null ,
    divSubmit = null ,
    ancCancel = null ,
    sltSeeTheCode = null ;

var sourceCodeWindow ;

var setManufacturerList = function ( pManufacturerData , pManufacturerSelected )
{
  var manufacturers = pManufacturerData.manufacturers ,
      manufacturerIds = pManufacturerData.ids ,
      manufacturerCount = manufacturerIds.length , ii , currentId , currentManufacturer ,
      newLi ;

  for ( ii = 0 ; ii < manufacturerCount ; ii++ )
  {
    currentId = manufacturerIds[ ii ] ;
    currentManufacturer = manufacturers[ currentId ] ;

    newLi = $( '<li />' , {
      'text' : manufacturers[ currentId ].name
    }) ;

    if ( currentManufacturer == pManufacturerSelected )
    {
      newLi.addClass( 'cssLiSelected' ) ;
    }

    ulManufacturers.append( newLi ) ;
  }
} ;

var DropDownList = function ( pUlElementId , pHiddenElementId , pIds )
{
  var _selected = true , _ulElementId = pUlElementId ,
      _hiddenElementId = pHiddenElementId , _selectedIndex ,
      _manufacturerIds = pIds ;

  this.selectedIndex = function ( )
  {
    return _selectedIndex ;
  } ;

  var hideUnselected = function ( pSelected )
  {
    $( _ulElementId + ' li' ).addClass( 'cssLiHidden' ) ;
    $( _ulElementId + ' li' ).removeClass( 'cssLiSelected' ) ;

    $( pSelected ).addClass( 'cssLiSelected' ) ;
    $( pSelected ).addClass( 'cssLiSelectedAlone' ) ;
    $( pSelected ).removeClass( 'cssLiHidden' ) ;
  } ;

  $( _ulElementId + ' li' ).on(
    'click' ,
    function ( )
    {
      _selected = !_selected ;

      if ( _selected )
      {
        hideUnselected( this ) ;
        _selectedIndex = $( this ).index() ;
        $( _hiddenElementId ).val( _manufacturerIds[ _selectedIndex ]) ;
      }
      else
      {
        $( _ulElementId + ' li' ).removeClass( 'cssLiHidden' ) ;
        $( _ulElementId + ' li' ).removeClass( 'cssLiSelectedAlone' ) ;
      }
    }
  ) ;

  if ( _selected )
  {
    hideUnselected( $( _ulElementId + ' li.cssLiSelected' )) ;
    _selectedIndex = $( _ulElementId + ' li.cssLiSelected' ).index() ;
  }
} ;

var main = function ( )
{
  setDataReferences( itemData , manufacturerData ) ;

  txfName = $( TXF_NAME_ID ) ;
  txfItemCode = $( TXF_ITEM_CODE_ID ) ;
  nmfStockCount = $( NMF_STOCK_COUNT_ID ) ;
  chbEditDiscontinued = $( CHB_EDIT_DISCONTINUED_ID ) ;
  txaDescription = $( TXA_DESCRIPTION_ID ) ;
  ulManufacturers = $( UL_MANUFACTURERES_ID ) ;
  hdnManufacturer = $( HDN_MANUFACTURER_ID ) ;
  formKilimanjaroHikingGear = $( FORM_KHG_ID ) ;
  ulErrorList = $( UL_ERROR_LIST_ID ) ;
  divSubmit = $( DIV_SUBMIT_ID ) ;
  ancCancel = $( ANC_CANCEL_ID ) ;
  sltSeeTheCode = $( SLT_SEE_THE_CODE_ID ) ;

  var itemId = $.url().param( 'itemid' ) || 1 ;
  var errorFlag = ( ulErrorList.length > 0 ) ;
  var cancelFlag = false ;

  ancCancel.on(
  'click' ,
    function ( )
    {
      cancelFlag = true ;
    }
  ) ;

  formKilimanjaroHikingGear.attr(
    'action' ,
    formKilimanjaroHikingGear.attr( 'action' ) + '?itemid=' + itemId
  ) ;

  var selectedItem = itemData.items[ itemId ] ;
  var selectedManufacturer ;

  ancCancel.attr(
    'href' ,
    ancCancel.attr( 'href' )  + '?id=' + selectedItem.id
  ) ;

  if ( ! errorFlag )
  {
    txfName.val( selectedItem.name ) ;
    txfItemCode.val( selectedItem.itemCode );
    nmfStockCount.val( selectedItem.stockCount );
    chbEditDiscontinued.attr( 'checked' , selectedItem.isDiscontinued ) ;
    txaDescription.val( selectedItem.description ) ;
  }

  if ( hdnManufacturer.val() == null || hdnManufacturer.val() === '' )
  {
    selectedManufacturer = selectedItem.manufacturer ;
    hdnManufacturer.val( selectedManufacturer.id ) ;
  }
  else
  {
    selectedManufacturer = manufacturerData.manufacturers[ hdnManufacturer.val() ] ;
  }

  setManufacturerList( manufacturerData , selectedManufacturer ) ;

  $( UL_MANUFACTURERES_ID + ' li:first-child').addClass( 'cssLiFirst' ) ;
  $( UL_MANUFACTURERES_ID + ' li:last-child').addClass( 'cssLiLast' ) ;

  var list = new DropDownList( UL_MANUFACTURERES_ID , HDN_MANUFACTURER_ID , manufacturerData.ids ) ;

  nmfStockCount.on(
    'keypress' ,
    function ( pEvent )
    {
      var whichKey = pEvent.which ;
      if (( whichKey < 1 ) || ( whichKey == 8 ))
      {
        return true ;
      }
      else if ( $(this).val().length < 6 )
      {
        // 8 == backspace
        //between 48 and 57 numbers.
        return ( whichKey >= 48 && whichKey <= 57 ) ;
      }
      else
      {
        return false ;
      }
    }
  ) ;

  nmfStockCount.on(
    'focus' ,
    function ( )
    {
      this.previousValue = $(this).val() ;
    }
  ) ;

  nmfStockCount.on(
    'change' ,
    function ( )
    {
      if ( ! /^[0-9]{1,6}$/.test( $(this).val()))
      {
        $(this).val( this.previousValue ) ;
      }
    }
  ) ;

  divSubmit.on(
    'click' ,
    function ( )
    {
      if ( ! cancelFlag )
      {
        formKilimanjaroHikingGear.submit() ;
      }
    }
  ) ;

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

$( document  ).one( 'ready' , main ) ;

})() ;
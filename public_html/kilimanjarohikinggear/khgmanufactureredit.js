var itemData , manufacturerData ;

( function ( )
{

var UL_ERROR_LIST_ID = '#ulErrorList' ,
    TXF_NAME_ID = '#txfName' ,
    NMF_PHONE_NUMBER_ID = '#nmfPhoneNumber' ,
    TXF_EMAIL_ID = '#txfEmail' ,
    TXA_DESCRIPTION_ID = '#txaDescription' ,
    TXA_ADDRESS_ID = '#txaAddress' ,
    DIV_SUBMIT_ID = '#divSubmit' ,
    ANC_CANCEL_ID = '#ancCancel' ,
    SLT_ITEM_LIST_ID = '#sltItemList' ,
    HDN_ITEM_LIST_ID = '#hdnItemList' ,
    FORM_KHG_ID = '#formKilimanjaroHikingGear' ,
    SLT_SEE_THE_CODE_ID = '#sltSeeTheCode' ;

var ulErrorList = null ,
    txfName = null ,
    nmfPhoneNumber = null ,
    txfEmail = null ,
    txaDescription = null ,
    txaAddress = null ,
    divSubmit = null ,
    ancCancel = null ,
    sltItemList = null ,
    hdnItemList = null ,
    formKilimanjaroHikingGear = null ,
    sltSeeTheCode = null ;

var _onChangeSelection = function ( pSelect , pFunction )
{
  pSelect.on(
    'change' ,
    function ( )
    {
      var children = pSelect.children( 'option' ) ;
      var jOption , ii ;
      
      for ( ii = 0 ; ii < children.length ; ii ++ )
      {
        jOption = $( children[ ii ]) ;
        pFunction( jOption ) ;
      }
    }
  ) ;
} ;

var _OPTION_TAG = '<option />' ,
    _INP_TAG = '<input />' ;

var _setupItemSelection = function ( pSelect , pItemData , pSelectedIds )
{
  var ids = pItemData.ids , itemCount = pItemData.ids.length , newOption , ii ,
      items = pItemData.items ;
  pSelect.empty() ;
  
  //loop add items, and mark as selected those that are.
  pSelect.attr( 'size' , itemCount ) ;
  for ( ii = 0 ; ii < itemCount ; ii ++ )
  {
    
    newOption = $(
      _OPTION_TAG , {
        text : items[ ids[ ii ]].name ,
        value : ids[ ii ]
      }
    ) ;
    
    if ( pSelectedIds.indexOf( items[ ids[ ii ]].id + '' ) > -1 )
    {
      newOption.attr( 'selected' , 'selected' ) ;
    }
    
    pSelect.append( newOption ) ;
  }
} ;

var _itemListIdToHiddenArray = function ( pForm , itemIdList )
{
  var hiddenItem , ii ;
  for ( ii = 0 ; ii < itemIdList.length ; ii ++ )
  {
    hiddenItem = $(
     _INP_TAG , {
       value : itemIdList[ ii ] ,
       type : 'hidden' ,
       name : 'hdnItemList[]'
     }
    ) ;
      
    pForm.append( hiddenItem ) ;
  }
} ;

var main = function ( )
{
  setDataReferences( itemData , manufacturerData ) ;

  ulErrorList = $( UL_ERROR_LIST_ID ) ;
  txfName = $( TXF_NAME_ID ) ;
  nmfPhoneNumber = $( NMF_PHONE_NUMBER_ID ) ;
  txfEmail = $( TXF_EMAIL_ID ) ;
  txaDescription = $( TXA_DESCRIPTION_ID ) ;
  txaAddress = $( TXA_ADDRESS_ID ) ;
  divSubmit = $( DIV_SUBMIT_ID ) ;
  ancCancel = $( ANC_CANCEL_ID ) ;
  sltItemList = $( SLT_ITEM_LIST_ID ) ;
  hdnItemList = $( HDN_ITEM_LIST_ID ) ;
  formKilimanjaroHikingGear = $( FORM_KHG_ID ) ;
  sltSeeTheCode = $( SLT_SEE_THE_CODE_ID ) ;

  var selectedId = $.url().param( 'manufacturerid' ) || 1 ;
  var errorFlag = ( ulErrorList.length > 0 ) ;
  var cancelFlag = false ;

  var manufacturers = manufacturerData.manufacturers , items = itemData.items ,
      itemCount = itemData.ids.length ,
      selectedManufacturer = manufacturers[ selectedId ] ,
      selectedItemIds = null , ii ;
  
  ancCancel.attr(
    'href' ,
    ancCancel.attr( 'href' )  + '?id=' + selectedId
  ) ;
  
  //Make sure cancel and submit are not clicked quickly in that order.
  ancCancel.on(
  'click' ,
    function ( )
    {
      cancelFlag = true ;
    }
  ) ;
    
  formKilimanjaroHikingGear.attr(
    'action' ,
    formKilimanjaroHikingGear.attr( 'action' ) + '?manufacturerid=' + selectedId
  ) ;
    
  divSubmit.on(
    'click' ,
    function ( )
    {
      if ( ! cancelFlag )
      {
        _itemListIdToHiddenArray( formKilimanjaroHikingGear , selectedItemIds ) ;
        formKilimanjaroHikingGear.submit() ;
      }
    }
  ) ;
  
  sltItemList.on(
    'change' ,
    function ( )
    {
      selectedItemIds.splice( 0 , selectedItemIds.length ) ;
      $( "[name='hdnItemList[]']" ).remove() ;
    }
  ) ;
  
  _onChangeSelection(
    sltItemList ,
    function ( pOption )
    {
      var optionValue = pOption.val() ;
      var indexOfValue = selectedItemIds.indexOf( optionValue ) ;
      if ( pOption.is( ':selected' ))
      {
        if ( indexOfValue < 0 )
        {
          selectedItemIds.push( optionValue ) ;
        }
      }
      else if ( indexOfValue > -1 )
      {
        selectedItemIds.splice( indexOfValue , 1 ) ;
      }
    }
  ) ;
  
  if ( ! errorFlag )
  {
    txfName.val( selectedManufacturer.name ) ;
    nmfPhoneNumber.val( selectedManufacturer.phoneNumber ) ;
    txfEmail.val( selectedManufacturer.email ) ;
    txaDescription.val( selectedManufacturer.description ) ;
    txaAddress.val( selectedManufacturer.address ) ;
  }
  
  var itemListJson = hdnItemList.val() ;

  if ( itemListJson == null || itemListJson == '' )
  {
    selectedItemIds = [] ;
    for ( ii = 0 ; ii < selectedManufacturer.items.length ; ii ++ )
    {
      selectedItemIds.push( selectedManufacturer.items[ ii ].id + '' ) ;
    }
  }
  else
  {
    selectedItemIds = JSON.parse( itemListJson ) ;
  }
  
  
  hdnItemList.remove() ;
  hdnItemList = null ;
  
  _setupItemSelection( sltItemList , itemData , selectedItemIds ) ;
  
  
  nmfPhoneNumber.on(
    'keypress' ,
    function ( pEvent )
    {
      var whichKey = pEvent.which ;
      if (( whichKey < 1 ) || ( whichKey == 8 ))
      {
        return true ;
      }
      else if ( this.value.length < 12 )
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
  );
  
  nmfPhoneNumber.on(
    'focus' ,
    function ( )
    {
      this.previousValue = $(this).val() ;
    }
  ) ;

  nmfPhoneNumber.on(
    'change' ,
    function ( )
    {
      if ( ! /^[0-9]{1,12}$/.test( $(this).val()))
      {
        $(this).val( this.previousValue ) ;
      }
    }
  ) ;
    
  var sourceCodeWindow = null ;
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

})() ;

<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

$view = new KhgManufacturerEditView() ;
$request = $view->getRequest() ;

$controller = null ;
$result = NULL ;

if ( $request != NULL )
{
  $controller = new KhgController( KhgEntityManager::getInstance()) ;
  $controller->setRequest( $request ) ;
  $controller->processRequest() ;
  $result = $controller->getResult() ;
}

$view->processResult( $result ) ;

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<!--
 Kilimanjaro Hiking Gear Inventory System
 Version 0.0.0, date/last/modified
 Ed Arvelaez

 Kilimanjaro Hiking Gear is an inventory website simulator that features seemless
 server-side request-response interaction. It features the use of Doctrine as its
 object-relational management system, and jquery ui for client side visual elements
-->

<meta name="description" content="Kilimanjaro Hiking Gear Inventory System" />
<meta name="keywords" content="doctrine,mvc,jquery,ui,jsonp" />
<meta name="author" content="Ed Arvelaez" />

<meta http-equiv="content-type"  content="text/html;charset=utf-8" />
<meta charset="utf-8" />

<link type="text/css" rel="stylesheet" media="screen" href="/common/css/reset.css" />
<link type="text/css" rel="stylesheet" media="screen" href="/common/jquery-ui/themes/base/jquery-ui.min.css" />
<link type="text/css" rel="stylesheet" media="screen" href="style.css" />

<script type="text/javascript" src="/common/jquery/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="/common/jquery/purl.min.js"></script>

<script type="text/javascript" src="khgcommon.js"></script>
<script type="text/javascript" src="khgmanufactureredit.js"></script>
<script type="text/javascript" src="khgdata.php?get=items&amp;assign=itemData"></script>
<script type="text/javascript" src="khgdata.php?get=manufacturers&amp;assign=manufacturerData"></script>
<title>Kilimanjaro Hiking Gear</title>
</head>
<body id="bodyKilimanjaroHikingGear" class="cssBodyKilimanjaroHikingGear">
<form id="formKilimanjaroHikingGear" method="post" action="manufactureredit.php" enctype="application/x-www-form-urlencoded">
 <div id="divHeader" class="cssDivHeader">
  <span id="spanTitle" class="cssSpanTitle">Kilimanjaro Hiking Gear - Inventory System</span>
  <div id="divOptions" class="cssDivOptions">
   <a href="/apps/kilimanjarohikinggear/index.html" class="cssAncHeaderLink">Item List</a>
   <a href="/apps/kilimanjarohikinggear/manufacturers.html" class="cssAncHeaderLink">Manufacturer List</a>
   See the code:
   <select id="sltSeeTheCode">
     <option>Select a script</option>
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=KhgEntityManager_class_php.txt">Entity Manager</option>
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=KhgController_class_php.txt">Controller</option>
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=KhgManufacturerEditView_class_php.txt">Manufacturer Edit view</option>
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=manufactureredit_php.txt">Preprocessed markup</option>
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=khgmanufactureredit_js.txt">Client script</option>
   </select>
  </div>
 </div>
 <div class="cssDivContentBackground">
  <div class="cssManufacturerEditContent">
   <table class="tblManufacturerEdit">
   <?php $view->printErrorsFound() ; ?>
   <tr>
    <td>
     Items
    </td>
    <td>
     Ctrl + Click for multiple selection
     <div id="divItemList">
      <select id="sltItemList" multiple="multiple"></select>
     </div>
    </td> 
   </tr>
   <tr>
    <td>Name</td>
    <td><input id="txfName" class="cssInpField" name="txfName" type="text" size="35" value="<?php $view->printName() ; ?>" /></td>
   </tr>
   <tr>
    <td>Phone number</td>
    <td><input id="nmfPhoneNumber" class="cssInpField" name="nmfPhoneNumber" type="number" min="0" max="999999999999" size="12" maxlength="12" value="<?php $view->printPhoneNumber() ; ?>" /></td>
   </tr>
   <tr>
    <td>Email</td>
    <td><input id="txfEmail" class="cssInpField" name="txfEmail" type="text" size="35" value="<?php $view->printEmail() ; ?>" /></td>
   </tr>
   <tr>
    <td colspan="2">
     <div class="cssDivDescription">Description</div>
     <textarea id="txaDescription" name="txaDescription" class="cssTxaDescription" rows="6" cols="50"><?php $view->printDescription() ; ?></textarea>
    </td>
   </tr>
   <tr>
    <td colspan="2">
     <div class="cssDivAddress">Address</div>
     <textarea id="txaAddress" name="txaAddress" class="cssTxaAddress" rows="6" cols="50"><?php $view->printAddress() ; ?></textarea>
    </td>
   </tr>
  </table>
   <div id="divSubmit" class="cssFormButtom">Update</div>
   <div class="cssFormButtom"><a id="ancCancel" title="Go back" alt="Go back" href="manufacturers.html">Cancel</a></div>
  </div>
 </div>
 <input id="hdnItemList" name="hdnItemList" type="hidden" value="<?php $view->printItemListJSONString() ; ?>" />
</form>
</body>
</html>
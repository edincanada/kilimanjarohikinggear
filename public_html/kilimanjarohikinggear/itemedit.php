<?php

require_once ( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

$view = new KhgItemEditView() ;
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
<script type="text/javascript" src="khgedit.js"></script>
<script type="text/javascript" src="khgdata.php?get=items&amp;assign=itemData"></script>
<script type="text/javascript" src="khgdata.php?get=manufacturers&amp;assign=manufacturerData"></script>
<title>Kilimanjaro Hiking Gear</title>
</head>
<body id="bodyKilimanjaroHikingGear" class="cssBodyKilimanjaroHikingGear">
<form id="formKilimanjaroHikingGear" method="post" action="itemedit.php" enctype="application/x-www-form-urlencoded">
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
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=KhgItemEditView_class_php.txt">Item Edit view</option>
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=itemedit_php.txt">Preprocessed markup</option>
     <option value="http://<?php echo $HOST_NAME ; ?>/apps/codedisplay/codedisplay.php?project=kilimanjarohikinggear&amp;file=khgedit_js.txt">Client script</option>
   </select>
  </div>
 </div>
 <div class="cssItemEditContent">
  <table class="tblItemEdit">
   <?php $view->printErrorsFound() ; ?>
   <tr>
    <td>Name</td>
    <td><input id="txfName" class="cssInpField" name="txfName" type="text" size="35" value="<?php $view->printItemName() ; ?>" /></td>
   </tr>
   <tr>
    <td>Item Code</td>
    <td><input id="txfItemCode" class="cssInpField" name="txfItemCode" type="text" size="35" value="<?php $view->printItemCode() ; ?>" /></td>
   </tr>
   <tr>
    <td>Stock Count</td>
    <td><input id="nmfStockCount" class="cssInpField" name="nmfStockCount" type="number" min="0" max="999999" size="6" maxlength="6" value="<?php $view->printStockCount() ; ?>" /></td>
   </tr>
   <tr>
    <td>Discontinued</td>
    <td><input id="chbEditDiscontinued" name="chbEditDiscontinued" type="checkbox" value="DISCONTINUED" <?php $view->printDiscontinuedAttribute() ; ?>/><label id="lblDiscontinued" for="chbEditDiscontinued" ></label></td>
   </tr>
   <tr>
    <td colspan="2">
     <div class="cssDivDescription">Description</div>
     <textarea id="txaDescription" name="txaDescription" class="cssTxaDescription" rows="6" cols="50"><?php $view->printDescription() ; ?></textarea>
    </td>
   </tr>
   <tr>
    <td colspan="2">
     Manufacturer
     <ul id="ulManufacturers"></ul>
    </td>
   </tr>
  </table>
  <input id="hdnManufacturer" name="hdnManufacturer" type="hidden" value="<?php $view->printManufacturerId() ; ?>" />
  <div id="divSubmit" class="cssFormButtom">Update</div>
  <div class="cssFormButtom"><a id="ancCancel" title="Go back" alt="Go back" href="index.html">Cancel</a></div>
 </div>
</form>
</body>
</html>
<?php

require_once( $APPS_PHP_DIR . '/KilimanjaroHikingGear.namespace.php' ) ;

$manager = KhgEntityManager::getInstance() ;

$manufacturers = array() ;
$items = array() ;
$currentManufacturer = new KhgManufacturer(
  'Atlas Extremewear' ,
  ( 'Makes outdoor clothing for extreme weather conditions and sports. ' .
    'Featured by many extreme sport atheletes. Sponsors many extreme sport events.' ) ,
  '43 Tobermory Drive. Toronto, Ontario, Canada. M3N2R4' ,
  '2455813317' ,
  'contactus@atlasxwear.ca'
) ;

$currentItem = new KhgItem(
  'EhGloo Winter Jacket' ,
  'WJ05BLUE' ,
  ( 'This jacket is designed to prevent freezing by catching heat ' .
    'from the sun while keeping the body at a comfortable temperature below the point of ' .
    'transpiration. It is snow-proof and water-proof. It transform into a portable ' .
    'pillow for the winter.' ) ,
  20 ,
  false ,
  '/apps/kilimanjarohikinggear/image/jacket.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;

$currentItem = new KhgItem(
  'Raincoat PocketMaster' ,
  'SF119TENT' ,
  ( 'This waterproof raincoat has numerous pockets and can turn into a bag. The sleeves are ' .
    'removable, and its length is adjustable. Fresh and durable.') ,
  30 ,
  false ,
  '/apps/kilimanjarohikinggear/image/waterproof.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;
$manufacturers[] = $currentManufacturer ;

$currentManufacturer = new KhgManufacturer(
  'Nature House Ltd.' ,
  ( 'Makes tents, and outdoor shelters. Military supplier. Featured on tv several times. ' .
    'Offers manufacturer warranty on most o its products.' ) ,
  '5214 Gerrish Street. Halifax, Nova Scotia, Canada. B3K5K3' ,
  '4168266712' ,
  'inquiries@naturehouse.com'
) ;

$currentItem = new KhgItem(
  'Sky Fort' ,
  'SF119TENT' ,
  ( 'This instant tent is ready in literally a snap. Its fabric is washable and anti-odors. ' .
    'It can be set to opaque or see-thru depending on time and weather. It allows for great ' .
    'air circulation. It is extremely light and when folded, It\'s smaller than a lunch box.' ) ,
  25 ,
  false ,
  '/apps/kilimanjarohikinggear/image/tent.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;

$currentItem = new KhgItem(
  'Good Times Chair' ,
  '880GT' ,
  ( 'Durable chair with a popular design. Two pockets for beverages and other times. ' .
    'Hydrorepellent fabric and aluminium structure. It comes with a golf style shoulderd bag. ' .
    'It comes in different colours and designs.' ) ,
  90 ,
  false ,
  '/apps/kilimanjarohikinggear/image/chair.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;
$manufacturers[] = $currentManufacturer ;

$currentManufacturer = new KhgManufacturer(
  'Dominion Footwear.' ,
  ( 'Makes shoes for athletics and outdoor activities. Best extreme shoes seller in Europe. ' .
    'Company Head Quarters in Germany. Shoes manufactured in Canada.' ) ,
  '900 Turnpike Avenue. Winnipeg, Manitoba, Canada. T5RH2N' ,
  '9025291174' ,
  'canada@dominionfw.de'
) ;

$currentItem = new KhgItem(
  'Blue Panther Hiking Shoes' ,
  'BP10050' ,
  ( 'These hiking shoes are made with the technology and materials used in military vehicle ' .
    'tires and bulletproof vests. It offers 25% better grip than the closest competing brand. ' .
    'It is water resistant, and anti-bacterial.' ) ,
  40 ,
  false ,
  '/apps/kilimanjarohikinggear/image/shoes.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;

$currentItem = new KhgItem(
  'Alligator Climbing shoes' ,
  'CSXX2013' ,
  ( 'These shoes prevent transpiration and are designed for vertical rock climbing. ' .
    'The material provides grip even on slippery rocks, and also the proper bounce to ' .
    'aid with vertical pulling and downwards rappelling.') ,
  20 ,
  false ,
  '/apps/kilimanjarohikinggear/image/other_shoes.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;
$manufacturers[] = $currentManufacturer ;

$currentManufacturer = new KhgManufacturer(
  'Darryl\'s Equipment.' ,
  ( 'Importer of equipment for outdoor activities. Chairs, tables, containers, etc. ' .
    'Items are of very high quality. Darryl\'s equipment sponsors many community ' .
    'health and fitness events.' ) ,
  '84 Leich Street. North York, Ontario, Canada. M2J2C0' ,
  '9026785630' ,
  'darrylm@darryls.com'
) ;

$currentItem = new KhgItem(
  'Pathfinder trekking poles' ,
  '880GT' ,
  ( 'These hiking sticks have adjustable width, length, and flexibility. Weight can be ' .
    'added and removed. They come with a set of accessories that turn them into 12 different ' .
    ' useful camping and hiking tools such as emergency stretcher.' ) ,
  15 ,
  false ,
  '/apps/kilimanjarohikinggear/image/stick.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;

$currentItem = new KhgItem(
  'Electronic Christopher Compass' ,
  'COM10ELEC' ,
  ( 'This compass needs no batteries. It glows in the dark and has an emergency beeping beacon ' .
    ' if It\'s under water or underground for an extended period of time. It has a watch and ' .
    ' fuel-less lighter.' ) ,
  120 ,
  false ,
  '/apps/kilimanjarohikinggear/image/compass.JPG' ,
  $currentManufacturer
) ;

$currentManufacturer->getItems()->add( $currentItem ) ;
$items[] = $currentItem ;
$manufacturers[] = $currentManufacturer ;

foreach ( $manufacturers as $currentManufacturer )
{
  $manager->persist( $currentManufacturer ) ;
}

foreach ( $items as $currentItem )
{
  $manager->persist( $currentItem ) ;
}

$items = NULL ;
$manufacturers = NULL ;
$currentItem = NULL ;
$currentManufacturer = NULL ;

$manager->flush() ;
$manager = NULL ;

?>

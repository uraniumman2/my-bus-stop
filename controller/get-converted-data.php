<?php
    require('../model/Util.php');
    require('../model/ColorManager.php');
    require('../model/LegendManager.php');
    require('../model/BoundaryManager.php');
    require('../model/LayoutManager.php');

$aRequestData = json_decode(file_get_contents('php://input'), true);
// print_r($aRequestData);
$aBuses = json_decode($aRequestData['buses'], true);
$sCurCoord = $aRequestData['current_coord'];
$dOffsetX = RAINBOW_OFFSET_X;
$dOffsetY = RAINBOW_OFFSET_Y;
$sPolylines = '';
$oColorMngr = model\ColorManager::getInstance();
$oColorMngr->clearMatches();
$aAllBusRoutes = array();
$aAllBusStops = array();
$aBusCollection = array();
$aBusNumber = array();
foreach ($aBuses AS $sBusId) {
    $aBusInfo = model\Util::getBusInfo($sBusId, $sCurCoord);
    // $aFetchedData[] = array($sBus => $aBusInfo);
    $aRoute = $aBusInfo['route'];
    if (empty($aRoute))
        continue;
    $aBusNumber[] = $aBusInfo['bus_number'];
    $aBusCollection[] = $sBusId;
    $aAllBusRoutes[] = $aBusInfo['route'];
    $aAllBusStops = $aAllBusStops + $aBusInfo['stops'];

    // if successful assign color to bus id
    $oColorMngr->setColor($sBusId);

}
//var_dump($aAllBusStops);

foreach ($aAllBusRoutes as $i => &$aBusRoutesI) {
    foreach ($aBusRoutesI as &$sRouteI) {
        $sRouteI = (doubleval(explode(",", $sRouteI)[0]) + $dOffsetX*($i - (count($aAllBusRoutes)/2) + 1)) . "," . (doubleval(explode(",", $sRouteI)[1]) - $dOffsetY*($i - (count($aAllBusRoutes)/2)));
    }
}


foreach ($aBusCollection AS $i => $sBusId) {
    $sPolylines .= model\Util::getPolyline($aAllBusRoutes[count($aAllBusRoutes)-$i-1], $oColorMngr->getColor($sBusId),$aBusNumber[count($aBusNumber)-$i-1]);
}
//echo model\Util::getStartStopCircle($sCurCoord);
$sPolylines .= model\Util::getStartStopCircle($sCurCoord);


$oBoundaryMngr = \model\BoundaryManager::getInstance();
//    print_r($oBoundaryMngr->getBoundaries());
//    print_r($oBoundaryMngr->getCropBoundaries(200, 200));
model\LayoutManager::drawSVGLayout($aRequestData['caption'],$aRequestData['caption'], $sPolylines);
//model\Util::getSVGMap($sPolylines);
echo 'SUCCESS';
// echo json_encode($aFetchedData);
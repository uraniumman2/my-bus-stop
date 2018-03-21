<?php
    require('../model/Util.php');
    require('../model/ColorManager.php');
    require('../model/LegendManager.php');
    require('../model/BoundaryManager.php');

    $aRequestData = json_decode(file_get_contents('php://input'), true);
    // print_r($aRequestData);
    $aBuses = json_decode($aRequestData['buses'], true);
    $sCurCoord = $aRequestData['current_coord'];

    $sPolylines = '';
    $oColorMngr = model\ColorManager::getInstance();
    $oColorMngr->clearMatches();
    foreach($aBuses AS $sBus) {
        $aBusInfo = model\Util::getBusInfo($sBus, $sCurCoord);
        // $aFetchedData[] = array($sBus => $aBusInfo);
        $aRoute = $aBusInfo['route'];
        if(empty($aRoute))
            continue;
        // if successful assign color to bus id
        $oColorMngr->setColor($sBus);
        $sPolylines .= model\Util::getPolyline($aRoute, $oColorMngr->getColor($sBus));
    }
    // $sTemplate = model\Util::getSVGTemplate(); // testing
    // if($sTemplate) {
    //     $sTemplate .= $sPolylines;
    //     $sTemplate .= '</svg>';
    // }
//    echo $sPolylines;
    // TODO: Написать модель для сохранения svg файла который возвращает ссылку на объект с генерируемый по дате
    // $sTemplateLegend = model\LegendManager::getBackground('red', 'white');
    // $sTemplateLegend = model\LegendManager::drawBusNumber(51, 'white');
    // $sTemplateLegend = model\LegendManager::drawStopCaptions('Caption 1', 'Caption 2', 'white');
    // $sTemplateLegend = model\LegendManager::drawWorkingHours('7:00 - 22:30', '7:30 - 22:10', 'white');
    // $sTemplateLegend = model\LegendManager::drawInterval('7-15', 'white');
//     $sTemplateLegend = model\LegendManager::drawBusInfo('red', 'white', 51, 'Caption 1', 'Caption 2', '7:00 - 22:30', '7:30 - 22:10', '7-15');
//     $sTemplateLegend = model\LegendManager::drawLegend();
//     echo $sTemplateLegend;

    $oBoundaryMngr = \model\BoundaryManager::getInstance();
    print_r($oBoundaryMngr->getBoundaries());
    // echo json_encode($aFetchedData);
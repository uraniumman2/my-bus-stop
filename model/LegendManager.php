<?php
namespace model;

class LegendManager {
    const STROKE_WIDTH  = 2;
    const RECT_HEIGHT   = 150;
    const RECT_WIDTH    = 500;
    const FIRST_VERT_X  = 100;
    const SECOND_VERT_X = 350;

    static function drawBackground($sBackgroundColor, $sFontColor) {
        $iRectWidth   = self::RECT_WIDTH;
        $iRectHeight  = self::RECT_HEIGHT;
        $iStrokeWidth = self::STROKE_WIDTH;
        $iFirstVertX = self::FIRST_VERT_X;
        $iSecondVertX = self::SECOND_VERT_X;
        $iOffsetY = $iRectHeight / 2;

        // <!-- Background -->
        $sBgTemplate = "<polygon points=\"0,0 {$iRectWidth},0 {$iRectWidth},{$iRectHeight} 0,{$iRectHeight}\" style=\"fill:{$sBackgroundColor};stroke:{$sFontColor};stroke-width:{$iStrokeWidth}\" />\n";

        // <!-- Vertical lines -->
        $sBgTemplate .= "<line x1=\"{$iFirstVertX}\" y1=\"0\" x2=\"{$iFirstVertX}\" y2=\"{$iRectHeight}\" style=\"stroke:{$sFontColor};stroke-width:{$iStrokeWidth}\" />\n";
        $sBgTemplate .= "<line x1=\"{$iSecondVertX}\" y1=\"{$iOffsetY}\" x2=\"{$iSecondVertX}\" y2=\"{$iRectHeight}\" style=\"stroke:{$sFontColor};stroke-width:{$iStrokeWidth}\" />\n";
        
        // <!-- Horizontal line -->
        $sBgTemplate .= "<line x1=\"{$iFirstVertX}\" y1=\"{$iOffsetY}\" x2=\"{$iRectWidth}\" y2=\"{$iOffsetY}\" style=\"stroke:{$sFontColor};stroke-width:{$iStrokeWidth}\" />\n";
        return $sBgTemplate;
    }

    static function drawBusNumber($sBusNumber, $sFontColor) {
        $iFirstVertX = self::FIRST_VERT_X;
        $iOffsetX = $iFirstVertX / 2;
        $iOffsetY = 100;
        // <!-- Bus number -->
        $sNumberTemplate = "<text x=\"{$iOffsetX}\" y=\"{$iOffsetY}\" fill=\"{$sFontColor}\" font-size=\"72\" font-weight=\"700\" text-anchor=\"middle\">{$sBusNumber}</text>\n";
        return $sNumberTemplate;
    }

    static function drawStopCaptions($sStartCaption, $sEndCaption, $sFontColor) {
        $iFirstVertX = self::FIRST_VERT_X;
        $iOffsetX = self::FIRST_VERT_X + 10;
        $iOffsetY = 30;
        // <!-- Bus stop caption -->
        $sCaptionTemplate  = "<text x=\"{$iOffsetX}\" y=\"{$iOffsetY}\" fill=\"{$sFontColor}\" font-size=\"24\" font-weight=\"700\">\n";
        $sCaptionTemplate .= "<tspan>{$sStartCaption}</tspan>\n";
        $sCaptionTemplate .= "<tspan x=\"{$iOffsetX}\" dy=\"1.2em\">{$sEndCaption}</tspan>\n";
        $sCaptionTemplate .= "</text>\n";
        return $sCaptionTemplate;
    }
    
    static function drawWorkingHours($sFwrdTime, $sBwrdTime, $sFontColor) {
        $iOffsetX = self::FIRST_VERT_X + (self::SECOND_VERT_X - self::FIRST_VERT_X) / 2;
        $iOffsetY = self::FIRST_VERT_X + 5;
        // <!-- Working hours -->
        $sWorkingHoursTemplate  = "<text x=\"{$iOffsetX}\" y=\"{$iOffsetY}\" fill=\"{$sFontColor}\" font-size=\"24\" font-weight=\"700\" text-anchor=\"middle\">\n";
        // <!-- (forward) -->
        $sWorkingHoursTemplate .= "<tspan>{$sFwrdTime}</tspan>\n";
        // <!-- (backward) -->
        $sWorkingHoursTemplate .= "<tspan x=\"$iOffsetX\" dy=\"1.2em\">{$sBwrdTime}</tspan>\n";
        $sWorkingHoursTemplate .= "</text>\n";

        return $sWorkingHoursTemplate;
    }

    static function drawInterval($sTimeInterval, $sFontColor) {
        $iOffsetX = self::SECOND_VERT_X + (self::RECT_WIDTH - self::SECOND_VERT_X) / 2;
        $iOffsetY = 130;
        $sIntervalTemplate = "<text x=\"{$iOffsetX}\" y=\"{$iOffsetY}\" fill=\"{$sFontColor}\" font-size=\"48\" font-weight=\"700\" text-anchor=\"middle\">{$sTimeInterval}</text>";

        return $sIntervalTemplate;
    }

    static function drawBusInfoRect($sBackgroundColor, $sFontColor, $sBusNumber, $sStartCaption, $sEndCaption, $sFwrdTime, $sBwrdTime, $sTimeInterval, $sCoordY) {
        $iRectHeight = self::RECT_HEIGHT;
        $iRectWidth  = self::RECT_WIDTH;
        $sTemplate  = "<svg x=\"0\" y=\"{$sCoordY}\" width=\"{$iRectWidth}\" height=\"{$iRectHeight}\" viewBox=\"0 0 {$iRectWidth} {$iRectHeight}\">\n";
        $sTemplate .= self::drawBackground($sBackgroundColor, $sFontColor);
        $sTemplate .= self::drawBusNumber($sBusNumber, $sFontColor);
        $sTemplate .= self::drawStopCaptions($sStartCaption, $sEndCaption, $sFontColor);
        $sTemplate .= self::drawWorkingHours($sFwrdTime, $sBwrdTime, $sFontColor);
        $sTemplate .= self::drawInterval($sTimeInterval, $sFontColor);
        $sTemplate .= "\n</svg>\n";

        return $sTemplate;
    }

    public static function drawLegend() {
        $aBusColors = \model\ColorManager::getColorMatches();
        $iTotalCount = count($aBusColors);
        $iOffsetY = 5;
        $iCount = 0;
        $iRectHeight = self::RECT_HEIGHT * $iTotalCount + $iOffsetY * ($iTotalCount - 1);
        $iRectWidth  = self::RECT_WIDTH;
        $sLegendTemplate  = "<svg width=\"{$iRectWidth}\" height=\"{$iRectHeight}\" viewBox=\"0 0 {$iRectWidth} {$iRectHeight}\">\n";
        foreach($aBusColors AS $sBusId => $sBusColor) {
            $aBusLegendData = \model\Util::getBusLegendData($sBusId);
            if(!empty($aBusLegendData)) {
                $sLegendTemplate .= self::drawBusInfoRect($sBusColor, 'white', $aBusLegendData['bus_number'], $aBusLegendData['caption_start'], $aBusLegendData['caption_end'], $aBusLegendData['frwd'], $aBusLegendData['bkwd'], $aBusLegendData['time_interval'], $iCount * ($iRectHeight + $iOffsetY));
            }
        }
        $sLegendTemplate .= "</svg>";

        return $sLegendTemplate;
    }
}
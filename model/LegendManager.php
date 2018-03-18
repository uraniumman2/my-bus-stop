<?php
namespace model;

class LegendManager {
    const STROKE_WIDTH  = 2;
    const RECT_HEIGHT   = 150;
    const RECT_WIDTH    = 500;
    const FIRST_VERT_X  = 100;
    const SECOND_VERT_X = 350;
    
    public static function drawBackground($sBackgroundColor, $sFontColor) {
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

    public static function drawBusNumber($sBusNumber, $sFontColor) {
        $iFirstVertX = self::FIRST_VERT_X;
        $iOffsetX = $iFirstVertX / 2;
        $iOffsetY = 100;
        // <!-- Bus number -->
        $sNumberTemplate = "<text x=\"{$iOffsetX}\" y=\"{$iOffsetY}\" fill=\"{$sFontColor}\" font-size=\"72\" font-weight=\"700\" text-anchor=\"middle\">{$sBusNumber}</text>\n";
        return $sNumberTemplate;
    }

    public static function drawStopCaptions($sStartCaption, $sEndCaption, $sFontColor) {
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
    
    public static function drawWorkingHours($sFwrdTime, $sBwrdTime, $sFontColor) {
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

    public static function drawInterval($sTimeInterval, $sFontColor) {
        $iOffsetX = self::SECOND_VERT_X + (self::RECT_WIDTH - self::SECOND_VERT_X) / 2;
        $iOffsetY = 130;
        $sIntervalTemplate = "<text x=\"{$iOffsetX}\" y=\"{$iOffsetY}\" fill=\"{$sFontColor}\" font-size=\"48\" font-weight=\"700\" text-anchor=\"middle\">{$sTimeInterval}</text>";

        return $sIntervalTemplate;
    }

    public static function generateSVG($sTemplate) {
        
    }

    public static function drawBusInfo($sBackgroundColor, $sFontColor, $sBusNumber, $sStartCaption, $sEndCaption, $sFwrdTime, $sBwrdTime, $sTimeInterval) {
        $iRectHeight = self::RECT_HEIGHT;
        $iRectWidth  = self::RECT_WIDTH;
        $sTemplate  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sTemplate .= "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"{$iRectWidth}\" height=\"{$iRectHeight}\" viewBox=\"0 0 {$iRectWidth} {$iRectHeight}\" version=\"1.1\">\n";
        $sTemplate .= self::drawBackground($sBackgroundColor, $sFontColor);
        $sTemplate .= self::drawBusNumber($sBusNumber, $sFontColor);
        $sTemplate .= self::drawStopCaptions($sStartCaption, $sEndCaption, $sFontColor);
        $sTemplate .= self::drawWorkingHours($sFwrdTime, $sBwrdTime, $sFontColor);
        $sTemplate .= self::drawInterval($sTimeInterval, $sFontColor);
        $sTemplate .= "</svg>\n";

        return $sTemplate;
    }
}
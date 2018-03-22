<?php

namespace model;

class LayoutManager {

    const LAYOUT_HEIGHT  = 2523;
    const LAYOUT_WIDTH   = 3567;


    static function drawHeaderTitle($sKazCaption = 'АСТАНА ҚАЛАСЫНЫҢ ҚОҒАМДЫҚ КӨЛІКТЕРІНІҢ ЖҮРІС СЫЗБАСЫ', $sEngCaption = 'SCHEME OF ASTANA PUBLIC TRANSPORT ROUTES') {
        $sHeaderCaption = "<text x=\"3212\" y=\"100\" fill=\"white\" font-size=\"60\" font-weight=\"700\" font-family=\"".FONT_FAMILY."\" text-anchor=\"end\">";
        $sHeaderCaption .= "<tspan>{$sKazCaption}</tspan>";
        $sHeaderCaption .= "<tspan x=\"3212\" dy=\"1.2em\">{$sEngCaption}</tspan>";
        $sHeaderCaption .= "</text>";

        return $sHeaderCaption;
    }

    static function drawHeaderBusCaption($sKazCaption, $sEngCaption) {
        $sHeaderCaption  = "<text x=\"1783\" y=\"350\" fill=\"black\" font-size=\"84\" font-weight=\"700\" font-family=\"".FONT_FAMILY."\" text-anchor=\"middle\">";
        $sHeaderCaption .= "<tspan>{$sKazCaption}</tspan>";
        $sHeaderCaption .= "<tspan x=\"1783\" dy=\"1.2em\">{$sEngCaption}</tspan>";
        $sHeaderCaption .= "</text>";

        return $sHeaderCaption;
    }

    static function drawHeader($sBusCaptionKaz, $sBusCaptionEng) {

        // <!-- Background -->
        $sTemplate  = "<svg width=\"3567\" height=\"600\" viewBox=\"0 0 3567 600\" version=\"1.1\">\n";
        // <!-- Header Background color -->
        $sTemplate .= "<polygon points=\"300,25 3337,25 3337,200 300,200\" style=\"fill:#bf1e2e;stroke:white;stroke-width:1\" />";

        //<!-- Astana Gerb Logo -->
        $sAstanaGerb = file_get_contents('../src/assets/astana-gerb.svg');
        $sTemplate .= $sAstanaGerb . "\n";

        //<!-- Astra Header Title  -->
        $sTemplate .= self::drawHeaderTitle();

        //<!-- Astra Logo Placeholder -->
        $sAstraLogo = file_get_contents('../src/assets/astra-logo.svg');
        $sTemplate .= $sAstraLogo . "\n";

        //<!-- Bus Stop Caption -->
        $sTemplate .= self::drawHeaderBusCaption($sBusCaptionKaz, $sBusCaptionEng);

        //<!-- Expo Logo Placeholder -->
        $sExpoLogo = file_get_contents('../src/assets/expo-logo.svg');
        $sTemplate .= $sExpoLogo . "\n";

        //<!-- Compass Logo Placeholder -->
        $sCompass = file_get_contents( '../src/assets/compass.svg');
        $sTemplate .= $sCompass . "\n";

        // closing SVG tag
        $sTemplate .= "</svg>\n";
        return $sTemplate;
    }

    static function drawFooter() {
        $sFooterTemplate  = "<svg x=\"0\" y=\"2023\" width=\"3567\" height=\"400\" viewBox=\"0 0 3567 400\" version=\"1.1\">\n";
        $sFooterTemplate .= "<polygon points=\"0,230 3567,230 3567,400 0,400\" style=\"fill:#bf1e2e;stroke:white;stroke-width:1\" />\n";
        $sFooterTemplate .= "<text x=\"100\" y=\"80\" fill=\"black\" font-size=\"28\" font-weight=\"700\" font-family=\"".FONT_FAMILY."\" text-anchor=\"start\"><tspan>6:30 - 22:30 - алғашқы жəне соңғы автобустың өту уақыты</tspan><tspan x=\"205\" dy=\"1.2em\">время первого и последнего автобуса на маршруте</tspan><tspan x=\"100\" dy=\"1.2em\">*</tspan><tspan x=\"195\">- демалыс күндерінің кестесі/расписание в выходные дни</tspan></text>";
        $sFooterTemplate .= "<text x=\"3547\" y=\"200\" fill=\"black\" font-size=\"48\" font-weight=\"700\" font-family=\"".FONT_FAMILY."\" text-anchor=\"end\"><tspan>ID: 09308</tspan></text>";
        $sFooterTemplate .= "<text x=\"50\" y=\"330\" fill=\"white\" font-size=\"64\" font-weight=\"700\" font-family=\"".FONT_FAMILY."\" text-anchor=\"start\"><tspan>Жолаушылар көлігінің БАЙЛАНЫС ОРТАЛЫҒЫ</tspan></text><text x=\"1960\" y=\"375\" fill=\"white\" font-size=\"178\" font-weight=\"700\" font-family=\"".FONT_FAMILY."\" text-anchor=\"middle\"><tspan>574-777</tspan></text><text x=\"3547\" y=\"330\" fill=\"white\" font-size=\"64\" font-weight=\"700\" font-family=\"".FONT_FAMILY."\" text-anchor=\"end\"><tspan>Passenger Transport CONTACT CENTER</tspan></text>";
        $sFooterTemplate .= "</svg>\n";

        return $sFooterTemplate;
    }

    public static function drawSVGLayout($sBusCaptionKaz, $sBusCaptionEng, $sPolylines) {
        //841x1189mm A0 size
        //2523x3567 Formatted

        $iLayoutWidth  = self::LAYOUT_WIDTH;
        $iLayoutHeight = self::LAYOUT_HEIGHT;
        $sLegend = \model\LegendManager::drawLegend(200, 600);
        $sMap = \model\Util::getSVGMap($sPolylines);


        $sLayoutTemplate  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sLayoutTemplate .= "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"{$iLayoutWidth}\" height=\"{$iLayoutHeight}\" viewBox=\"0 0 {$iLayoutWidth} {$iLayoutHeight}\" version=\"1.1\">\n";
        $sLayoutTemplate .= $sMap;
        $sLayoutTemplate .= self::drawHeader($sBusCaptionKaz, $sBusCaptionEng);
        $sLayoutTemplate .= $sLegend;
        $sLayoutTemplate .= self::drawFooter();
        $sLayoutTemplate .= "</svg>"; // END SVG TAG

        file_put_contents('../src/php_layout_test.svg', $sLayoutTemplate);
//        return $sLayoutTemplate;
    }
}
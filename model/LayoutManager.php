<?php

class LayoutManager {

    const LAYOUT_HEIGHT  = 2523;
    const LAYOUT_WIDTH   = 3567;

    static function drawHeader($sBusCaption) {
        $iLayoutWidth = self::LAYOUT_WIDTH;

        $file = '../src/assets/astana-gerb.svg';
        $sAstanaGerb = file_get_contents($file);
        $sHeader = $sAstanaGerb;
        $sHeaderRect = '';
        return $sHeader;
    }

    static function drawHeaderTitle($sKazCaption = 'АСТАНА ҚАЛАСЫНЫҢ ҚОҒАМДЫҚ КӨЛІКТЕРІНІҢ ЖҮРІС СЫЗБАСЫ', $sEngCaption = 'SCHEME OF ASTANA PUBLIC TRANSPORT ROUTES') {
        $sHeaderCaption = "<text x=\"3212\" y=\"100\" fill=\"white\" font-size=\"60\" font-weight=\"700\" font-family=\"Century\" text-anchor=\"end\">";
        $sHeaderCaption .= "<tspan>{$sKazCaption}</tspan>";
        $sHeaderCaption .= "<tspan x=\"3212\" dy=\"1.2em\">{$sEngCaption}</tspan>";
        $sHeaderCaption .= "</text>";

        return $sHeaderCaption;
    }

    static function drawHeaderBusCaption($sKazCaption, $sEngCaption) {
        $sHeaderCaption  = "<text x=\"1783\" y=\"350\" fill=\"black\" font-size=\"84\" font-weight=\"700\" font-family=\"Century\" text-anchor=\"middle\">";
        $sHeaderCaption .= "<tspan>{$sKazCaption}</tspan>";
        $sHeaderCaption .= "<tspan x=\"1783\" dy=\"1.2em\">{$sEngCaption}</tspan>";
        $sHeaderCaption .= "</text>";

        return $sHeaderCaption;
    }

    static function drawHeaderRect() {

        // <!-- Background -->
        $sTemplate = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sTemplate .= "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"3567\" height=\"600\" viewBox=\"0 0 3567 600\" version=\"1.1\">";
        // <!-- Header Background color -->
        $sTemplate .= "<polygon points=\"300,25 3337,25 3337,200 300,200\" style=\"fill:#bf1e2e;stroke:white;stroke-width:1\" />";

        //<!-- Astana Gerb Logo -->
        $sAstanaGerb = file_get_contents('../src/assets/astana-gerb.svg');
        $sTemplate .= $sAstanaGerb;

        //<!-- Astra Header Title  -->
        $sTemplate .= self::drawHeaderTitle();

        //<!-- Astra Logo Placeholder -->
        $sAstraLogo = file_get_contents('../src/assets/astra-logo.svg');
        $sTemplate .= $sAstraLogo;

        //<!-- Bus Stop Caption -->
        $sTemplate .= self::drawHeaderBusCaption('НАЗАРБАЕВ УНИВЕРСИТЕТІ АЯЛДАМАСЫ', 'NAZARBAYEV UNIVERSITY BUS STOP');

        //<!-- Expo Logo Placeholder -->
        $sExpoLogo = file_get_contents('../src/assets/expo.svg');
        $sTemplate .= $sExpoLogo;

        //<!-- Compass Logo Placeholder -->
        $sCompass = file_get_contents( '../src/assets/compass.svg');
        $sTemplate .= $sCompass;

        // closing SVG tag
        $sTemplate .= "</svg>";
        return $sTemplate;
    }

    public static function getSVGLayout($sBusCaption) {
        //841x1189mm A0 size
        //2523x3567 Formatted

        $iLayoutWidth  = self::LAYOUT_WIDTH;
        $iLayoutHeight = self::LAYOUT_HEIGHT;


        $sLayoutTemplate  = "<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"{$iLayoutWidth}\" height=\"{$iLayoutHeight}\" viewBox=\"0 0 {$iLayoutWidth} {$iLayoutHeight}\" version=\"1.1\">\n";;
        $sLayoutTemplate .= self::drawHeader($sBusCaption);
        $sLayoutTemplate .= "</svg>";
    }
}
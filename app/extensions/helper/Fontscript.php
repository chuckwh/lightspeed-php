<?php

namespace app\extensions\helper;
/**
 * @author Chuck White
 * Component: Fontscript
 * usage: <?= $this->fontscript->fontscript() ?>
 *
 */

class Fontscript extends \lithium\template\helper\Html {

	public function fontscript($hasGaramond = FALSE){
		$html = '<script type="text/javascript">';
			$html .= 'WebFontConfig = {';
			if($hasGaramond) {
			$html .= 'google: { families: [ "Tenor Sans", "Archivo Black", "Archivo Narrow", "EB Garamond" ] }';
			}
			else {
				$html .= 'google: { families: [ "Tenor Sans", "Archivo Black", "Archivo Narrow" ] }';
			}
			$html .= '};';
			$html .= '(function() {';
			$html .= 'var wf = document.createElement("script");';
			$html .= 'wf.src = ("https:" == document.location.protocol ? "https" : "http") + ';
			$html .= '"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";';
			$html .= 'wf.type = "text/javascript";';
			$html .= 'wf.async = "true";';
			//BUGNOTE This is important. First script MUST be the font script as indicated by the indexed value below
			//So make sure this component is at the top of the page
			$html .= 'var s = document.getElementsByTagName("script")[0];';
			$html .= 's.parentNode.insertBefore(wf, s);';
			$html .= '})();';
		$html .= '</script>';
		$html .= '<style type="text/css">';
		$html .= '.wf-loading body {';
		$html .= 'font-family: serif';
		$html .= '}';
		$html .= '.wf-inactive body {';
		$html .= 'font-family: serif';
		$html .= '}';
		$html .= 'wf-active body {';
		$html .= 'font-family: "Tenor Sans", serif';
		$html .= '}';
		$html .= '</style>';
		return $html;
	}
}
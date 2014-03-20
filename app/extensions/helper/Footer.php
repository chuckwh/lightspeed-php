<?php

namespace app\extensions\helper;

/**
 * @author Chuck White
 * Component: footer
 * usage: <?= $this->footer->footer() ?>
 *
 */

class Footer extends \lithium\template\helper\Html {

	public function footer(){
		$html = "";
		$html .=  "<div id='footer' class='footer-div'>";
		$html .= "<ul class='footer-ul'>";
			$html .= "<li class='first-footer'><a href='/' itemprop='relatedLink' title='Home'>Home Page</a></li>";
			$html .= "<li  class='last-footer'><a href='/photos' itemprop='relatedLink' title='Photos'>Photos</a></li>";
		$html .= "</ul>";
		$html.= "</div>";

		return $html;
	}
}
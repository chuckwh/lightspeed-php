<?php

namespace app\extensions\helper;


/**
 * @author facingfive
 * Component: ieconditionals
 * usage: <?= $this->ieconditionals->conditionals() ?>
 * Generally, this only needs to be used on public facing documents
 * Once the user gets inside, ie, the profile page, they would not be
 * using IE anyway, since FacingFive video doesn't work on older versions of
 * IE (only newer versions of IE which might use the chrome plugin)
 *
 */
class Ieconditionals extends \lithium\template\helper\Html {

	public function conditionals(){
		$html = '';
		$html .= '<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->';
		$html .= '<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->';
		$html .= '<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->';
		$html .= '<!--[if gt IE 8]><!-->';
		$html .= '<html  class="no-js"	lang="en">';
		$html .= '<!--<![endif]-->';
		return $html;
	}
}
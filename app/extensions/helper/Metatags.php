<?php

namespace app\extensions\helper;

use lithium\core\Libraries;
/**
 * @author createamixer
 * Component: metatags
 * usage: <?= $this->metags->metatags(true) //true if viewport doesn't need any kind of mobile support - default is FALSE ?>
 *
 */
class Metatags extends \lithium\template\helper\Html {

	public function metatags($viewportOff = FALSE, $xFrameRestricted = TRUE){
		$html = '';

        /**
         * Generally you will want to restrict the X-Frame
         * to prevent cross domain scripting attacks. However, you will need to set the
         * second parameter, $xFrameRestricted, to FALSE if you need to include your page
         * as an iframe on another domain. Example: Facebook app. If you have the X-Frame-Options
         * Facebook WILL NOT render your page.
         */

        if($xFrameRestricted) {
            $html .= '<meta http-equiv="X-Frame-Options" content="sameorigin">';
        }


		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		$html .= '<meta itemprop="description"  content="'. DESCRIPTION_META .'">';
		$html .= '<meta name="description"  content="'. DESCRIPTION_META .'">';
		$html .= '<meta property="og:description"  content="'. DESCRIPTION_META .'">';
		/* http://stackoverflow.com/questions/6771258/whats-the-difference-if-meta-http-equiv-x-ua-compatible-content-ie-edge */
		$html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
		$html .= '<meta name =  "ROBOTS" content="index,follow">';
		$html .= '<meta itemprop="name" content="Cloudbook">';
		$html .= '<meta itemprop="author" content="Wyanet LLC">';
		$html .= '<meta name="author" content="Copyright 2013, Wyanet LLC, Creative Commons License. You are free to use">';
		$html .= '<meta itemprop="inLanguage" content="English">';
		$html .= '<meta itemprop ="image"  content="">';
		$html .= '<meta property="og:title"  content="'. DESCRIPTION_META .'">';
		$html .= '<meta property="og:type" content="website">';
		$html .= '<meta property="og:url" content="" />';
		$html .= '<meta property="og:image" content="" />';
		$html .= '<meta meta name="p:domain_verify" content="" />';
		$html .= '<meta itemprop="copyrightHolder" content="Wyanet LLC">';
		$html .= '<meta itemprop="copyrightYear" content="2012">';
		$html .= '<meta itemprop="headline"  content="'. TAGLINE .'">';
		$html .= '<meta itemprop="mentions" content="">';
		$html .= '<meta itemprop="keywords" content="'. SITE_KEYWORDS .'">';
		$html .= '<meta name="keywords"  content="'. SITE_KEYWORDS .'">';
		$html .= '<meta name="news_keywords"  content="'. NEWS_KEYWORDS .'">';
		$html .= '<link rel="canonical" href="https://YOURDOMAIN.COM">';
		$html .= '<link rel="search" type="application/opensearchdescription+xml" title="CreateAMixer" href="https://YOURDOMAINorCDN.COM/opensearch.xml">';



		if(!$viewportOff) {
		$html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
		}


        return $html;
	}
}
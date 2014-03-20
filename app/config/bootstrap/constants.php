<?php
// Set SANDBOX (test mode) to true/false.


define('SANDBOX', LITHIUM_APP_PATH . 'true');
define('MODE', (SANDBOX ? 'TEST' : 'LIVE') );
// Set PayPal API version and credentials.
define('API_VERSION', '85.0');


define('BETA_TESTING', TRUE);
define('MANDRILL_API', '111111');
define ('LINKEDIN_API_KEY_PUBLIC', '111111');
define ('LINKEDIN_API_KEY_PRIVATE', '111111');
// END PayPal API version and credentials.

//STRIPE CREDENTIALS
define('STRIPE_SECRET_KEY_TEST', '111111');
define('STRIPE_PUBLISHABLE_KEY_TEST', '111111');
define('STRIPE_SECRET_KEY_PRODUCTION', '111111');
define('STRIPE_PUBLISHABLE_KEY_PRODUCTION', '111111');
define('TWILIO_ACCOUNT_SID', '111111');
define('TWILIO_AUTH_TOKEN', '111111');
define('TWILIO_PHONE', '+111111111');
//CSS STYLE SHEETS AND JS FILES
/*
 * 		I have found the best way to avoid caching on both the server
 * 		and the browser is to add a numeric revision number for static
 * 		CSS and JS. You can also use Cloudfront to purge a cached file or group of them
 * 		NOTE: Lithium doesn't require the use of the extension because
 * 		it injects the extension for you on the link builders associated with the extension
*/
define('GLOBAL_STYLE_SHEET', 'app');

define('BOOTSTRAP_STYLE_SHEET', 'bootstrap');
define('BOOTSTRAP_GLYPHS_STYLE_SHEET', 'bootstrap-glyphicons-local');
define('BOOTSTRAP_RESPONSIVE_STYLE_SHEET', 'bootstrap-responsive');

/*
 * JAVASCRIPT
 *
 */

//FULL MODERNIZR - YOU SHOULD REALLY JUST GET THE TESTS YOU WANT FROM http://modernizr.com/download/
define('MODERNIZR_JS', 'modernizr-full-min.js');
//JQUERY MIGRATE IS FOR MIGRATING PRE-1.9 versions of jquery
define('JQUERY_MIGRATE', 'jquery/jquery-migrate-1.2.1.min');
define('JQUERY_2x', 'jquery/jquery-2.0.3.min');
define('JQUERY_1.10x', 'jquery/jquery-1.10.2.min.js');
define('JQUERY_OLD_JS', 'jquery/jquery-older.min');
define('JQUERY_UI_JS', 'jquery/jquery.ui.min');



define('BOOK_JS', 'app/book');
define('BOOTSTRAP_JS', 'bootstrap.min');
define('BOOTSTRAP_FULL', 'bootstrap');
//PHOTOS
define('PHOTOS_JS', 'photos');
define('GET_IMAGE_DATA_JS', 'getimagedata.min');
define('MASONRY_JS', 'masonry.min');
define('CLOUDINARY_JQUERY_UI_WIDGET', 'cloudinary/jquery.ui.widget');
define('CLOUDINARY_JQUERY_IFRAME_TRANSPORT', 'cloudinary/jquery.iframe-transport');
define('CLOUDINARY_JQUERY_FILEUPLOAD', 'cloudinary/jquery.fileupload');
define('CLOUDINARY_JS', 'cloudinary/jquery.cloudinary');
/*
 * *** WebRTC: */
define('TOKBOX_JS', 'https://swww.tokbox.com/webrtc/v2.0/js/TB.min.js');
define('TOKBOX_JS_DEBUG', 'https://swww.tokbox.com/webrtc/v2.0/js/TB.js');


define('COPYRIGHT', 'Copyright 2013, Wyanet LLC');
define('TAGLINE', 'Web Development in the Cloud');
define('H1_TAGLINE', 'Web Development in the Cloud');
define('NEW_TAGLINE_LONGER', 'Web Development in the Cloud');
define('TAGLINE_SHORTER', 'Web Development in the Cloud');
//keywords

define('SITE_KEYWORDS', 'cloud development, web cloud development, AWS, Cloudinary, OpenTok, Twilio, PaaS, REST, RESTful APIs');
define('NEWS_KEYWORDS', 'Web Development in the Cloud Released');
define('FB_IMAGE', '');

define('DESCRIPTION_META', 'Web Development in the Cloud: Developing Applications in the Cloud');



//CLOUDINARY
define ('CLOUDINARY_CLOUD_NAME', '111111');
define ('CLOUDINARY_API_KEY', '111111');
define ('CLOUDINARY_API_SECRET', '111111');
define('CLOUDINARY_BASE_URL', 'https://res.cloudinary.com/xxxxx/')


?>
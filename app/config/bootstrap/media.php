<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The `Collection` class, which serves as the base class for some of Lithium's data objects
 * (`RecordSet` and `Document`) provides a way to manage data collections in a very flexible and
 * intuitive way, using closures and SPL interfaces. The `to()` method allows a `Collection` (or
 * subclass) to be converted to another format, such as an array. The `Collection` class also allows
 * other classes to be connected as handlers to convert `Collection` objects to other formats.
 *
 * The following connects the `Media` class as a format handler, which allows `Collection`s to be
 * exported to any format with a handler provided by `Media`, i.e. JSON. This enables things like
 * the following:
 * {{{
 * $posts = Post::find('all');
 * return $posts->to('json');
 * }}}
 */
use lithium\util\Collection;

Collection::formats('lithium\net\http\Media');

use lithium\action\Dispatcher;
use lithium\action\Response;
use lithium\net\http\Media;
use lithium\core\Libraries;




Media::type('ajax', array('application/xhtml+xml', 'text/html'), array(
    'view' => 'lithium\template\View',
    'paths' => array(
        'template' => array(
            '{:library}/views/{:controller}/{:template}.ajax.php'
        ),
        'layout' => '{:library}/views/layouts/default.ajax.php'
    ),
    'conditions' => array('ajax' => true)
));
/*
Media::type('jsonp', array('application/json', 'application/jsonp', 'application/javascript'), array('cast' => false, 'encode' => function($data) {
	return json_encode($data);
})); */

Media::type('jsonp', array('application/json', 'application/jsonp', 'application/javascript'), array(
    'view' => 'lithium\template\View',
    'layout' => false,
    'paths' => array(
        'template' => array(
            '{:library}/views/{:controller}/{:template}.jsonp.php'				),
        'layout' => '{:library}/views/layouts/default.jsonp.php'
    ),
    'conditions' => array('type' => true),
    'encode' => function($data, $handler, &$response) {
        // do something with it
        return $_GET['callback']."(".json_encode($data).")";
    }
));

Media::type('jpg', 'image/jpeg', array('cast' => false, 'encode' => function($data) {
    return $data['photo']->file->getBytes();
}));


?>
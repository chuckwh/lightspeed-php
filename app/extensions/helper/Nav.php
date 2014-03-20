<?php

namespace app\extensions\helper;

/**
 * @author facingfive
 * Component: navbar
 * usage: <?= $this->navbar->navbar($userSession['username']) ?>
 * username is required for logged in users, the second parameter is the layout name,
 * as some of the navbar has conditional displays based on layout type
 *
 */

class Nav extends \lithium\template\helper\Html {

	public function nav($userName){
		$html = '';
		if($userName) {
			$html .= '<a href="/users/logout" class="" title="Logout"><i class="icon-home"></i> Logout</a>';
			$html .= ' | <a href="/events/searchEvents">Search For Events</a> | <a href="/events/addEvents">Add an Event</a> | <a href="/photos">Photos</a>';
		}
		else {
			$html .= '<a href="/users" class="" title="Register or sign in"><i class="icon-home"></i> Register or SignIn</a>';
		}
		return $html;
	}
}
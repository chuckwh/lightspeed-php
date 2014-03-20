<?php

namespace app\controllers;

use lithium\core\Environment;
use lithium\storage\Session;
use li3_access\security\Access;
use lithium\security\Auth;
use lithium\security\Password;
use lithium\net\http\Router;
use app\extensions\Set;
use app\extensions\SessionMessage;
use app\extensions\LibraryManager;
use MongoCursor;
use MongoCursorException;


class AppController extends \lithium\action\Controller {
    protected $_config = null;

    protected $_isLibrary = true;

protected $_secure = false;


    protected $_user = false;

    /* (non-PHPdoc)
     * @see \lithium\action\Controller::render()
     * the $auth or $adminauth variable is populated with user Authentication data (or false if check failed).
     * $auth and #adminauth will be available in the view for any controller action extending AppController.
     */
    public function render($options = array()) {

/*     	$auth = Auth::check('default');
    	$this->set(compact('auth')); */
    	parent::render($options);
    }


    protected function _init(){
    	//TODO localization will require conditionals here

    	MongoCursor::$timeout = -1;

    	try {


    	$sessioninfo = Session::read('User');
    	} catch (MongoCursorException $e) {
    		$sessioninfo = false;
    	}
    	//setting default time zone
    	date_default_timezone_set('UTC');

    	$this->_render['negotiate'] = true;

        //set it up so libraries also use the applications layout.
        if($this->_isLibrary){
            $this->_render['paths']['template'] = '{:library}/views/{:controller}/{:action}.{:type}.php';
			/* We don't always want the default layout, so we add :layout to allow us to do either call the
			 * _render property or render() method in the controller in order to use a different layout
			 * In this case we generally will use the default layout, but the video pages have their own layout
			 * called videolayout, so this makes it possible to use them */
            $this->_render['paths']['layout'] = LITHIUM_APP_PATH . '/views/layouts/{:layout}.{:type}.php';

        }

//         Session::write('beforeAuthURL', '/' . $this->request->url, array('name' => 'facing_five_cookie', 'expires' => '+1 hour'));


        //library manager
        LibraryManager::applyControllerFilters($this, __CLASS__);

        //allow config data to reach everything
//         $this->_config = Environment::get('board');
        $this->set(array('config' => $this->_config));

        //process any session messages we have
        SessionMessage::process($this);

        $this->_filter(__METHOD__, array(), function($self,$params){});
        parent::_init();


    }

    /**
     * When ensuring a user is logged in you should always
     * make sure that the following keys are set in
     * $userData or else the system will not recognize them
     * as logged in:
     *
     * 'username'
     * 'id'
     * 'loginTime'
     *
     * additional libraries may require additional functions
     *
     * @param array $userData
     * @return void
     */
    public function setUserData($userData = array()){
        $this->_user = $userData;
        /*
         * userSession gets used throughout the site for signed in users
         *  */
        $this->set(array('userSession' => $userData));
        $testsessionInfo = $this->_user;
        $test=null;
    }
//TODO should we use server cache instead of cookies?
    public function getUserData(){
        return $this->_user;
    }

    protected function _userLoggedIn(){

    	if(Set::arrayKeysSet($this->_user, array('username', 'email', 'password', 'password_verify','location', 'timezone'))){
    		return true;
    	}
    }



}

?>
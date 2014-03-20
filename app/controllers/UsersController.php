<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2013, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use MongoDate;
use Mandrill;

use app\models\Users;
use lithium\security\validation\RequestToken;

use lithium\security\Auth;
use lithium\storage\Session;

use app\extensions\Set;
use app\extensions\SessionMessage;
use lithium\security\Password;

class UsersController extends \app\controllers\AppController {


    /**
     * ========== CONTROLLER FUNCTIONS ==========
     * These correspond to specific actions for the request handler
     * In other words, index would go to http://yourapp/users,
     * login would go to http://yourapp/login
     * which in this case is a JSON POST to the controller through the
     * JS function in book.js
     */
	public function index() {
        $viewScript= 'users';
        // redirect users if they're logged in
        if (Auth::check ( 'default', $this->request )) {
        	$this->redirect("/events/searchEvents");
        }
        return compact('viewScript');
               
	}


    public function login(){

        $username = null;
        $interests = null;
        $email = null;
        $message = null;
        $notification = null;
        $showMenu = false;

        //check for form data
        if(isset($this->request->action)){
            $data = &$this->request->data;
            if(Set::arrayKeysSet($data, array('email', 'password'), true)){
                //first check the email by examining mongo db for a match
                $user = Users::find('first', array(
                    'conditions' => array(
                        'email' => $data['email']
                    )
                ));

                /*
                 * USER SESSION
                * This is helpful for debugging because the
                * $checkedPwd variable will walk through each hashed character in the debugger
                * $pwdForm = $user->password;
                $pwdData = $data['password'];
                $checkedPwd = Password::check( $data['password'], $user->password); */
                //check the password
                if(Password::check( $data['password'], $user->password) ){
                    $saveConditions =  array(
                        '_id' => $user->_id
                    );
                   
                    /*
                    This is a MongoDB session, so you'll need to change your Session type 
                    if you don't want this. The advantages to a MongoDB session are they are 
                    good for load balanced multiple instances. The disadvantage is that you'll
                    need to purge sessions so that the DB doesn't become huge very quickly. 
                    
                    */
                    Session::write('User', array(
                        'last_login_time' => time(),
                        'email' => $user->email,
                        'id' => $user->_id,
                        'username' => $user->username,
                        'location' => $user->location,
                        'timezone' => $user->timezone

                    ), array('name' => 'login') );
                    //Set the authorization
                    Auth::set('default', $user->username);
					$showMenu = true;
                    $message = "You have successfully logged in.";
                    $notification = "notice";
                    

                }

                else if($data['email'] == $user->email) {
                	$message= "We have a guest with this email, but the password seems to be incorrect. Try again?";
                	$notification = "error";
                }
                else if($data['email'] != $user->email) {
                	$message= "We do not appear to have a guest with this email. If you think this is a mistake, please click the feedback button on the far right of the screen";
                	$notification = "error";
                }
                else {
                	$message = "The username and password combination you entered was incorrect.";
                	$notification = "error";
                }
            }
            $this->set(array('config' => ''));


        }

        return compact('message', 'notification', 'showMenu');
    }


    public function logout(){
    	Session::clear();
    	Auth::clear('default');
    	$this->redirect("/");
    
    }
    public function register() {

        $emailBody= '';
        $message = null;
        $notification = null;
        $generated = null;
        $success = false;
        //check for form data
        if($this->request->data){

            //variable for storing the username, etc
            $checkFor = array('username', 'email', 'password', 'password_verify','location', 'timezone',  'cmToken');
            if(Set::arrayKeysSet($this->request->data, $checkFor, true)){
                $data = &$this->request->data;

                /*
                 * trim whitespace from user input on email and user name to prevent duplicate registrations
                */
                $email = trim($data['email']);
                $fieldName = null;
                $username = trim($data['username']);
                $user_email = Users::find('first', array(
                    'conditions' => array(
                        'email' => $email
                    )));
                $userDbName = Users::find('first', array(
                    'conditions' => array(
                        'username' => $data['username']
                    )));
                if($user_email || $userDbName){
                    if($user_email) {

                        $message ="Someone already has an account registered to that email address.";
                        $notification = "error";

                    }
                    if($userDbName) {
                        $message = "Someone already has an account registered to that name";
                        $notification = "error";
                    }
                }
                else
                    if($this->verifyMailAddress($email)) {
                        if($data['password'] == $data['password_verify']){

                            //we don't want to keep the password_verify, just be certain it matches password, so destroy it
                            unset($data['password_verify']);
                            //Now we can hash the password using lithium's nifty bcrypt
                            //Lithium will use the strongest hash available on the system
                            //TODO on live, we may want to consider a salt
                            $data['password'] = Password::hash($data['password']);
                            $usersModel = new Users();
                            //store a generated verification string for users to verify email
                            //Hash the generated verfication string using lithium's nifty bcrypt
                            //Lithium will use the strongest hash available on the system
                            //TODO on live, we may want to consider a salt
                            $data['password_verify'] = Password::hash($data['password_verify']);

                            //prevent undesirables (such as fields not intended for the system))
                            //be sure any fields you have in your data array are in this array else you'll get an Undefined index php notice and the document will not get passed to the model or the DB
                            $data = Set::removeNonMatching($data, array('username', 'email', 'password', 'location', 'timezone', 'cmToken', 'password_verify'));

                            self::applyAuthenticationFilter();
                            if ( RequestToken::check($data['cmToken'])  ) {
                                $user = Users::create($data);
                                $success = $user->save();
                            }
                            else {
                                if(!RequestToken::check($data['cmToken'])) {
                                    $message = "Whoops, there was a REALLY big error. Please refresh your page and try again.";
                                }
                                return compact('message');
                            }
                            if(!$success){
                                $message =   "There was an unknown problem creating your account.";
                                $notification = "error";

                            }else{
                                $notification = "notice";
                                $newUser = Users::find('first', array(
                                    'conditions' => array(
                                        'email' => $email
                                    )));

                                $subject = "Here is Your CreateAMixer Verification Email";
                                $fromEmail = "support@mailserv.createamixer.com";
                                $fromName="CreateAMixer";
                                $id = $newUser->_id;

                                $message = "You have successfully signed up!";
                                $subject = "Thanks for Registering";
                                $emailBody = "<h1>Thanks for registering!</h1><p>We hope you enjoy the service.</p>";
                                if($newUser->email === $email) {
                                   $this->sendMail(json_encode(array("email" => $email)), $emailBody, $subject, $fromEmail, $fromName );
                                }


                            }
                            $this->set(array('config' => ''));
                        }
                        else{
                            $message = "The provided passwords do not match.";
                            $notification = "error";
                            $fieldName = "password";
                        }
                    }
                    else {
                        $message = "Please provide a valid email address";
                        $notification = "error";
                        $fieldName = "email";

                    }

            }else{
                $message = "One or more fields were not filled out.";
                $notification = "error";
            }



        }
        //BE SURE TO LEAVE THIS OFF IF THIS ACTION IS AN AJAX CALL
//        $this->_render['layout'] =  'default';
        $returnData = compact('message', 'notification', 'fieldName');
        return $returnData;
    }


    /**
     * ========== HELPER FUNCTIONS ==========
     */

    /**
     * This function leverages PHP's built in email validation filter
     * @param  $address - the email address to evaluate
     * @return boolean
     */
    public function verifyMailAddress($address)
    {
        if( filter_var($address, FILTER_VALIDATE_EMAIL) )
            return true;
        else
            return false;
    }



    protected function sendMail($to, $messageBody, $subject, $fromEmail, $fromName) {
        Mandrill::setApiKey(MANDRILL_API);
        $request_json = '{"type":"messages","call":"send","message":{"html": "' . $messageBody . '",  "subject": "' . $subject . '", "from_email": "' . $fromEmail . '", "from_name": "' . $fromName . '", "to":[' . $to . '],"headers":{"...": "..."},"track_opens":true,"track_clicks":true,"auto_text":true,"url_strip_qs":true,"tags":["test","example","sample"],"google_analytics_domains":["createamixer.com"],"google_analytics_campaign":["..."],"metadata":["..."]}}';

        $ret = Mandrill::call((array) json_decode($request_json));

        return compact('ret');

    }


    /**
     * @return mixed
     */
    public function applyAuthenticationFilter() {
        Users::applyFilter('save', function($self, $params, $chain) {
            $entity = $params['entity'];
            $data 	= $params['data'];
            //work with the schema to write entity values
            if($data) {
                $entity->set($data);
                if(isset($data['password']) && $data['password_verify']) {
                    $entity->password = Password::hash($data['password']);
                    $entity->password_verify = Password::hash($data['password_verify']);
                    $entity->verification = $data['verification'];
                    $entity->gender = $data['gender'];
                    $entity->location= $data['location'];
                    $entity->timezone = $data['timezone'];


                }
            }

            if(!$entity->id) {
                $entity->created = new MongoDate();
                $entity->updated = new MongoDate();
                $entity->credits = 100;
                $entity->hasLoggedIn = 0;
                $entity->flags = 0;
                $entity->badges = 0;
                $entity->verified = 0;
                $entity->interests = array('mixers');

            }

            $params['entity'] = $entity;
            return $chain->next($self, $params, $chain);
        });
    }




}


?>
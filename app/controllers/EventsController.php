<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2013, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;
use li3_geo\data\Geocoder;
use li3_geo\data\Location;
use Services_Twilio;
use Services_Twilio_RestException;
use app\models\Events;

class EventsController extends \app\controllers\AppController {

	public function index() {
		return $this->render(array('layout' => false));
	}

	
	public function get() {
		if($this->request->data) {
			$data = $this->request->data;
			$events = Events::find('all'. array('conditions' => array(
				'name' => $data['name']
				)));
		}
	}

	public function addEvents() {
		//controller function for addEvents view page
	}

	public function addEvent() {
		$events = null;
		$message = null;

		if ($this->request->data) {
			$hostId = null;
			$data = $this->request->data;
			$data['guests'] = array( $data['hostid']);
			$name = trim($data['name']);
			try
				{
					if($data['hostid'] !=null) {
						$hostId = $data['hostId'];
				}
			}
			catch (Exception $e)
			{
			 throw new Exception( 'You need to be logged in to do this', 0, $e);
			 $message = $e->getMessage();
			}
			//TODO error handling on null
			$location = $data['location'];
			$data['name'] = $name;
            $data['location'] = $location;

			try
			{

                $events = Events::create ( $data );
				$success = $events->save();
				$message =  "Event Created";
			}
			catch (Exception $e)
			{
			 throw new Exception( 'Error creating this event', 0, $e);
			 $message = $e->getMessage();
			}
			
		}

		return compact ('message');
	}

		public function joinEvent() {
		$events = Events::all ();
		$updatedEvent = null;

		$guests = null;
		$updatedCount = null;
		$errorMessage = '';
		$name = $userData ['username'];
		$userData = parent::getUserData();

		$data = $this->request->data;
		$conditions = array (
					'_id' => $this->request->data ['eventId']
			);
			$conditions2 = array (
					'_id' => $userData ['id']
			);
			$event = Events::find ( 'first', array (
				'conditions' => $conditions
			) );	

			$user = Users::find ( 'first', array (
					'conditions' => $conditions2
			) );
			$guests = iterator_to_array ( $signedUpEvent->guests );

			$result = Events::update ( $toSave, $conditions );
		

			if ($result) {
				$updatedEvent = Events::find ( 'first', array (
						'conditions' => $conditions
				) );
				if($updatedEvent != null) {
					$updatedCount = count ( iterator_to_array ( $updatedEvent->guests ) );
				}
				
			}

		return compact ( 'updatedpEvent', 'errorMessage', 'updatedCount' );
	}

	/**
	 * @return multitype: List of facing five Events
	 */
	public function searchLocal() {
		$this->_render['layout'] = false;
		if ($this->request->data) {
			$data = $this->request->data;
	
			// 			$page = $data['page'] ?: 1;
			$limit = 200;
			$order = array('date' => 'date');
			$conditions = array (
					'eventEnd' => null
	
			);
			$events = Events::all(compact('conditions', 'order','limit','page'));
			$coords = array(Geocoder::find('google', array('address' => $data['location']) ) );
			$coords1 =null;
			$coords2 = null;
			$total = Events::count();
			$coordinates = $coords[0]->coordinates();
			$address = $coords[0]->address();
			$location = $data['location'];
			//	$events = Events::find('near', $coordinates);
			//TODO move geo stuff to back end, currently it is in the aja
			/* 			$events = Events::find ( 'all', array (
			 'conditions' => array (
			 		'location' => $this->request->data
			 )
			) ); */
	
		}
	
		return compact ( 'events', 'location', 'coordinates', 'address', 'page', 'limit', 'total', 'isFb');
	}

	/**
	 * @return multitype: List of  Events
	 */
	public function searchEvents() {

	
	}

	/*
	 * usage: $message = $this->sendSMS($facingFive, $phone);
	*  */
	/**
	 * @param array $numberToCall
	 * @param String $userPhone
	 * @return multitype:
	 */
	public function sendSMS($numbersToCall,  $smsBody){
		$AccountSid =  TWILIO_ACCOUNT_SID;
		$AuthToken = TWILIO_AUTH_TOKEN;
		$message = null;
		$numbers = null;
		$userPhone = TWILIO_PHONE;
		$phoneNumbers = null; //TODO set up a DB for this
		// instantiate a new Twilio Rest Client
		$client = new Services_Twilio($AccountSid, $AuthToken);
	
		// make an array of people we know, to send them a message.
		$peopleArray = null;
		/*
		 * 			TODO set up a DB for this
		$numbers = iterator_to_array($phoneNumbers);
		*/
		$numbers = array($numbersToCall);
		
			foreach ($numbers as $number) {
			
				try {
					$sms = $client->account->sms_messages->create(
		
							// Step 6: Change the $userPhone var below to be a valid Twilio number
							// that you've purchased
							$userPhone,
		
							// the number we are sending to - Any phone number
							$number,
							$smsBody
		
							// the sms body
		
					);
					$message = $smsBody;
				} catch (Services_Twilio_RestException $e) {
					$message = $e->getMessage();
			}
			 
			//     				}
		
						// Display a confirmation message on the screen
		
			}
		return compact('message');
		}
	
		public function postSMS() {
			$data = $this->request->data;
			$response = null;
			if($data){
				$numbersToCall = array();
				array_push($numbersToCall, $data['phone_field']);
				$smsBody= $data['sms_message'];
				$response = $this->sendSMS($numbersToCall,  $smsBody);
				//clear the config and userSession object so they don't 
				//get sent as part of the JSON response
				$this->set(array('config' => '', 'userSession'=> ''));
			}
			return compact('response');
		}	

}


?>
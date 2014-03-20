<?php
namespace app\controllers;

use cloudinary\Cloudinary;
use cloudinary\Uploader;
use app\models\Users;

use app\models\Photos;
use users\models\Events;
use lithium\security\Auth;
use app\models\Tags;
use Exception;
use lithium\storage\Session;

// class PhotosController extends  UserController  {
class PhotosController extends \app\controllers\AppController {
	protected $_user = false;


	protected function _init(){
		parent::_init();
	}


	public function index($tags = null) {
		$this->_render ['layout'] = 'photos';
		$order = array('votes' => 'desc');
		$conditions = $tags ? compact('tags') : array();
		$photos = Photos::all(compact('conditions', 'order'));

		return compact('photos');
	}

	public function image() {
		$this->_render ['negotiate'] = true;
		$this->_render ['type'] = 'image/jpeg';
		$photo = Photos::first($this->request->id);
		return compact('photo');
	}

	public function view() {
		$userData = parent::getUserData();
		$this->_render ['layout'] = 'photos';
		$canEdit = false;
		$photo = Photos::first($this->request->id);
		$creatorId = $photo->user_id;
		$user = Users::find('first', array(
				'conditions' => array(
						'_id' => $creatorId
				)
		));
		if($creatorId == $userData['id']) {
			$canEdit = true;
		}
		return compact('photo', 'canEdit');
	}

	public function guestPosts() {
		$userData = parent::getUserData();
		$canEdit = null;
		$this->_render ['layout'] = 'photos';
		$requestId = $this->request->id;
		$conditions = array(
				'user_id' => $requestId
		);

		$user = Users::find('first', array(
				'conditions' => array(
						'_id' => $requestId
				)
		));

		$userName = $user->username;
		$userId = $user->_id;
		if($userData['id'] == $userId) {
			$canEdit = true;
		}
		$photos = Photos::find ( 'all', array (
				'conditions' => $conditions
		) );

		return compact('photos', 'userName', 'requestId', 'canEdit');
	}
	public function location() {
		$this->_render ['layout'] = 'photos';
		$requestId = $this->request->id;
		$conditions = array(
				'location' => $requestId
		);

		$photos = Photos::find ( 'all', array (
				'conditions' => $conditions
		) );

		return compact('photos',  'requestId');
	}
	
	public function addPhotoUrl() {
		if (Auth::check ( 'default', $this->request )) {
			$message = '';
			$generatedUrl = '';
			$userData = parent::getUserData();
			$dbError = null;
			$result = null;
			$document = null;
			$newData = null;
			$savePhotoList = array();
			$conditions = null;
	
			if ($this->request->data) {
				$data = $this->request->data;

	
	
				$pic = $data['secureUrl'];
	
				try {
					$document['user_id'] = $userData['id'];
					$document['location'] = $userData['location'];
					$document['votes'] = 0;
					$document['user_name'] = $userData['username'];
					$document['tags'] = $data['tags'];
					$document['ctitle'] = str_replace(' ', '_', $data['title']);
					$document['title'] = $data['title'];
					$document['credit'] = $data['credit'];
					$document['url'] = $pic;
					$document['format'] = $data['format'];
					$document['width'] = $data['width'];
					$document['height'] = $data['height'];
					$document['public_id'] = $data['public_id'];
					$document['version'] = $data['version'];
					$document['signature'] = $data['signature'];
					$document['status'] = $data['status'];
					$result = Photos::create($document);
					$success = $result->save();
				} catch (Exception $e) {
					$dbError = $e->getMessage();
				}
	
	
				if($result == true) {
					$message = "Success! Your picture has been saved.";
					$conditions = array(
							'signature' => $data['signature']
					);
	
					$newData = Photos::find('first', array(
							'conditions' => $conditions
					));
				}
				else {
					$message = "Ooops, there was a problem: " .$dbError;
				}
	
	
				$this->set(array('config' => ''));
			}
			return compact( 'message', 'newData');
		}
	}
	
	public function updatePhotoUrl() {
		if (Auth::check ( 'default', $this->request )) {
			$message = '';
			$generatedUrl = '';
			$userData = parent::getUserData();
			$userPost = null;
			$dbError = null;
			$result = null;
			$document = null;
			$newData = null;
			$savePhotoList = array();
	
			if ($this->request->data) {
				$data = $this->request->data;
	
				$conditions = array(
						'_id' => $userData['id']
				);
				$users = Users::find('first', array(
						'conditions' => $conditions
				));
				$conditions2 = array(
						'_id' => $data['id']
				);
				$userPost = Photos::find('first', array(
						'conditions' => $conditions2
				));
	
	
				$pic = $data['secureUrl'];
	
				try {
					$save = array('$set' =>  array(
							'user_id' => $userData['id'],
							'location' => $userData['location'],
							'votes' => 0,
							'user_name' => $userData['username'],
							'tags' => $data['tags'],
							'title' => $data['title'],
							'credit' => $data['credit'],
							'url' => $pic,
							'format' => $data['format'],
							'width' => $data['width'],
							'height' => $data['height'],
							'public_id' => $data['public_id'],
							'version' => $data['version'],
							'signature' => $data['signature'],
							'status' => $data['status'])
					);
					$result = Photos::update($save, $conditions2);
				} catch (Exception $e) {
					$dbError = $e->getMessage();
				}
	
	
				if($result == true) {
					$message = "Success! You have successfully edited your post.";
	
	
					$newData = Photos::find('first', array(
							'conditions' => $conditions2
					));
				}
				else {
					$message = "Ooops, there was a problem: " . $dbError;
				}
	
	
				$this->set(array('config' => ''));
			}
			return compact( 'message', 'newData');
		}
	}
	
	public function getVoteCount() {
		$signature = null;
		$votes = null;
		$userPhotos = null;
		$id = null;
		if ($this->request->data) {
			$data = $this->request->data;
			$signature = $data['signature'];
			$conditions = array(
					'signature' => $signature
			);

			$userPhotos = Photos::find('first', array(
					'conditions' => $conditions
			));
			$votes = $userPhotos->votes;
			$id = $userPhotos->_id;
			$this->set(array('config' => ''));
		}
		return compact('votes', 'userPhotos');
	}

	public function getFlagCount() {
		$signature = null;
		$flags = null;
		$userPhotos = null;
		$id = null;
		if ($this->request->data) {
			$data = $this->request->data;
			$signature = $data['signature'];
			$conditions = array(
					'signature' => $signature
			);

			$userPhotos = Photos::find('first', array(
					'conditions' => $conditions
			));
			$flags = $userPhotos->flags;
			$id = $userPhotos->_id;
			$this->set(array('config' => ''));
		}
		return compact('flags', 'userPhotos');
	}
	public function postPushUp() {
		$message = '';
		$generatedUrl = '';
		$userData = parent::getUserData();
		$userPhotos = null;
		$id=null;
		$dbError = null;
		$result = null;
		$success = null;
		$document = null;
		$savePhotoList = array();
		$conditions = null;
		if (Auth::check ( 'default', $this->request )) {

			$voteSession = Session::read('Votes');
			if ($this->request->data) {
				$data = $this->request->data;
				$signature = $data['signature'];
				$conditions = array(
						'signature' => $signature
				);

				$userPhotos = Photos::find('first', array(
						'conditions' => $conditions
				));
				$votes = $userPhotos->votes;
				try {
					if($votes != null) {
						$count = array(
							'$set' =>  array('votes' => $votes + 1 )
						);
					}
					else {
						$count = array(
								'$set' =>  array('votes' => 1 )
						);
					}
					if($userPhotos->_id != $voteSession['photo_id']) {
					$result = Photos::update($count, $conditions);
					}
					$id = $userPhotos->_id;
					Session::write('Votes', array(
					'photo_id' => $id,
					));


// 					$success = $result->save();

				} catch (Exception $e) {
					$dbError = $e->getMessage();
					$message = "Ooops, there was a problem with your vote: ";
				}

			}
			$this->set(array('config' => ''));

		}
		else {
			$message = "AUTH_ERROR";
// 			return compact( 'message', 'id');
		}
		return compact( 'message', 'result', 'success', 'id');

	}

	public function deletePost(){
		$message = '';
		$userData = parent::getUserData();
		if (Auth::check ( 'default', $this->request )) {
			if ($this->request->data) {
				$data = $this->request->data;
				$id = $data['id'];
				$conditions = array(
						'_id' => $id
				);
				$userPhoto = Photos::find('first', array(
						'conditions' => $conditions
				));
				if($userPhoto->user_id == $userData['id']) {
				$userPhoto->delete();
				}
				else {
					$message = "AUTH_ERROR";
				}
			}
		}
		else {
			$message = "AUTH_ERROR";
		}
	}

	public function flagPost() {
		$message = '';
		$generatedUrl = '';
		$userData = parent::getUserData();
		$userPhotos = null;
		$id=null;
		$dbError = null;
		$result = null;
		$success = null;
		$document = null;
		$savePhotoList = array();
		$conditions = null;
		if (Auth::check ( 'default', $this->request )) {
			$flagSession = Session::read('Flags');
			if ($this->request->data) {
				$data = $this->request->data;
				$signature = $data['signature'];
				$conditions = array(
						'signature' => $signature
				);

				$userPhotos = Photos::find('first', array(
						'conditions' => $conditions
				));
				$flags = $userPhotos->flags;
				try {
					if($flags != null) {
						$count = array(
							'$set' =>  array('flags' => $flags + 1 )
						);
					}
					else {
						$count = array(
								'$set' =>  array('flags' => 1 )
						);
					}
					if($userPhotos->_id != $flagSession['photo_id']) {
						if($flags > 5) {
							$userPhotos->delete();
						}
						else {
						$result = Photos::update($count, $conditions);
						}
					}
					$id = $userPhotos->_id;
					Session::write('Flags', array(
					'photo_id' => $id,
					));
// 					$success = $result->save();

				} catch (Exception $e) {
					$dbError = $e->getMessage();
					$message = "Ooops, there was a problem with flagging this: ";
				}

			}
			$this->set(array('config' => ''));

		}
		else {
			$message = "AUTH_ERROR";
// 			return compact( 'message', 'id');
		}
		return compact( 'message', 'result', 'success', 'id');

	}

	public function getPic() {

		$photo = null;
		if ($this->request->query) {
		$photo = Photos::first($this->request->query['_id']);
		}
		return compact('photo');
	}

public function photoCallback() {

}

public function add(){
	if (Auth::check ( 'default', $this->request )) {
		$userData = parent::getUserData();
		$this->_render ['layout'] = 'photos';
			$conditions = array(
					'user_id' => $userData['id']
			);
			$userPics = Photos::find('first', array(
					'conditions' => $conditions
			));
			$count = count($userPics);
	}
	else {
		$this->redirect("/users");
	}
	return compact('count');
}



public function edit(){
	if (Auth::check ( 'default', $this->request )) {
// 		$this->response->headers('Access-Control-Allow-Origin', '*');
		$userData = parent::getUserData();
		$message = '';
		$this->_render ['layout'] = 'photos';
		$requestId = $this->request->id;
		$conditions = array(
				'_id' => $requestId
		);
		$post = Photos::find('first', array(
				'conditions' => $conditions
		));
	}
	else {
		$this->redirect("/users");
	}
	return compact('post', 'message');
}

	/**
	 * @return multitype: List of facing five Events
	 */
	public function search() {
		if ($this->request->query) {
			$data = $this->request->query;
			$events = Events::find ( 'all', array (
					'conditions' => array (
							'topic' => $data
					)
		) );
			$topic = $data['topic'];
		}
		return compact ( 'events', 'topic' );
	}

}

?>
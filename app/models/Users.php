<?php

namespace app\models;
use lithium\util\Validator;

use lithium\storage\Cache;
use lithium\util\Inflector;
use lithium\security\Auth;
use lithium\security\Password;
use MongoDate;
use app\extensions\helper\Fblogin;
use users\extensions\util\Util;
use \Exception;

class Users extends \lithium\data\Model {

}

/*    protected $_schema = array(
        '_id'		=>	array('type' => 'id'),
        'username'	=>	array('type' => 'string', 'null' => false),
        'password'	=>	array('type' => 'string', 'null' => false),
        'email'		=>	array('type' => 'string', 'null' => false),
        'location'		=>	array('type' => 'string', 'null' => false),
        'timezone'		=>	array('type' => 'string', 'null' => false),
        'updated'	=>	array('type' => 'datetime', 'null' => false),
        'created'	=>	array('type' => 'datetime', 'null' => false),
    );

    protected $_meta = array(
        'key' => '_id',
        'locked' => true
    );

    public $validates = array(
        'name' => 'Please enter a name',
        'password' => array(
            array('notEmpty', 'message' => 'Please enter a password'),
            array('alphaNumeric', 'message' => 'Please use only alphanumeric characters'),
            array('passwordVerification', 'message' => 'Passwords are not the same'),
        ),
        'username' => array(
            array('uniqueUsername', 'message' => 'This username is already taken'),
            array('notEmpty', 'message' => 'Please enter a username'),
            array('alphaNumeric', 'message' => 'Please use only alphanumeric characters'),
            array('lengthBetween', 'message' => 'Too long!', 'max'=>20),
        )
    );
}


Validator::add('passwordVerification', function($value, $rule, $options) {
    if(!isset($options['values']['password_verify']) || $value==$options['values']['password_verify']) return true;
    return false;
});


Validator::add('uniqueUsername', function($value, $rule, $options) {
    $conflicts = Users::count(array('username' => $value));
    if($conflicts) return false;
    return true;
});


Users::applyFilter('save', function($self, $params, $chain) {
    $entity = $params['entity'];
    $data 	= $params['data'];

    if($data) {
        $entity->set($data);
        if(isset($data['password']) && $data['password'] && isset($data['password_verify']) && $data['password_verify']) {
            $entity->password = \lithium\util\String::hash($data['password']);
            $entity->password2 = \lithium\util\String::hash($data['password_verify']);
        }
    }

    if(!$entity->id) $entity->created = new \MongoDate();
    $entity->updated = new \MongoDate();

    $params['entity'] = $entity;
    return $chain->next($self, $params, $chain);
});*/
?>
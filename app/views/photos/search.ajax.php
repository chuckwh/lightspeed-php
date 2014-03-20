<h3><?=$topic ?> Mixers</h3>
<table class="table table-condensed">
	<thead>
		<tr>
			<th>Mixer Name</th>
			<th>Mixer Topic</th>
			<th>Date <?php if(!($userSession['username']))?>(Eastern Standard Time)</th>
			<th>Age Range</th>
			<th>Action</th>
		</tr>
	</thead>
<?php
foreach($events as $event):
$mongoDate = $event->date;
$startingTime =   new DateTime($mongoDate, new DateTimeZone('UTC'));
$eventIsCurrent = $startingTime > $now;


if($userSession['username']) {
	$usrTimeZone = new DateTimeZone($userSession['timezone']);
}
else {
	//America/New_York timezone for non logged in users
	$usrTimeZone = new DateTimeZone('America/New_York');
}

//	$startingTime->setTimeZone($user->timezone);
$startingTime->setTimezone($usrTimeZone);
$dateStr = $startingTime->format('m/d/Y g:i A');
?>

	<tr>
		<td><?=$event->name ?></td>
		<td><?=$event->topic ?></td>
		<td><span class="label label-success">Now</span></td>
		<td><?=$event->ageRange ?></td>
		<td>
		<?=$this->form->create(null, array('method' => 'get', 'url' => '/videoweb/search', 'name' => 's_goto_event_form_'. $event->_id, 'id' => 's_goto_event_form_'. $event->_id, 'class' =>'no-decoration') );?>
			<?=$this->form->hidden('eventId', array('id' => 'eventId'.$event->_id, 'value' => $event->_id));?>
			<?php //NOTE: This is not a true REST call, it's really more a query that can be accessed via an api ?>
			 <?=$this->form->submit('Go to this mixer', array('id' => 's_submit_goto_event_'  . $event->_id, 'name' => 's_submit_goto_event_' . $event->_id,
			 	'itemprop' => 'url', 'content'=>'https://xxx.com/search?eventId' . $event->_id,	'class' =>'btn btn-link') );?>
		 <?=$this->form->end();?>
		</td>
	</tr>
<?php

endforeach;

?>

</table>
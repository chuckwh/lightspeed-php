<?php if($errorMessage == null) {
	$event = $signedUpEvent;
		if($event->eventType== 'instantEvent') {
	?>
	<div id="eventSignupSuccessMessage" class="red">You've successfully joined the <span class="dotted-ul"><?=$signedUpEvent['name']?></span> mixer. </div>

			<?=$this->form->create(null, array('url' => 'Videoweb::mixer', 'name' => 'goto_event_form_'. $event->_id, 'id' => 'goto_event_form_'. $event->_id, 'class' =>'no-decoration') );?>
				<?=$this->form->hidden('eventId', array('id' => 'eventId'.$event->_id, 'value' => $event->_id));?>
			<?=$this->form->submit('Connect to this mixer', array('id' => 'submit_goto_event_'  . $event->_id, 'name' => 'submit_goto_event_' . $event->_id, 'class' =>'btn btn-link nopad') );?>
		<?=$this->form->end();?>
	<?php
		} else {
		?>

<div id="eventSignupSuccessMessage" class="red">You've successfully joined the <span class="dotted-ul"><?=$event['name']?></span> mixer.
Check your Profile page's "Your Mixers" tab and look there to follow the mixers you have joined so far.</div>
<?php
		}
	}

else {
?>
<p class="red"><?= $errorMessage ?></p>
<?php }?>
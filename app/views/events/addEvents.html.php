

<div class="row"  data-layout="default" data-view="events/addEvents">
	<div class="col-md-12">
		<div class="container"  id="results"></div>
	</div>
</div>
<div class="row" id="boss">
	<div class="col-md-12">
		<div class="container">
			<?=$this->form->create(null, array('url' => '/events/addEvent', 'name' => 'create_event_form', 'id' => 'create_event_form', 'role' => 'form', 'class' => 'form-horizontal table-style')); // Echoes a <form> tag .  ?>
			 <?=$this->form->field(array('name' => 'Enter Your Name'), array(
			 	'id' => 'name', 
			 	'class' => 'input-lg', 
			 	'tabindex'=>'1',  
			 	'required' => 'required',
				'template' => '<div class="form-group t-row" {:wrap}>{:label}{:input}{:error}</div>'
			 	));
            ?>
			 <?=$this->form->field(array('location' => 'Enter Your Postal Code'), array(
			 	'id' => 'location', 
			 	'class' => 'input-lg', 
			 	'tabindex'=>'2',  
			 	'required' => 'required',
				'template' => '<div class="form-group t-row" {:wrap}>{:label}{:input}{:error}</div>'
			 	));
            ?>
			 <?=$this->form->field(array('date' => 'Enter Date For Your Event'), array(
			 	'id' => 'date', 
			 	'class' => 'input-lg', 
			 	'tabindex'=>'3',  
			 	'required' => 'required',
				'template' => '<div class="form-group t-row" {:wrap}>{:label}{:input}{:error}</div>'
			 	));
            ?>

				<?= $this->form->hidden('hostid', array(
					'id' => 'hostid', 
					'tabindex'=>'-1',
					'value' => $userSession['id']));
                ?>

			<?=$this->form->submit('Create Event', array(
				'id' => 'submit_event',
				'tabindex'=>'4',
				 'name' => 'submit_event', 
				 'class' => 'btn btn-large btn-primary') );?>
			<?=$this->form->end(); // Echoes a </form> tag & unbinds the form ?>
			<div id="addSMS">
	        	<div id="sms_messaging" class="red archivo">
		
		        </div>
		        <h4>Let someone know about this event</h4>
		        <p class="help">Enter your phone number and phone number of the person you want to send an SMS message to. 
		        Your carrier's normal texting charges will apply. </p>
		
		        <?=$this->form->create(null, array('url' => '/events/sendSMS','name' => 'phone_form', 'id' => 'phone_form')); ?>
		        <?php //request token functionality provided through the lithium PHP framework ?>
		    	<?=$this->security->requestToken(array('name'=>'cm-token', 'id'=>'cm-token')); ?>
		 
				<?=$this->form->field(
		 				array(
		 				'phone_field' => 'Please a phone number for the person you wish to send a message (sorry, U.S. only for now)'),
						array('id' => 'phone_field', 'class' => 'input-lg phone', 'required' => 'required'));
				?>
				<?=$this->form->field(
		 				array(
		 				'sms_message' => 'Enter your message here'),
						array('maxlength' => '140', 'rows' => '3', 'type'=>'textarea', 'placeholder'=>'limit: 140 characters', 'id' => 'sms_message', 'class' => 'input-lg form-control', 'required' => 'required'));
				?>
				<?=$this->form->submit('Send SMS', array('class' => 'btn btn-primary mt10', 'id' => 'send_phone')); ?>
				<?=$this->form->end(); ?>
				<div id="sms_status" class="mt10"></div>
			</div>
		</div>
	</div>
</div>



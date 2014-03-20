<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
$this->title ( DEFAULT_TITLE_TEXT );

?>

<?php //LOGIN AND REGISTRATION ?>
<?php //use data attributes to allow developers to identify layout and views in HTML source code ?>
<div class="row" data-layout="default" data-view="users/index">
  <div class="col-md-12">

	<div id="container" class="container" class="login-page">

        <div id="login">
            <div class="pull-right"><a href="#register" id="register_btn" class="btn btn-success">Register</a></div>
			<?php echo $this->form->create(null, array('url' => '/users/login', 'name' => 'login_form', 'id' => 'login_form')); ?>
			<?php echo $this->security->requestToken(array('name'=>'cm-token')); ?>
			<fieldset class="home">
			<legend class="archivo">Login to Cloudbook</legend>
				<div style="height:60px;">
				<?php echo $this->form->field(
							array('email' => 'Email'), array(
							'type' => 'email',
							'class' => 'form-control form-control-add-on',
							'autofocus' => 'true',
							'x-moz-errormessage' => 'Please enter a valid email address (youremail@youremail.com)',
							'template' =>
							'<div id="login_email_div" class="t-row">
									<div class="pull-left table-style ">
										{:label} <span class="glyphicon glyphicon-envelope"></span>
									</div>

									<div class="pull-left table-style " style="width:85%">
										{:input}{:error}
									</div>
							</div>'
)
							); ?>
					</div>
					<div  style="height:60px;">
					<?php echo $this->form->field(
							array('password' => 'Password'), array(
							'type' => 'password',
							'class' => 'form-control form-control-add-on',
							'template' =>
							'<div id="login_password_div"  class="t-row">
									<div class="pull-left table-style ">
										{:label} <span class="glyphicon glyphicon-lock"></span>
									</div>

									<div class="pull-left table-style "  style="width:85%">
										{:input}{:error}
									</div>
							</div>'
							)
							); ?>

					</div>
				<div class="mt10">
					<?php echo $this->form->submit('Login', array('class' => 'btn btn-primary')); ?>

				</div>
			</fieldset>
			<?php echo $this->form->end(); ?>



        </div>

        <div id="registration" class="registration-page" <?php /* if ($home === true) { */?> class="headerWrapper" <?php /*  } */?>>
        	<div class="fr"><a href="#login" id="register_btn" class="btn btn-success archivo">Login</a></div>
			<?php echo $this->form->create(null, array('url' => '/users/register',null, 'class' => 'login-page-registration',  'id' => 'register_form')); ?>
			<?php echo $this->security->requestToken(array('name'=>'cm-token')); ?>
				<fieldset class="home">
				<legend class="archivo">
					Sign up with Cloudbook
				</legend>

						<?php echo $this->form->field(  'username', array('class' =>'form-control form-control-add-on', 'id'=>'registeras', 'type' => 'text', 'label'=>'Please pick a screen name', 'placeholder' => 'Please use only numbers and letters')); ?>
						<?php echo $this->form->field('email', array('class' => 'form-control form-control-add-on focus-color','id'=>'email', 'type' => 'email', 'label'=>'Your Email (Nobody else will ever see this)')); ?>
						<?php echo $this->form->field('password', array('class' =>'form-control form-control-add-on', 'type' => 'password', 'label'=>'Password','id' => 'txtPassword',  'template' => '<div id="txtPasswordDiv"{:wrap}>{:label}{:input}{:error}</div>', 'maxlength' => '16')); ?>
						<?php echo $this->form->field('password_verify', array('class' =>'form-control form-control-add-on', 'type' => 'password', 'label'=>'Please retype your password')); ?>
						<?php echo $this->form->hidden('timezone', array('id' => 'timezone', 'type' => 'hidden')); ?>
						<?php echo $this->form->field('location', array('class' =>'form-control form-control-add-on focus-color', 'id'=>'location', 'type' => 'text', 'label'=>'Zip Code', 'template' => '<div class="" {:wrap}>{:label}{:input}{:error}</div>', 'maxlength' => '5')); ?>


					<div class="mt10">
						<button class="btn btn-primary" id="register_btn_init">Sign up</button>


					</div>
			</fieldset>
			<?php echo $this->form->end(); ?>
       	 </div>
        <div class="mt10 alert hidden" id="results_alert">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong id="results_head"></strong>
            <div id="results"></div>
            <div id="where" class="hidden"><a href="/events/searchEvents">Search For Events</a> | <a href="/events/addEvents">Add an Event</a></div>
        </div>

	</div>


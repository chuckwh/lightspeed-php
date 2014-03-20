$('.cm-tip').tooltip();




$(document).ready(function() {

	$( "#container" ).hide();
	var newNode = null;
	var  url = '/profile/searchAll';

/* Send the data using post and put the results in a div
 * the post would look like this: topic="topic_entered_into_search_tags_input"
 *
 */


$.get( url,
  function( initData ) {

    //The content div lives in searchFacingFivess.ajax.php
    //set a var to find the div



     //then post that content from the ajax response into the search_result div
	newNode = 	 $( '#search_result').append(initData);



  }, 'html'
);

	$('.close-btn-30').on('click', function(){
		$( "#register_container" ).hide();
		$( "#primary" ).show();
	});
	if(location.hash==='#register')  {
		$( "#login" ).hide();
		$( "#registration" ).show();
	}
	else {
		$( "#register_container" ).hide();
		$( "#login" ).hide();
		//this is a hack. The home page defaults to hiding login container
		//but on login page we want it to show, so since this is after
		//the #login hide it will override.
		$('.login-page').show();
		$( "#registration" ).hide();
	}
	$(document).on('click', '#register_btn, #register_btn_sm', function(){
		$( "#blurbpage" ).hide();
		$( "#search" ).hide();
		$( "#container" ).show();
		$( "#login" ).hide();
		$( "#registration" ).show();
	});
	$(document).on('click',  '.log-in', function(){
		$( "#blurbpage" ).hide();
		$( "#search" ).hide();
		$( "#container" ).show();
		$( "#login" ).show();
		$( "#registration" ).hide();
	});


 $(document).on('click', 'a.page', function() {

	var n = 'empty';
	$(this).attr('href', '#');
	$(newNode).remove();
	$.get(url + '/page/' + $(this).attr('data-page'), function(data) {
		newData = data;
		$( '#search_result').html(data);
	}, 'html').done(function(){
		$('#results_table').append('<tbody id="search_result"></tbody>');
		$( '#search_result').focus();
		$( '#search_result').html(newData);
		}).fail(function() { alert("error"); });

});


	$('.change_tz').on('click', function(){
		$('#change_tz_modal').modal();
	});
	$('.focus-color').focus(function() {
		$(this).css('backgroundColor', '#ffffff');
	});


	$('input#Email').focus();
	$("#txtPassword").passStrength({
        preferedLength: 14,
		minLength: 6,
		messages: ".pwd-help",
		appendTo: '#test_result_head"',
	      rules                    :{
	          preferedLength         :14,//optional
	          minNums                :2,//optional
	          minLowerChars          :2,//optional
	          minUpperChars          :1,//optional
	          minSymbols             :1,//optional
	          hasLowerAndUpperChars  :false,//optional
	          hasNumbersAndChars     :false,//optional
	          hasNumbersAndSymbols   :false,//optional
	          hasCharsAndSymbols     :false,//optional
	          hasRepetitionInPassword:0//optional
	        },
        hasLowerAndUpperChars  :true,//optional
        hasNumbersAndChars     :true,//optional
        hasNumbersAndSymbols   :true,//optional
        hasCharsAndSymbols     :true,//optional
        hasRepetitionInPassword:0, //optional
        btn: '#register_btn_init'

	}, function(){
        $(".pwd-help").html("Excellent password! <a target='_blank' style='font-decoration:underline;' href='http://www.amazon.com/Cryptonomicon-Neal-Stephenson/dp/0060512806'>Neal Stephenson</a> would be proud.");
      });
	 //Set default values for future Ajax requests.
	 //TODO find out if false cache is default in jquery and if so, delete this
	  $.ajaxSetup ({
	        cache: false
	    });





  $('h2.markCopyHead').click(function(event) {
		$( "h2.markCopyHead" ).hide();
		$( "div.markCopyContent" ).show();
		$( "div.markCopy").css('margin-top', '10px');
		$( "div.markCopy").css('width', '720px');
	  });

	$('#loginButton').click(function() {
		$( "#login" ).show();
		$( "#registrationH2" ).show();
		$( "#loginH2" ).hide();
		$( "#registration" ).hide();
		$( "#homeContent" ).show();
	  	$( "div.homeInfo").removeClass('nbg');
	  	$( "#registrationMessage" ).hide();
	});


//get timezone and time
		var timezone = jstz.determine();
	z
	$('#timezone').attr('value', timezone.name());

	  /* FacingFive Event creator: attaches a submit handler to the form */
	  $("#login_form").on('submit', function(event) {

	    /* stop form from submitting normally */
	    event.preventDefault();

	    /* get some values from elements on the page: */
	    var login_form = $( this ),
	        email = login_form.find( 'input[name="email"]' ).val(),
	        password = login_form.find( 'input[name="password"]' ).val(),
	        url = '/users/login';

	    /* Send the data using post and put the results in a div
	     * the post parameters are of "topic="input[name='topic']".val() etc
	     */
	    $.post( url, {  email:email, password:password},
	      function( data, textStatus ) {
	    	if(textStatus === 'error') {
				 $( "#registration_message" ).text('There was an error communicating with our server. Please try again');
				 $( "#registration_message_sm_device_text" ).text('There was an error communicating with our server. Please try again');
	    	} else {
		         var content = data.message;
		         //then post that content from the ajax response into the result div


				  /* If data sends a positive response that log in is successful... */
				  if (data.notification === 'notice') {

				 /*  this page reload should send the user to profile page.
				 */

					 window.location.href = '/profile/profile';
				  }
				  else if (data.notification === 'gotoevent') {
					  window.location.href = '/videoweb/search?eventId=' + data.eventurl;
				  }
				  else if (data.notification === 'error') {
					  window.scrollTo(0,0);
					  $("#registration_icon").attr('class', 'glyphicon glyphicon-warning-sign  tbl-cell');
					  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
					  $( "#registration_message_text" ).text(content);
					  $( "#registration_message_sm_device").attr('class', 'registration-msg-sm-device registration-msg-error ');
					  $( "#registration_message_sm_device_text" ).text(content);
					  $("input#Email").css('background-color', 'rgb(255, 241, 0)');
					//  $( "#messageIcon" ).attr('class', 'errorIcon');
				  }
	    	}
	      }, 'json'
	    );


	  });
    $('#userAgreementModal').on('shown.bs.modal', function() {
    	$('#agreement_modal_body').load('legal/legalagree');
    });

    $('#pwd_help').popover();

	  $('#register_btn_init').on('click', function(event) {
		    /* stop form from submitting normally */


		    event.preventDefault();
		    var zip = $('#location').val();
		    var len = zip.length;
		    var ints = '0123456789';
		    var letterNums = false;
		    var count = false;
		    var readyToRegister = false;
		    for(var i=0; i<5; i++) {
		    	if (ints.indexOf(zip.charAt(i)) < 0 ){
		    		letterNums = false;
		    	}
		    	else {
		    		letterNums = true;
		    	}

		    }
	    	if (len !=5) {
	    		count = false;
		    	}
	    	else {
	    		count = true;
	    	}

	    	if ( (letterNums === true) && (count === true) ) {
//	    		 $('#userAgreementModal').modal({show: true});
	    		readyToRegister = true;
	    	}

	    	else {
	    		if (letterNums === false) {
	    			var ziperror = "Please enter numbers only into the zip code box";
	    			$("#registration_icon").attr('class', 'glyphicon glyphicon-warning-sign  tbl-cell');
	    			window.scrollTo(0,0);
			  		  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
					  $( "#registration_message_text" ).text( ziperror );
					  $( "#registration_message_sm_device").attr('class', 'registration-msg-sm-device registration-msg-error ');
					  $( "#registration_message_sm_device_text" ).text(ziperror);
					  $('#location').css('backgroundColor', 'yellow');
	    		}
	    		else if (count === false) {
	    			var ziperror2 = "Please enter a 5 digit zip code";
	    			$("#registration_icon").attr('class', 'glyphicon glyphicon-warning-sign  tbl-cell');
	    			window.scrollTo(0,0);
			  		  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
					  $( "#registration_message_text" ).text( ziperror2 );
					  $( "#registration_message_sm_device").attr('class', 'registration-msg-sm-device registration-msg-error ');
					  $( "#registration_message_sm_device_text" ).text(ziperror2);
					  $('#location').css('backgroundColor', 'yellow');
	    		}
	    	}
	    	if(readyToRegister) {
	    		$("#register_form").submit();
	    	}


	     });



	  $("#register_form").on('submit', function(event) {
		    event.preventDefault();
		    $('#register_btn_init').text('Processing...');
		    $('#register_btn_init').attr('class', 'btn disabled cb block');
			    /* get some values from elements on the page: */
			    var register_form = $( this ),
			    	cmToken = register_form.find( 'input[name="cm-token"]' ).val(),
			        username = register_form.find( 'input[name="username"]' ).val(),
			        email = register_form.find( 'input[name="email"]' ).val(),
			        password = register_form.find( 'input[name="password"]' ).val(),
			        password_verify = register_form.find( 'input[name="password_verify"]' ).val(),
			        location = register_form.find( 'input[name="location"]' ).val(),
			        age = register_form.find( 'input[name="age"]' ).val(),
			        gender = register_form.find( 'input[name="gender"]:checked' ).val(),
			        timezone = register_form.find( 'input[name="timezone"]' ).val(),
			        oauth_token = register_form.find( 'input[name="oauth_token"]' ).val(),
			        url = '/users/register';

			    /* Send the data using post and put the results in a div
			     * the post parameters are of "topic="input[name='topic']".val() etc
			     * TODO add field labels for fields not filled out
			     */

			    $.post( url, {cmToken: cmToken, username: username, email:email, password:password, password_verify:password_verify, location:location, age:age, timezone:timezone, gender:gender, oauth_token:oauth_token },
			      function( data ) {
			         var content = data.message;
			         //then post that content from the ajax response into the result div
			          $( "#reg_success_modal_message" ).html( content );
			          $('#reg_success_modal').modal('show');
			          $( "#registration_message_sm_device_text" ).html( content );
					  if(data.notification === 'notice') {

						  $( "div#login").show();
						  $( "div#registration").hide();
						  $('#register_btn_init').text('Registration Successful');
						  $('#register_btn_init').attr('class', 'btn disabled cb block');
					  }
					  else if (data.notification === 'error') {
						  $("#registration_icon").attr('class', 'glyphicon glyphicon-warning-sign tbl-cell');
						  window.scrollTo(0,0);
						  $( "#registration_message").attr('class', 'registration-msg registration-msg-error  tbl-cell');
						  $( "#registration_message_text" ).html( content );
						  $( "#registration_message_sm_device").attr('class', 'registration-msg-sm-device registration-msg-error  tbl-cell');
						  $( "#registration_message_sm_device_text" ).html( content );
						  $('#' + data.fieldName).css('backgroundColor', 'yellow');
						  $('#register_btn_init').text('Waiting for password...');
						  $('#register_btn_init').attr('class', 'btn disabled cb block');
					  }

			      }, 'json'
			    );


		  });


	  $('input#PasswordVerify').blur(function(event) {
		  if($(this).val() !== $('#txtPassword').val()){
			  $( "#registration_message").attr('class', 'registration-msg registration-msg-error    tbl-cell');
			  window.scrollTo(0,0);
			  $("#registration_icon").attr('class', 'glyphicon glyphicon-warning-sign tbl-cell');
			  $( "#registration_message_text" ).html( 'Your passwords are not matching. Please try again.' );
			  $( "#registration_message_sm_device").attr('class', 'registration-msg-sm-device registration-msg-error  tbl-cell');
			  $( "#registration_message_sm_device_text" ).html('Your passwords are not matching. Please try again.' );

			  $(this).val('');
		  }
		  else 	if($(this).val() === $('#txtPassword').val()){
			  $( "#registration_message").attr('class', 'registration-msg registration-msg-success     tbl-cell');
			  $("#registration_icon").attr('class', 'glyphicon glyphicon-ok tbl-cell');
			  $( "#registration_message_text" ).html( 'Your passwords match.' );
			  $( "#registration_message_sm_device_text" ).html( 'Your passwords match.' );
		  }
	  });

	  $('input#age').blur(function(event) {
		  if($(this).val() < 18){
			  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
			  window.scrollTo(0,0);
			  $("#registration_icon").attr('class', 'glyphicon glyphicon-warning-sign tbl-cell');
			  $( "#registration_message_text" ).html( 'You must be 18 or older to join.' );
			  $( "#registration_message_sm_device").attr('class', 'registration-msg-sm-device registration-msg-error  ');
			  $( "#registration_message_sm_device_text" ).html( 'You must be 18 or older to join.' );
			  $(this).val('');
		  }
	  });

	    $('#registeras').blur(function(event) {
		    username = $('#registeras').val(),
		    url = '/users/checkForRegisteredNames';

		    /* Send the data using post and put the results in a div */
		    $.post( url, { username: username},
		      function( data ) {
			      if (data.notification === "error") {
			         var content = data.message;
			         //then post that content from the ajax response into placeholder
			         $( "#registeras" ).attr('value', '');
			          $( "#registeras" ).attr('placeholder', content );
				  	  $( "#registeras" ).attr('class', 'form-control has-error');

			      }
		      }, 'json'
		    );
	    });
	    $('#registeras').focus(function(event) {
	    	$( "#registeras" ).attr('class', 'form-control');
	    	$( "#registeras" ).attr('placeholder', 'Please use only numbers and letters' );
	    });
	    $('#email').focus(function(event) {
	    	$( "#email" ).attr('class', 'form-control');
	    	$( "#email" ).attr('placeholder', 'Please use a valid email address' );
	    	$(this).css('backgroundColor', '#ffffff');
	    });

	    //TODO we don't need a second function here, just a better encapsulation of the first one. This is just a quick and dirty thing
	    $('#email').blur(function(event) {
		    email = $('#email').val(),
		    url = '/users/checkForEmail';

		    /* Send the data using post and put the results in a div */
		    $.post( url, { email: email},
		      function( data ) {
			      if (data.notification === "error") {
				         var content = data.message;
				         //then post that content from the ajax response into placeholder
				         $( "#email" ).attr('value', '');
				         $( "#email" ).attr('placeholder', content );
					  	 $( "#email" ).attr('class', 'form-control has-error');
				      }
		      }, 'json'
		    );
	    });

});

$(window).load(function(){
	$('body').removeClass('hidden');

});



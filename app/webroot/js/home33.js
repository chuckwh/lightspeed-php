$('#pageLoading').show();

$(document).ready(function() {
	$('.change_tz').on('click', function(){
		$('#change_tz_modal').modal();
	});
	$('.focus-color').focus(function() {
		$(this).css('backgroundColor', '#ffffff');
	});
	if(location.hash==='#register')  {
		$( "#login" ).hide();
		$( "#registration" ).show();
		 $( "#registration" ).removeClass('hidden');
		$( "#registrationH2" ).hide();
		$( "#loginH2" ).show();
	  	 $( "#homeContent" ).hide();
	  	 $( "div.homeInfo").css('position', 'relative');
	  	 $( "div.homeInfo").css('z-index', '1000');
	  	 $( "div.homeInfo").css('padding', '0');
	  	 $( "div.homeInfo").addClass('nbg');
	  	 $( "div.homeInfo").addClass('nb');
	  	 $( "div.homeInfo").addClass('nbxshadow');
	}

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

  $('#registrationButton').click(function(event) {
	$( "#login" ).hide();
	$( "#registration" ).show();
	 $( "#registration" ).removeClass('hidden');
	$( "#registrationH2" ).hide();
	$( "#loginH2" ).show();
  	 $( "#homeContent" ).hide();
  	 $( "div.homeInfo").css('position', 'relative');
  	 $( "div.homeInfo").css('z-index', '1000');
  	 $( "div.homeInfo").css('padding', '0');
  	 $( "div.homeInfo").addClass('nbg');
  	 $( "div.homeInfo").addClass('nb');
  	 $( "div.homeInfo").addClass('nbxshadow');
//   	$( "#registrationMessage" ).hide();
  });


//  $('#registrationButton').click(function(event) {
//	  $( "#beta_test_dialog" ).dialog( "open" );
//  });


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
		var localzone = function() {
			var currentTime = new Date();
			var hours = currentTime.getHours();
			var minutes = currentTime.getMinutes();
			var time = null;
			  var ampm = hours >= 12 ? 'pm' : 'am';
			  hours = hours % 12;
			  hours = hours ? hours : 12; // the hour '0' should be '12'
			  minutes = minutes < 10 ? '0'+minutes : minutes;
			  var strTime = hours + ':' + minutes + ' ' + ampm;
			  return strTime;
		};

	$('.getTime').html(localzone() + " " + timezone.name());
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
					  $("#login_icon").attr('class', 'icon-exclamation-sign tbl-cell');
					  $( "#login_message").attr('class', 'registration-msg registration-msg-error');
					  $( "#login_message_text" ).text(content);
					  $("input#Email").css('background-color', 'rgb(255, 241, 0)');
					//  $( "#messageIcon" ).attr('class', 'errorIcon');
				  }
	    	}
	      }, 'json'
	    );


	  });
    $('#userAgreementModal').on('shown', function() {
    	$('#user_agreement_h3').focus();
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
			  		  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
					  $( "#registration_message_text" ).html( 'Please enter numbers only into the zip code box' );
					  $('#location').css('backgroundColor', 'yellow');
	    		}
	    		else if (count === false) {
			  		  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
					  $( "#registration_message_text" ).html( 'Please enter a 5 digit zip code' );
					  $('#location').css('backgroundColor', 'yellow');
	    		}
	    	}
	    	if(readyToRegister) {
	    		$("#register_form").submit();
	    	}


	     });

	  $('#legal_agree_link').on('click', function() {
		  $('#userAgreementModal').modal({show: true});
	  });
	  


	  /* @search form */
    $('#search').on('click','#submit_local_search', function(event) {
    	event.preventDefault();

    	var search_form = $( '#search_local' );

    	locationInput = search_form.find( 'input[name="locationInput"]' ).val(),

   
	    url = '/events/searchLocal';

	    /* 
	     * Send the data using post and inject the results in a div
	     */

	    var $target = $(event.target);
	    var $page = null;
	    var obj = {};
	    if ( $target.is('a') ) {
	        $page = $target.attr('data-page');
	        obj = { location: locationInput, page: $page};
	      }
	    else {
	    	obj = { location: locationInput};
	    }
	    $.post( url, obj,
	      function( data ) {
	    	

	        //The content div lives in searchLocalEvents.ajax.php
	        //set a var to find the div

	    	 var content = $( data ).find( '#searchcontent' );

	         //then inject that content from the ajax response into the search_result div

	          $( "#search_result" ).html(data);

	
	      }, 'html' //html makes it possible to read the DOM after the ajax call DEBUG: look for this as being missing in ajax calls where dom manipulation fails.
	    );
    });
    
    

	  $("#register_form").on('submit', function(event) {
		    event.preventDefault();

			    /* get some values from elements on the page: */
			    var register_form = $( this ),
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

			    $.post( url, { username: username, email:email, password:password, password_verify:password_verify, location:location, age:age, timezone:timezone, gender:gender, oauth_token:oauth_token },
			      function( data ) {
			         var content = data.message;
			         //then post that content from the ajax response into the result div
			          $( "#registration_message_text" ).html( content );
					  if(data.notification === 'notice') {
						  $( "#login_message").attr('class', 'registration-msg');
						  $( "#login_message_text" ).html( content );
						  $("#login_icon").attr('class', 'icon-ok tbl-cell');
						  $( "#login_message_text").attr('class', 'registration-msg-success pd6 tbl-cell');
						  $( "div#login").removeClass('hidden');
						  $( "div#login").show();
						  $( "div#registration").addClass('hidden');
					  }
					  else if (data.notification === 'error') {
						  $("#registration_icon").attr('class', 'icon-exclamation-sign tbl-cell');
						  $( "#registration_message").attr('class', 'registration-msg registration-msg-error pd6 tbl-cell');
						  $( "#registration_message_text" ).html( content );
						  $('#' + data.fieldName).css('backgroundColor', 'yellow');
					  }

			      }, 'json'
			    );

		  });


	  $('input#PasswordVerify').blur(function(event) {
		  if($(this).val() !== $('#txtPassword').val()){
			  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
			  $("#registration_icon").attr('class', 'icon-exclamation-sign tbl-cell');
			  $( "#registration_message_text" ).html( 'Your passwords are not matching. Please try again.' );
			  $(this).val('');
		  }
		  else 	if($(this).val() === $('#txtPassword').val()){
			  $( "#registration_message").attr('class', 'registration-msg registration-msg-success ');
			  $("#registration_icon").attr('class', 'icon-ok-sign tbl-cell');
			  $( "#registration_message_text" ).html( 'Your passwords match.' );
		  }
	  });

	  $('input#age').blur(function(event) {
		  if($(this).val() < 18){
			  $( "#registration_message").attr('class', 'registration-msg registration-msg-error ');
			  $("#registration_icon").attr('class', 'icon-exclamation-sign pd6 tbl-cell');
			  $( "#registration_message_text" ).html( 'You must be 18 or older to join.' );
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
				  	  $( "#registeras" ).attr('class', 'input-xlarge reg-error');

			      }
		      }, 'json'
		    );
	    });
	    $('#registeras').focus(function(event) {
	    	$( "#registeras" ).attr('class', 'input-xlarge');
	    	$( "#registeras" ).attr('placeholder', 'Please use only numbers and letters' );
	    });
	    $('#email').focus(function(event) {
	    	$( "#email" ).attr('class', 'input-xlarge');
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
					  	 $( "#email" ).attr('class', 'input-xlarge reg-error');
				      }
		      }, 'json'
		    );
	    });

});

$(window).load(function(){
	$('#home_header').removeClass('hidden');
	$('#homeContent').removeClass('hidden');
	$('#pageLoading').hide();
});

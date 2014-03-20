/*
	 * bootstrap-filestyle
	 * http://dev.tudosobreweb.com.br/bootstrap-filestyle/
	 *
	 * Copyright (c) 2013 Markus Vinicius da Silva Lima
	 * Version 1.0.3
	 * Licensed under the MIT license.
	 */
	(function(b){var c=function(d,e){this.options=e;this.$elementFilestyle=[];this.$element=b(d)};c.prototype={clear:function(){this.$element.val("");this.$elementFilestyle.find(":text").val("")},destroy:function(){this.$element.removeAttr("style").removeData("filestyle").val("");this.$elementFilestyle.remove()},icon:function(d){if(d===true){if(!this.options.icon){this.options.icon=true;this.$elementFilestyle.find("label").prepend(this.htmlIcon())}}else{if(d===false){if(this.options.icon){this.options.icon=false;this.$elementFilestyle.find("i").remove()}}else{return this.options.icon}}},input:function(d){if(d===true){if(!this.options.input){this.options.input=true;this.$elementFilestyle.prepend(this.htmlInput());var e="",f=[];if(this.$element[0].files===undefined){f[0]={name:this.$element[0].value}}else{f=this.$element[0].files}for(var g=0;g<f.length;g++){e+=f[g].name.split("\\").pop()+", "}if(e!==""){this.$elementFilestyle.find(":text").val(e.replace(/\, $/g,""))}}}else{if(d===false){if(this.options.input){this.options.input=false;this.$elementFilestyle.find(":text").remove()}}else{return this.options.input}}},buttonText:function(d){if(d!==undefined){this.options.buttonText=d;this.$elementFilestyle.find("label span").html(this.options.buttonText)}else{return this.options.buttonText}},classButton:function(d){if(d!==undefined){this.options.classButton=d;this.$elementFilestyle.find("label").attr({"class":this.options.classButton});if(this.options.classButton.search(/btn-inverse|btn-primary|btn-danger|btn-warning|btn-success/i)!==-1){this.$elementFilestyle.find("label i").addClass("icon-white")}else{this.$elementFilestyle.find("label i").removeClass("icon-white")}}else{return this.options.classButton}},classIcon:function(d){if(d!==undefined){this.options.classIcon=d;if(this.options.classButton.search(/btn-inverse|btn-primary|btn-danger|btn-warning|btn-success/i)!==-1){this.$elementFilestyle.find("label").find("i").attr({"class":"icon-white "+this.options.classIcon})}else{this.$elementFilestyle.find("label").find("i").attr({"class":this.options.classIcon})}}else{return this.options.classIcon}},classInput:function(d){if(d!==undefined){this.options.classInput=d;this.$elementFilestyle.find(":text").addClass(this.options.classInput)}else{return this.options.classInput}},htmlIcon:function(){if(this.options.icon){var d="";if(this.options.classButton.search(/btn-inverse|btn-primary|btn-danger|btn-warning|btn-success/i)!==-1){d=" icon-white "}return'<i class="'+d+this.options.classIcon+'"></i> '}else{return""}},htmlInput:function(){if(this.options.input){return'<input type="text" class="'+this.options.classInput+'" disabled> '}else{return""}},constructor:function(){var f=this,d="",g=this.$element.attr("id"),e=[];if(g===""||!g){g="filestyle-"+b(".bootstrap-filestyle").length;this.$element.attr({id:g})}d=this.htmlInput()+'<label for="'+g+'" class="'+this.options.classButton+'">'+this.htmlIcon()+"<span>"+this.options.buttonText+"</span></label>";this.$elementFilestyle=b('<div class="bootstrap-filestyle" style="display: inline;">'+d+"</div>");this.$element.css({position:"fixed",left:"-500px"}).after(this.$elementFilestyle);this.$element.change(function(){var h="";if(this.files===undefined){e[0]={name:this.value}}else{e=this.files}for(var j=0;j<e.length;j++){h+=e[j].name.split("\\").pop()+", "}if(h!==""){f.$elementFilestyle.find(":text").val(h.replace(/\, $/g,""))}});if(window.navigator.userAgent.search(/firefox/i)>-1){this.$elementFilestyle.find("label").click(function(){f.$element.click();return false})}}};var a=b.fn.filestyle;b.fn.filestyle=function(e,d){var g="",f=this.each(function(){if(b(this).attr("type")==="file"){var i=b(this),j=i.data("filestyle"),h=b.extend({},b.fn.filestyle.defaults,e,typeof e==="object"&&e);if(!j){i.data("filestyle",(j=new c(this,h)));j.constructor()}if(typeof e==="string"){g=j[e](d)}}});if(typeof g!==undefined){return g}else{return f}};b.fn.filestyle.defaults={buttonText:"Choose file",input:true,icon:true,classButton:"btn",classInput:"input-large",classIcon:"icon-folder-open"};b.fn.filestyle.noConflict=function(){b.fn.filestyle=a;return this};b(".filestyle").each(function(){var e=b(this),d={buttonText:e.attr("data-buttonText"),input:e.attr("data-input")==="false"?false:true,icon:e.attr("data-icon")==="false"?false:true,classButton:e.attr("data-classButton"),classInput:e.attr("data-classInput"),classIcon:e.attr("data-classIcon")};e.filestyle(d)})})(window.jQuery);
	$(document).on('ready', function() {
		$('#post_results').hide();
		$('#add_from_file_btn').hide();
		$('#url_chooser').hide();
		$('#progress_bar').hide();
	});

	$('#upload_url_btn').on('click', function(event) {
		event.preventDefault();
		$('#file').removeClass('filestyle');
		var val = $('#url_field').val();
		$('#credit').val(val);
		$('#file').fileupload('option', 'formData').file = val;
		$('#file').fileupload('add', { files: [ val ] });


	});
//Not used in this app
	$('#get_images_btn').on('click', function(event) {
		event.preventDefault();
		var val = $('#get_images_field').val() + '&callback=?';
		// Get the url
		$.getJSON(val,
		function(data){

		  // For each image
		  $.each(data.items, function(i,item){
		    $.getImageData({
		      url: item.media.m, // This is the URL of the retrieved image
		      server: '/getImageData.php',
		      success: analyseAndDraw, // Run this function when image has been fetched
		      error: function(xhr, text_status){
		        // Handle your error here
		      }
		    });
		  });

		});


//	 	$('#url_field').cloudinary_fileupload();
//	 	$('#file').val(val);
	});
	$('#add_from_web_btn').on('click', function(event){
		event.preventDefault();
		$(this).hide();
		$('#file_chooser').hide();
		$('#add_from_file_btn').show();
		$('#url_chooser').show();
	});
	$('#add_from_file_btn').on('click', function(event){
		event.preventDefault();
		$(this).hide();
		$('#file_chooser').show();
		$('#add_from_web_btn').show();
		$('#url_chooser').hide();
	});
	$('#addmore_btn').on('click', function(){
		$('#post_results').show();
		$('#photo_form').hide();
		//clear the form
		$('#photo_form').find("input[type=text], textarea").val("");
	});


	$('.cloudinary-fileupload').bind('fileuploadprogress', function(e, data) {
		$('#progress_bar').show();
		$('.post-submit-btn').attr('disabled', true);
		$('#progress_indicator').text('Image uploading...');
		  $('.bar').css('width', Math.round((data.loaded * 100.0) / data.total) + '%');
		});
	
	
	
	$('.cloudinary-fileupload').bind('cloudinarydone', function(e, data) {
		$('.preview').html(
		       $.cloudinary.image(data.result.public_id,
		           { format: data.result.format, version: data.result.version,
		             crop: 'scale', width: 200 }));
	    			var secureUrl = data.result.secure_url,
	    			public_id = data.result.public_id,
	    			version = data.result.version,
	    			format = data.result.format,
	    			signature = data.result.signature,
	    			width = data.result.width,
	    			height = data.result.height;
	    			$('.post-submit-btn').attr('disabled', false);
	    			$('.post-submit-btn').attr('data-secure-url', secureUrl);
	    			$('.post-submit-btn').attr('data-public-id', public_id);
	    			$('.post-submit-btn').attr('data-version', version);
	    			$('.post-submit-btn').attr('data-format', format);
	    			$('.post-submit-btn').attr('data-signature',signature );
	    			$('.post-submit-btn').attr('data-width', width);
	    			$('.post-submit-btn').attr('data-height', height);
	    			$('#progress_indicator').text('Image Uploaded');
	    			$('.post-submit-btn').attr('disabled', false);

		  return true;
		});

		$('#do_post').on('click', function(event) {
			event.preventDefault;
			var secureUrl = $('#do_post').attr('data-secure-url'),
			public_id = $('#do_post').attr('data-public-id'),
			version = $('#do_post').attr('data-version'),
			format = $('#do_post').attr('data-format'),
			signature = $('#do_post').attr('data-signature'),
			width = $('#do_post').attr('data-width'),
			height = $('#do_post').attr('data-height'),
			credit = $('#credit').val(),
			status = $('#status').val(),
			title = $('#title').val(),
			tags = $('#tags').val();
			$.post('/photos/addPhotoUrl', {
				secureUrl:secureUrl,
				public_id: public_id,
				version: version,
				format: format,
				signature: signature,
				width: width,
				height: height,
				title:title,
				tags:tags,
				status:status,
				credit:credit
				}, function(data) {
				$('#post_results_title').text(data.newData.title);
				$('#post_results_tags').text(data.newData.tags);
				$('#post_results_status').text(data.newData.status);
				$('#post_results_credit').text(data.newData.credit);
				$('#post_results').show();
				$('#photo_form').hide();
				$('#view_post').attr('href', '/photos/view/' + data.newData._id);
			}, 'json');
			return false;

		});

		$('#edit_btn').on('click', function(){
			$('#post_results').hide();
			$('#photo_form').show();
		});
		/*
		 	TODO: this is not currently implemented on the view page. If you want to implement it, 
		 	combine the function with the anonymous function for "do_post", perhaps using a callback that 
		 	fetches the shared field names
		*/
		$('#update_post').on('click', function(event) {
			event.preventDefault;
			var secureUrl = $('#update_post').attr('data-secure-url'),
			id =$('#update_post').attr('data-id'),
			public_id = $('#update_post').attr('data-public-id'),
			version = $('#update_post').attr('data-version'),
			format = $('#update_post').attr('data-format'),
			signature = $('#update_post').attr('data-signature'),
			width = $('#update_post').attr('data-width'),
			height = $('#update_post').attr('data-height'),
			credit = $('#credit').val(),
			status = $('#status').val(),
			title = $('#title').val(),
			tags = $('#tags').val();
			$.post('/photos/updatePhotoUrl', {
				secureUrl:secureUrl,
				id:id,
				public_id: public_id,
				version: version,
				format: format,
				signature: signature,
				width: width,
				height: height,
				title:title,
				tags:tags,
				status:status,
				credit:credit
				}, function(data) {
				$('#post_results_title').text(data.newData.title);
				$('#post_results_tags').text(data.newData.tags);
				$('#post_results_status').text(data.newData.status);
				$('#post_results_credit').text(data.newData.credit);
				$('#updated_pic').attr('src', data.newData.url);
				$('#post_results').show();
				$('#photo_form').hide();
				$('#view_post').attr('href', '/photos/view/' + data.newData._id)
			}, 'json');
			return false;

		});

		// This creates a canvas, draws the image on to it, gets the average colour of the image and then adds
		// the image to the DOM with the average colour as a background colour to its container
		function analyseAndDraw(image) {
		  // Create the canvas and context
		  var can = document.createElement('canvas');
		  var ctx = can.getContext('2d');

		  // Set the canvas dimensions
		  $(can).attr('width', image.width);
		  $(can).attr('height', image.height);

		  // Draw the image to the canvas
		  ctx.drawImage(image, 0, 0, image.width, image.height);

		  // Get the image data
		  var image_data = ctx.getImageData(0, 0,  image.width, image.height);
		  var image_data_array = image_data.data;
		  var image_data_array_length = image_data_array.length;

		  // Array to hold the average totals
		  var a=[0,0,0];

		  // Accumulate the pixel colours
		  for (var i = 0; i < image_data_array_length; i += 4){
		    a[0]+=image_data_array[i];
		    a[1]+=image_data_array[i+1];
		    a[2]+=image_data_array[i+2];
		  }

		  // Divide by number total pixels
		  a[0] = Math.round(a[0]/=(image_data_array_length/3)); // R
		  a[1] = Math.round(a[1]/=(image_data_array_length/3)); // G
		  a[2] = Math.round(a[2]/=(image_data_array_length/3)); // B

		  // Create the container, set its background colour and add it to the DOM
		  var imageContainer = $('<div style="background-color:rgb('+a[0]+','+a[1]+','+a[2]+')"></div>');
		  $(".images").append(imageContainer);

		  // Insert the image to the container
		  $(imageContainer).append(image);
		}


$('#pageLoading').show();
$('#error_alert').hide();

$('.pic-loader').on('click', function() {
		var picId = $(this).attr('data-pic-id');
		var topic = $(this).attr('data-pic-topic');
		var titleId = $(this).attr('data-pic-title');
		$('#topix-pic-loader').attr('data-pic-id', picId);
		$('#topix-pic-loader').attr('data-pic-title', titleId);

		$('#topix-events-loader').attr('data-pic-topic', topic);
	});
	$('#topix_modal').on('shown', function () {
		$modalHeight = $(this).height();
		$('body').height(3000);
		var picId = $('#topix-pic-loader').attr('data-pic-id');
		var topic = $('#topix-events-loader').attr('data-pic-topic');
		var titleId = $('#topix-pic-loader').attr('data-pic-title');

		$('#topix-pic-loader').load('/photos/getPic?_id=' + picId);
		$('#topix-events-loader').load('/photos/search?topic=' + topic);
		$('#modal-event-topics-head').text(titleId);
    });

	$(document).ready(function(){
		var topic = $('#single_photo_view').attr('data-topic');
		$('#single_photo_view').load('/photos/search?topic=' + topic);
	});

	$(window).load(function(){
		$('#pageLoading').hide();
		$('div.row-fluid').removeClass('hidden');
	});
	$(document).on('click', '.delete-post', function(event){
		var id = $(this).attr('data-id');
		$('#modal_delete_btn').attr('data-id',id );
	});
	$(document).on('click', '#modal_delete_btn', function(event){
		event.preventDefault();
		var id = $(this).attr('data-id');
		$.post('/photos/deletePost', {id: id}, function(data){
			if(data.message === "AUTH_ERROR") {
				$('#error_alert').show();
				$('#error_text').text('You need to be logged in to delete your post. ');
			}

		}, 'json').done(function(){
				$('#cm_item_' + id).toggle('explode');
				$('#cm_item_' + id).remove();
		});
	});

	$(document).on('click', '.post-push-up', function(event){
		event.preventDefault();
		var signature = $(this).attr('data-signature');
		$.post('/photos/postPushUp', {signature: signature}, function(data){
			if(data.message === "AUTH_ERROR") {
				$('#error_alert').show();
				$('#error_text').text('You need to be logged in to give a thumbs up to a post. Log in now if you wish to vote.');
			}
		}, 'json')
		.done(
				$.post('/photos/getVoteCount', {signature: signature}, function(newData) {
					voteTotalNodeText = $('#vote_total_' + newData.userPhotos._id);
					$(voteTotalNodeText).text(newData.votes);
					$(voteTotalNodeText).addClass('red');
				}, 'json')
				);
	});


	$(document).on('click', '.flag-post', function(event){
		event.preventDefault();
		var signature = $(this).attr('data-signature');
		$.post('/photos/flagPost', {signature: signature}, function(data){
			if(data.message === "AUTH_ERROR") {
				$('#error_alert').show();
				$('#error_text').text('You need to be logged in to give a thumbs up to a post. Log in now if you wish to flag');
			}
		}, 'json')
		.done(
				$.post('/photos/getFlagCount', {signature: signature}, function(newData) {
					flagTotalNodeText = $('#flag_total_' + newData.userPhotos._id);
					$(flagTotalNodeText).text(newData.flags);
					$(flagTotalNodeText).addClass('red');
				}, 'json')
				);
	});

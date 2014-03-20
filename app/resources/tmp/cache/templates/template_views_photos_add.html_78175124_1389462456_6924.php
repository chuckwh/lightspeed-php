
<div class="row-fluid"  data-view="add">
		<div class="col-md-12">
			<?php echo $this->form->create($photo, array('type' => 'file', 'class' => 'photo-form ml15', 'id' => 'photo_form', 'name' => 'photo_form')); ?>
				<fieldset>
				<legend>What's Happening?</legend>
				<?php
				$publicId = $userSession['username'] . '/' . md5($count);
				$sortedParameters ='timestamp=' . time() . CLOUDINARY_API_SECRET;
				?>
				<?php
				/*
				Instructions for setting up a signed sha hash for cloudinary are here:
				http://cloudinary.com/documentation/upload_images#request_authentication
				The parameters noted in the instructions need to be in alphabetical order

				You need to sign a string with all parameters sorted by their names alphabetically. Separate parameter name and value with '=' and join parameters with '&'.

					Create a HEX digest string of a SHA1 signature of a string with the concatenation of all serialized parameters and your API secret.

					For example, if your API Key is '1234', your secret is 'abcd', the Unix time now is 1315060076 and you upload a file with the 'sample' Public ID:

					Parameters:
					timestamp: 1315060510
					public_id: "sample"
					file: DATA
					Serialized sorted parameters:
					"public_id=sample&timestamp=1315060510"
					String to create SHA1 HEX digest for:
					"public_id=sample&timestamp=1315060510abcd"
					SHA1 HEX digest:
					"c3470533147774275dd37996cc4d0e68fd03cd4f"
					Final request parameters:
					timestamp: 1315060510
					public_id: "sample"
					file: DATA
					signature: "c3470533147774275dd37996cc4d0e68fd03cd4f"

				 */
				?>
					<p class="help">Got something to say? Some local news? A recipe to share? A picture to share with other guests?
					Here is where to do it. Share your thoughts in the "Say Something" box,
					and add a picture if you wish. You can do it as often as you like and it's all searchable.</p>
					<?php echo $this->form->field('title', array('id'=>'title', 'class' => 'form-control', 'template' => '<div {:wrap}>{:label}<div class="help">Choose a title for your thoughts to make them easier to find</div>{:input}{:error}</div>')); ?>
					<?php echo $this->form->field('Say Something', array('type'=>'textarea', id=>"status", 'class'=>'form-control',  'rows'=> '6', 'template' => '<div {:wrap}>{:label}<div class="help">Add your thoughts</div>{:input}{:error}</div>')); ?>
					<div class="mt10 help">
					Add a topic in the text box below.
					</div>
					<div class="mt10 form-group">
						<div>
				              <input type="text" id="tags" name="tags" class="form-control">
				              <input type="hidden" id="user_name" name="user_name" value="<?php echo $h($userSession['username']); ?>">
				              <input type="hidden" id="image_upload" name="image_upload" value="">
				              <input type="hidden" id="image_url" name="image_url" value="">
				              <?php echo $this->security->requestToken(array('name'=>'cm-token')); ?>
						</div>
				    </div>
				    <?php echo $this->form->field('credit', array('id'=>'credit', 'class' => 'form-control', 'template' => '<div class="mt10" {:wrap}>{:label}<div class="help">Photo not yours? PLEASE provide a credit or URL</div>{:input}{:error}</div>')); ?>
					<div id="progress_bar">
					    <p id="progress_indicator"></p>
						<div class="progress">
							<div class="bar"></div>
						</div>
					</div>
					<div id="url_chooser">
					    <?php echo $this->form->field('url', array(
					    	'id'=>'url_field',
					    	'placeholder' => 'http://...',
							'type' => 'text',
					    	'class' => 'cloudinary-fileupload form-control',
							'data-cloudinary-field'=>'image_upload',
							'data-form-data' => json_encode(array(
								'timestamp' => time(),
								'signature' => sha1($sortedParameters),
								'api_key' => CLOUDINARY_API_KEY
								) ),
					    		'template' => '<div id="url" class="mt10" {:wrap}>{:label}<div class="preview"></div><div class="help">Paste a URL into the text box</div>{:input}{:error}</div>')); ?>
					    		<button class="btn mt10" id="upload_url_btn">Upload this image</button>
				    </div>

					<?php echo $this->form->field('file', array(
							'id' => 'file',
							'type' => 'file',
							'class' => 'filestyle cloudinary-fileupload',
							'data-cloudinary-field'=>'image_upload',
							'data-form-data' => json_encode(array(
								'timestamp' => time(),
								'signature' => sha1($sortedParameters),
								'api_key' => CLOUDINARY_API_KEY
								) ),
							'template' => '<div id="file_chooser" {:wrap}>{:label}<div class="preview"></div><div class="help">Click the "Choose File" button to select a photo from your computer</div>{:input}{:error}</div>')); ?>
					<div class="mt10">
						<?php echo $this->form->submit('Save', array( 'id'=> 'do_post',
								'data-secure-url' => '',
								'data-public-id' => '',
								'data-version' => '',
								'data-format' => '',
								'data-signature' => '',
								'data-width' => '',
								'data-height' => '',
								 'class' => 'btn btn-primary post-submit-btn')); ?>

								 <button class="btn btn-link ml10" id="add_from_web_btn">Add from Website</button>
								 <button class="btn btn-link ml10" id="add_from_file_btn">Add a file from your computer</button>
								 <p class="help mt10">
								 	You can also add a picture by right clicking on an image from a web page and choosing "Copy Image".
								 	Then, return to this page and click anywhere on the page. Hit Control V on a PC or Command V on a Mac and your picture
								 	will automatically begin to upload. Be sure to give credit to the website you take it from and respect all copyright issues.
								 	The picture can be flagged and removed for copyright violations.
								 </p>
					</div>
				</fieldset>
				<canvas></canvas>
			<?php echo $this->form->end(); ?>
	</div>
</div>

<div class="row-fluid"  id="post_results">
	<div class="col-md-3"></div>
	<div class="col-md-6">
		<div class="well">
			<h3>Your Post is Now Viewable.</h3>
			<p>
				<a id="view_post" href=""><span class="glyphicon glyphicon-eye-open"></span> Click here to see it.</a>
			</p>
			<div>
				<a href="/photos/add" class="btn btn-link" id="addmore_btn"><span class="glyphicon glyphicon-share"></span> Add Another Post</a>
				<a class="btn btn-link ml10" id="edit_btn"><span class="glyphicon glyphicon-pencil"></span> Edit This Post</a>
			</div>
			<div class="mt10" id="post_results_content">
			<span class="red archivo">Your Title: </span>
				<h4 id="post_results_title"></h4>
				<div class="preview"></div>
				<p>
					<span class="red archivo">Your News: </span>
					<span id="post_results_status"></span>
				</p>
				<p>
					<span class="red archivo">Topic: </span>
					<span id="post_results_tags"></span>
				</p>
				<p>
					<span class="red archivo">Picture Credit: </span>
					<span id="post_results_credit"></span>
				</p>
			</div>
		</div>
	</div>
	<div class="col-md-3"></div>
</div>

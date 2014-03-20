<?php
/**
 * Lithium: the most rad php framework
make a _init function and put $this->_render['layout'] = 'otherLayout'; to use another layout
 */
?>
<!doctype html>
<?= $this->ieconditionals->conditionals() ?>
<head>
<!-- layout photos -->
 <?php
//BUGNOTE This is important. First script MUST be the font script as indicated by the indexed value below
//So make sure the Fontscript component is at the top of the page
?>
 	<?= $this->fontscript->fontscript() ?>
	<?= $this->metatags->metatags(FALSE, FALSE) ?>


<title><?php echo $this->title(); ?></title>

	<?php echo $this->html->script(array(MODERNIZR_JS ) ); ?>
	<?php /* TODO need to add minimized files */ ?>
	<?php echo $this->html->style(array(BOOTSTRAP_STYLE_SHEET, GLOBAL_STYLE_SHEET, BOOTSTRAP_GLYPHS_STYLE_SHEET)); ?>
	<style type="text/css">
 		.cm-item {
 			width: 190px;
 			margin-bottom: 20px;
 		}
		.glyphicon {
		position: relative;
		top: 1px;
		display: inline-block;
		font-family: 'Glyphicons Halflings';
		-webkit-font-smoothing: antialiased;
		font-style: normal;
		font-weight: normal;
		line-height: 1;
		}
		body {
			padding: 0 4em;
		}

 	</style>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	<style type="text/css">
 	.bootstrap-filestyle label {
 		margin-bottom: 8px;
 	}
 	</style>
</head>
<body data-layout="photos">
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8 relative">
			<?php echo $this->html->image('cloudcover-blog.png', array('scope' => 'app'));?>
			<div class="archivo title">
 						Web Development in the Cloud
 			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div id="login-logout" class="col-md-8">
			<?= $this->nav->nav($userSession['username'], 'default') ?>
		</div>
		<div class="col-md-2"></div>
	</div>

<?php echo $this->html->script(array(
		
		JQUERY_2x,
		JQUERY_UI_JS,
		BOOTSTRAP_JS,
		CLOUDINARY_JQUERY_UI_WIDGET,
		CLOUDINARY_JQUERY_IFRAME_TRANSPORT,
		CLOUDINARY_JQUERY_FILEUPLOAD,
		CLOUDINARY_JS,
		GET_IMAGE_DATA_JS
) ); ?>

	<div class="row">
		<div class="col-md-2"></div>
			<div class="col-md-8">
				<?php echo $this->content(); ?>
			</div>
		<div class="col-md-2"></div>
	</div>


	<div class="row mt10 ">
	<div class="col-md-2"></div>
		<div class="col-md-8">
			<?= $this->footer->footer() ?>
		</div>
	<div class="col-md-2"></div>
	</div>
	<div id="pageLoading" class="page-loading">
		<div id="loadingDiv" class="loading-div">

		    <div class="progress progress-striped active">
		    	<div class="bar" style="width: 100%;"></div>
		    </div>
		    <div class="loading-div-msg">Please wait while your page loads...</div>
		</div>
	</div>
	<script type="text/javascript">
	 $.cloudinary.config({'api_key':'<?= CLOUDINARY_API_KEY?>','cloud_name':'<?= CLOUDINARY_CLOUD_NAME ?>'});
	</script>
	<?php echo $this->html->script(array(PHOTOS_JS, MASONRY_JS) ); ?>

</body>
</html>
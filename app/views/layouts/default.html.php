<?php
$viewScript = null;
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2013, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
    <?php
    //BUGNOTE This is important. First script MUST be the font script below
    //So make sure the Fontscript component is at the top of the page
    ?>
    <?= $this->fontscript->fontscript() ?>
    <?= $this->metatags->metatags() ?>
	<title>Application &gt; <?php echo $this->title(); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $this->html->style(array('bootstrap', 'bootstrap-theme', 'app')); ?>
	<?php //if you have an icon file, you can add that here ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body data-layout="default">
	<div class="row mt10">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<?= $this->nav->nav($userSession['username']) ?>
		</div>
		<div class="col-md-2"></div>
	</div>
	<div class="row">

		<div class="col-md-12">
			<div class="container">
				<h1>Web Development in the Cloud</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="container">
				<?php echo $this->content(); ?>
			</div>
		</div>
	</div>
	<div class="row mt10">
		<div class="col-md-12">	
			<div class="container">
				<div class="footer">
					<p>Your Code on the Cloud</p>
				</div>
			</div>	
		</div>
	</div>

	



	</div>
	<?php echo $this->html->script(array('jquery',  'bootstrap', 'lib/jsTimezoneDetect-source', 'app/book' ) ); ?>


</body>
</html>
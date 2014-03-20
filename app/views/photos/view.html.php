<?php
$photoCredit = null;
$status = null;
$topic = null;

if( $photo->credit !=null) {
	$photoCredit = $photo->credit;
	$photoCreditTruncated = substr($photoCredit, 0, 50) . '...';
}
else {
	$photoCredit = 'N/A';
	$photoCreditTruncated = '';
}
if( $photo->status !=null) {
	$status = $photo->status;
}
else {
	$status = ' - - -';
}
if( $photo->tags !=null) {
	$topic = $photo->tags;
}
else {
	$topic = ' - - -';
}
if( $photo->title !=null) {
	$title = $photo->title;
}
else {
	$title = 'Untitled';
}
$photoCreditTruncated = substr($photoCredit, 0, 50) . '...';


function nl2p($string, $line_breaks = true, $xml = false) {

$string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);

// It is conceivable that people might still want single line-breaks
// without breaking into a new paragraph.
if ($line_breaks == true)
    return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'), trim($string)).'</p>';
else
    return '<p>'.preg_replace(
    array("/([\n]{2,})/i", "/([\r\n]{3,})/i","/([^>])\n([^<])/i"),
    array("</p>\n<p>", "</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'),

    trim($string)).'</p>';


}

$parsedStatus  = nl2p($status);


?>
<?php $this->title('Talk about ' . $photo->title .': Video Chat Speed Dating on FacingFive'); ?>
<div class="row" data-view="view">
<div class="span2"></div>
<div class="span8">
	<h1><?=$title; ?></h1>
	<div class="well">
		<h4>Topic: <?= $topic ?>
		<?php if($canEdit) {?>
			<a href="/photos/edit/<?= $photo->_id ?>" class="btn btn-link  edit-post"><span class="glyphicon glyphicon-pencil "></span></a>

		<?php }?>
		</h4>

		<div class="mt10">
		<div id="pindiv"><?php ////replace "yourcloud" with your actual cloudinary directory ?>
			<a href="//www.pinterest.com/pin/create/button/?url=http%3A%2F%2Fyourdomain.com%2Fphotos%2Fview%2F<?= $photo->_id ?>&media=https%3A%2F%2Fres.cloudinary.com%2Fyourcloud8%2Fimage%2Fupload%2Fv<?= $photo->version ?>%2F<?= $photo->public_id ?>.<?= $photo->format ?>&description=View%20This%20Pic!%20in%20<?= $photo->tags ?>%20at%20yourawesomesite!" data-pin-do="buttonPin" data-pin-config="none"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>		</div>
			<?=$this->html->image(CLOUDINARY_BASE_URL . "/image/upload/v{$photo->version}/{$photo->public_id}.{$photo->format}", array( 'id' => 'i_' . $photo->_id)); ?>
		</div>
		<div class="mt10">
			<p><?php echo $parsedStatus ?></p>
		</div>
		<div class="mt10">
				<?php if($photoCredit) {?>
			Photo credit:
			<?php
			if(strpos($photoCredit, "http://") !== false || strpos($photoCredit, "https://") !== false) {
			?>
			<a target="_blank" title="<?=$photoCredit ?>" href="<?=$photoCredit ?>"><?=$photoCreditTruncated ?></a>
			<?php } else {?>
			<?=$photoCredit ?>
			<?php }
			}?>
		</div>
	</div>

	<div>
		<p>More posts by <a href="/photos/guestPosts/<?=$photo->user_id ?>"><?=$photo->user_name ?></a></p>
	</div>
</div>
<div class="span2"></div>
</div>

<?php echo $this->html->script(array('pinit') ); ?>
